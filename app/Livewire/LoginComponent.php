<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class LoginComponent extends Component
{
    public $email = '';
    public $password = '';
    public $remember = false;

    protected $rules = [
        'email' => 'required|email',
        'password' => 'required|min:8'
    ];

    protected $messages = [
        'email.required' => 'Please enter your email address',
        'email.email' => 'Please enter a valid email address',
        'password.required' => 'Please enter your password',
        'password.min' => 'Password must be at least 8 characters'
    ];

    public function loginUser()
    {
        try {
            $server = env('BACKEND_URL');

            if (!$server) {
                return [
                    'error' => 'BACKEND_URL is not defined in the .env file.',
                    'status' => false
                ];
            }

            $endpoint = $server . '/api/auth/login';

            $credentials = [
                'email' => $this->email,
                'password' => $this->password
            ];

            $response = Http::asForm()->post($endpoint, $credentials);

            if (!$response->successful()) {
                return [
                    'error' => 'API call failed with status ' . $response->status(),
                    'status' => false
                ];
            }

            $responseData = $response->json();

            if (!isset($responseData['token'])) {
                return [
                    'error' => 'Invalid email or password.',
                    'status' => false
                ];
            }

            $user = User::where('email', $this->email)->first();

            if (!$user) {
                return [
                    'error' => 'User not found in local database.',
                    'status' => false
                ];
            }

            if ($user->role !== 'admin') {
                return [
                    'error' => 'You are not authorized as an admin.',
                    'status' => false
                ];
            }

            // Store the token in the session
            session(['token' => $responseData['token']]);
            Auth::login($user);

            return [
                'status' => true,
                'token' => $responseData['token']
            ];
        } catch (\Exception $e) {
            return [
                'error' => 'Exception: ' . $e->getMessage(),
                'status' => false
            ];
        }
    }

    public function login()
    {
        $this->validate();

        try {
            $response = $this->loginUser();

            if (!isset($response['status']) || $response['status'] === false) {
                $this->addError('error', $response['error'] ?? 'Login failed.');
                $this->reset('password');
                return redirect('/login');
            }

            return redirect('/admin');
        } catch (\Exception $e) {
            $this->addError('error', 'Something went wrong. Please try again.');
            return redirect('/login');
        }
    }

    public function render()
    {
        return view('livewire.login-component')->layout('layouts.auth');
    }
}
