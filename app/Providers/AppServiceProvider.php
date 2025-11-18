<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Category;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Share categories với tất cả views để dùng trong header menu
        View::composer('*', function ($view) {
            $headerCategories = Category::where('category_is_display', 1)
                ->orderBy('category_id', 'asc')
                ->limit(12)
                ->get();

            $view->with('headerCategories', $headerCategories);
        });
    }
}
