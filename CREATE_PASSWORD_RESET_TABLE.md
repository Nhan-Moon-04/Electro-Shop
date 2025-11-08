# Create `password_reset_tokens` table — manual SQL

If your friend cloned the repository but didn't run the migrations (or the migration failed because of FK issues), they can create the required table manually using this SQL.

Use the SQL below against the project's `electro` database (MySQL/MariaDB).

Important: this SQL intentionally does NOT add a foreign-key constraint to `users.user_id` because the existing `users` table in this project dump does not declare `user_id` as a proper PRIMARY KEY/UNIQUE index which can cause FK creation to fail. Instead the table includes an index on `MaNguoiDung` for lookups.

---

SQL to run:

```sql
CREATE TABLE `password_reset_tokens` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `MaNguoiDung` INT UNSIGNED NOT NULL,
  `Token` VARCHAR(100) NOT NULL,
  `ExpireAt` DATETIME NOT NULL,
  `Used` TINYINT(1) NOT NULL DEFAULT 0,
  INDEX (`MaNguoiDung`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

How to run (example using MySQL client):

```powershell
# From Windows PowerShell (replace host/user/password/db as needed):
mysql -h 127.0.0.1 -P 3306 -u root -p electro
# (enter password when prompted)
# then paste the CREATE TABLE SQL and run it
```

Or run it directly with one command:

```powershell
mysql -h 127.0.0.1 -P 3306 -u root -e "CREATE TABLE `password_reset_tokens` ( `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY, `MaNguoiDung` INT UNSIGNED NOT NULL, `Token` VARCHAR(100) NOT NULL, `ExpireAt` DATETIME NOT NULL, `Used` TINYINT(1) NOT NULL DEFAULT 0, INDEX (`MaNguoiDung`) ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;" electro
```

Alternative (no mysql client needed)

If your friend doesn't have the `mysql` CLI available, they can run a PHP helper included in this repo which uses the project's DB config from `.env` to create the table. Run this from the project root:

```powershell
php scripts/create_password_reset_table.php
```

The script will check for the table first and only create it if it doesn't exist.

Notes & troubleshooting

-   If you prefer to add a foreign key to `users.user_id`, ensure `users.user_id` is a PRIMARY KEY or at least has a UNIQUE index before adding the FK. Example (careful, altering primary keys may have side effects):

    ```sql
    ALTER TABLE `users` ADD PRIMARY KEY (`user_id`);
    ALTER TABLE `password_reset_tokens` ADD CONSTRAINT `fk_prt_user` FOREIGN KEY (`MaNguoiDung`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;
    ```

-   If the migration file `2025_10_19_000001_create_password_reset_tokens_table.php` exists in `database/migrations` and you want to keep using migrations, after creating the table manually you should insert a record into the `migrations` table so Laravel doesn't attempt to re-run the same migration. Example SQL to mark it as migrated:

```sql
INSERT INTO migrations (migration, batch) VALUES ('2025_10_19_000001_create_password_reset_tokens_table', 1);
```

-   If you already ran the migration and got errors, inspect both the `migrations` table and the database tables. Deleting a partially created table may be necessary before running the migration again.

-   After the table is present, the forgot-password flow in the app will be able to insert tokens into `password_reset_tokens`.

If you want, I can also provide a one-liner PowerShell script your friend can run that will apply the SQL (if they have `mysql` client installed) or I'll add a small PHP helper to execute the statement using the project's DB config.

php artisan config:clear; php artisan cache:clear; php artisan config:cache
