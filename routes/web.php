<?php

use App\Livewire\LoginComponent;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect(route('login'));
});

Route::get('/login', LoginComponent::class)->name('login')->middleware('guest');
