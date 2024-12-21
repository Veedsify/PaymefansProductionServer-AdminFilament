<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', function () {
    $user = User::where('role', 'admin')->first();  
    Auth::login($user);
    return redirect('/admin');
})->name('login');
