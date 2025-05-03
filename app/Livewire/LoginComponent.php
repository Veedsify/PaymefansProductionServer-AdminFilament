<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

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

    public function mount()
    {
        if (Auth::check()) {
            return redirect('/admin');
        }
    }

    public function loginUser()
    {
        try {

            if (Auth::check()) {
                return $this->redirect('/admin');
            }

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
                    'error' => $response->json()['error'] ?? 'API call failed with status ' . $response->status(),
                    'status' => false
                ];
            }

            $responseData = $response->json();
            if (empty($responseData['token'])) {
                return [
                    'error' => $responseData['error'] ?? 'Invalid email or password.',
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

            if ($user->role !== 'admin') { // Consider making this configurable
                return [
                    'error' => 'You are not authorized as an admin.',
                    'status' => false
                ];
            }

            // Use Laravel's authentication system to store the token securely
            auth()->login($user);
            session(['token' => $responseData['token']]); // Store token in DB if needed

            return [
                'status' => true,
                'token' => $responseData['token']
            ];
        } catch (\Illuminate\Http\Client\RequestException $e) {
            Log::error('API request failed: ' . $e->getMessage());
            return [
                'error' => 'API request failed: ' . $e->getMessage(),
                'status' => false
            ];
        } catch (\Exception $e) {
            Log::error('Unexpected error: ' . $e->getMessage());
            return [
                'error' => 'Unexpected error: ' . $e->getMessage(),
                'status' => false
            ];
        }
    }

    public function login()
    {
        $this->validate();
        try {
            $response = $this->loginUser();

            if (!isset($response['status']) || $response['status'] !== true || empty($response['token'])) {
                $this->addError('error', $response['error'] ?? 'Login failed.');
                $this->reset('password');
                return;
            }

            $this->redirect('/admin');
        } catch (\Exception $e) {
            Log::error('Login error: ' . $e->getMessage());
            $this->addError('error', 'An unexpected error occurred. Please try again.');
            $this->reset('password');
        }
    }



    public function render()
    {
        return view('livewire.login-component')->layout('layouts.auth');
    }
}
