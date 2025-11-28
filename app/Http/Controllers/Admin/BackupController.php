<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

class BackupController extends Controller
{
    public function index()
    {
        $backupPath = storage_path('app/backups');
        
        if (!File::exists($backupPath)) {
            File::makeDirectory($backupPath, 0755, true);
        }

        $backups = [];
        $files = File::files($backupPath);
        
        foreach ($files as $file) {
            $backups[] = [
                'name' => $file->getFilename(),
                'size' => $this->formatBytes($file->getSize()),
                'date' => date('d/m/Y H:i:s', $file->getMTime()),
                'path' => $file->getPathname()
            ];
        }

        // Sort by date descending
        usort($backups, function($a, $b) {
            return strcmp($b['date'], $a['date']);
        });

        return view('admin.backup.index', compact('backups'));
    }

    public function create()
    {
        try {
            $backupPath = storage_path('app/backups');
            
            if (!File::exists($backupPath)) {
                File::makeDirectory($backupPath, 0755, true);
            }

            $filename = 'backup_' . date('Y-m-d_His') . '.sql';
            $filepath = $backupPath . '/' . $filename;

            // Get database configuration
            $dbHost = env('DB_HOST', '127.0.0.1');
            $dbPort = env('DB_PORT', '3306');
            $dbName = env('DB_DATABASE');
            $dbUser = env('DB_USERNAME');
            $dbPass = env('DB_PASSWORD');

            // Path to mysqldump (adjust if needed)
            $mysqldumpPath = 'mysqldump'; // or full path like 'C:\\xampp\\mysql\\bin\\mysqldump.exe'

            // Build mysqldump command
            $command = sprintf(
                '%s --host=%s --port=%s --user=%s --password=%s %s > %s 2>&1',
                $mysqldumpPath,
                escapeshellarg($dbHost),
                escapeshellarg($dbPort),
                escapeshellarg($dbUser),
                escapeshellarg($dbPass),
                escapeshellarg($dbName),
                escapeshellarg($filepath)
            );

            // Execute command
            exec($command, $output, $returnCode);

            if ($returnCode !== 0 || !File::exists($filepath) || File::size($filepath) < 100) {
                // Fallback: Create backup using Laravel DB
                $this->createBackupUsingLaravel($filepath);
            }

            return redirect()->route('admin.backup.index')
                ->with('success', 'Backup được tạo thành công: ' . $filename);
        } catch (\Exception $e) {
            \Log::error('Backup error: ' . $e->getMessage());
            return redirect()->route('admin.backup.index')
                ->with('error', 'Lỗi khi tạo backup: ' . $e->getMessage());
        }
    }

    private function createBackupUsingLaravel($filepath)
    {
        $tables = DB::select('SHOW TABLES');
        $dbName = env('DB_DATABASE');
        $tableKey = 'Tables_in_' . $dbName;
        
        $sql = "-- MySQL Backup\n";
        $sql .= "-- Generated: " . date('Y-m-d H:i:s') . "\n\n";
        $sql .= "SET FOREIGN_KEY_CHECKS=0;\n\n";

        foreach ($tables as $table) {
            $tableName = $table->$tableKey;
            
            // Drop table
            $sql .= "DROP TABLE IF EXISTS `{$tableName}`;\n";
            
            // Create table
            $createTable = DB::select("SHOW CREATE TABLE `{$tableName}`");
            $sql .= $createTable[0]->{'Create Table'} . ";\n\n";
            
            // Insert data
            $rows = DB::table($tableName)->get();
            
            if ($rows->count() > 0) {
                foreach ($rows as $row) {
                    $values = [];
                    foreach ($row as $value) {
                        if (is_null($value)) {
                            $values[] = 'NULL';
                        } else {
                            $values[] = "'" . addslashes($value) . "'";
                        }
                    }
                    $sql .= "INSERT INTO `{$tableName}` VALUES (" . implode(', ', $values) . ");\n";
                }
                $sql .= "\n";
            }
        }

        $sql .= "SET FOREIGN_KEY_CHECKS=1;\n";
        
        File::put($filepath, $sql);
    }

    public function download($filename)
    {
        $filepath = storage_path('app/backups/' . $filename);

        if (!File::exists($filepath)) {
            return redirect()->route('admin.backup.index')
                ->with('error', 'File backup không tồn tại');
        }

        return response()->download($filepath);
    }

    public function delete($filename)
    {
        try {
            $filepath = storage_path('app/backups/' . $filename);

            if (File::exists($filepath)) {
                File::delete($filepath);
                return redirect()->route('admin.backup.index')
                    ->with('success', 'Xóa backup thành công');
            }

            return redirect()->route('admin.backup.index')
                ->with('error', 'File backup không tồn tại');
        } catch (\Exception $e) {
            return redirect()->route('admin.backup.index')
                ->with('error', 'Lỗi khi xóa backup: ' . $e->getMessage());
        }
    }

    public function restore(Request $request)
    {
        try {
            if ($request->hasFile('backup_file')) {
                $file = $request->file('backup_file');
                $content = File::get($file->getRealPath());
            } else {
                $filename = $request->input('filename');
                $filepath = storage_path('app/backups/' . $filename);
                
                if (!File::exists($filepath)) {
                    return redirect()->route('admin.backup.index')
                        ->with('error', 'File backup không tồn tại');
                }
                
                $content = File::get($filepath);
            }

            // Set SQL mode to allow invalid dates and disable strict mode temporarily
            DB::statement("SET SESSION sql_mode = 'NO_ENGINE_SUBSTITUTION'");
            DB::statement('SET FOREIGN_KEY_CHECKS=0');
            DB::statement('SET UNIQUE_CHECKS=0');
            DB::statement('SET AUTOCOMMIT=0');

            // Start transaction
            DB::beginTransaction();

            // Split SQL statements by semicolon but keep multi-line statements together
            $statements = $this->splitSqlStatements($content);

            // Execute each statement
            $executedCount = 0;
            foreach ($statements as $statement) {
                $statement = trim($statement);
                
                if (empty($statement)) continue;
                
                // Skip comments
                if (preg_match('/^(--|\/\*|#)/', $statement)) continue;
                
                try {
                    DB::unprepared($statement);
                    $executedCount++;
                } catch (\Exception $e) {
                    \Log::warning('Statement execution warning: ' . $e->getMessage());
                    \Log::warning('Statement: ' . substr($statement, 0, 200));
                    // Continue with other statements
                }
            }

            // Commit transaction
            DB::commit();

            // Re-enable checks
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
            DB::statement('SET UNIQUE_CHECKS=1');
            DB::statement('SET AUTOCOMMIT=1');
            DB::statement("SET SESSION sql_mode = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION'");

            return redirect()->route('admin.backup.index')
                ->with('success', "Khôi phục dữ liệu thành công! Đã thực thi {$executedCount} câu lệnh SQL.");
        } catch (\Exception $e) {
            DB::rollBack();
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
            DB::statement('SET UNIQUE_CHECKS=1');
            DB::statement('SET AUTOCOMMIT=1');
            DB::statement("SET SESSION sql_mode = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION'");
            
            \Log::error('Restore error: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return redirect()->route('admin.backup.index')
                ->with('error', 'Lỗi khi khôi phục: ' . $e->getMessage());
        }
    }

    private function splitSqlStatements($sql)
    {
        $statements = [];
        $current = '';
        $inString = false;
        $stringChar = '';
        $length = strlen($sql);
        
        for ($i = 0; $i < $length; $i++) {
            $char = $sql[$i];
            $nextChar = ($i + 1 < $length) ? $sql[$i + 1] : '';
            
            // Handle string literals
            if (($char === "'" || $char === '"') && ($i === 0 || $sql[$i - 1] !== '\\')) {
                if (!$inString) {
                    $inString = true;
                    $stringChar = $char;
                } elseif ($char === $stringChar) {
                    $inString = false;
                }
            }
            
            // Split on semicolon if not in string
            if ($char === ';' && !$inString) {
                $statements[] = $current;
                $current = '';
                continue;
            }
            
            $current .= $char;
        }
        
        // Add last statement if exists
        if (!empty(trim($current))) {
            $statements[] = $current;
        }
        
        return $statements;
    }

    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        $bytes /= pow(1024, $pow);

        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}
