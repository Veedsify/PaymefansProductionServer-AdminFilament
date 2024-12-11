<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', function () {
    $user = User::find(1);  

    Auth::login($user);

    return redirect('/admin');
})->name('login');
