<?php

use App\Livewire\LoginComponent;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (Auth::check()) {
        return redirect("/admin");
    }
    return redirect()->route('login');
    // return DB::connection("mongodb")->table("comments")->paginate(5);
});

Route::get('/login', LoginComponent::class)->name('login');
