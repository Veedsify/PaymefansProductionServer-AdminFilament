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

            $data = [
                'email' => $this->email,
                'password' => $this->password
            ];

            $response = Http::asForm()->post($endpoint, $data);

            $data = $response->json();

            // Debug the response from the API
            if (!$response->successful()) {
                return [
                    'error' => 'API call failed: ' . $response->status(),
                    'status' => false
                ];
            }

            if (isset($data['token'])) {

                $user = User::where('email', $this->email)->first();

                if (!$user) {
                    return [
                        'error' => 'User not found.',
                        'status' => false
                    ];
                }

                if ($user->role != 'admin') {
                    return [
                        'error' => 'You are not an admin.',
                        'status' => false
                    ];
                }

                Auth::login($user);

                return [
                    'status' => true,
                    'token' => $data['token']
                ];
            }else{
                return [
                    'error' => 'Invalid Email Or Password',
                    'status' => false
                ];
            }
        } catch (\Exception $e) {
            return [
                'error' => $e->getMessage(),
                'status' => false
            ];
        }
    }
    public function login()
    {
        $this->validate();

        try {

            $response = $this->loginUser();

            if (isset($response['status']) && $response['status'] == false) {
                $this->addError('error', $response['error']);
                $this->reset('password');
                return;
            } else {
                return $this->redirect('/admin');
            }
        } catch (\Exception $e) {
            $this->addError('error', 'Something went wrong. Please try again.');
        }
    }

    public function render()
    {
        return view('livewire.login-component')->layout('layouts.auth');
    }
}
