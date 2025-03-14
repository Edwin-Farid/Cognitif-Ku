<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        try {
            // Check if user is authenticated
            $user = Auth::user();
            if (!$user || empty($user->cognifit_user_token)) {
                Log::warning('User is missing CogniFit token.', ['user_id' => $user?->id]);
                return redirect()->route('home')->with('error', 'You need to connect your account with CogniFit first.');
            }
            session(['user_token' => $user->cognifit_user_token, 'client_id' => env('COGNIFIT_CLIENT_ID'), 'client_secret' => env('COGNIFIT_CLIENT_SECRET')]);
            return view('home');
        } catch (\Exception $e) {
            Log::error('Failed to retrieve game list', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'Failed to retrieve game list: ' . $e->getMessage());
        }
    }
}
