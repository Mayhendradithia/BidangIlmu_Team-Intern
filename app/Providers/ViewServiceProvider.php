<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Kategori;

class ViewServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Kirim data kategori ke navbar (semua view yang membutuhkan data kategori)
        View::composer('layout', function ($view) {
            $view->with('kategoris', Kategori::all());
        });
    }

    public function register()
    {
        //
    }
}


