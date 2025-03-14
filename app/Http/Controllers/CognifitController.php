<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use CognifitSdk\Api\Product;

class CognifitController extends Controller
{
    public function getGames()
    {
        try {
            // Check if user is authenticated
            $user = Auth::user();
            if (!$user || empty($user->cognifit_user_token)) {
                Log::warning('User is missing CogniFit token.', ['user_id' => $user?->id]);
                return redirect()->route('home')->with('error', 'You need to connect your account with CogniFit first.');
            }
            // dd(env('COGNIFIT_CLIENT_ID'));
            // dd($user->cognifit_user_token);
            // Store token in session
            session(['user_token' => $user->cognifit_user_token, 'client_id' => env('COGNIFIT_CLIENT_ID'), 'client_secret' => env('COGNIFIT_CLIENT_SECRET')]);
            // dd(session('client_id'));
            // Get games from CogniFit API
            $product = new Product(env('COGNIFIT_CLIENT_ID'), env('COGNIFIT_SANDBOX', false));
            $localesForAssets = ['en', 'es'];
            $games = $product->getGames($localesForAssets);
            // dd($games);

            if (empty($games)) {
                Log::warning('No games returned from CogniFit API');
            } else {
                Log::info('Retrieved ' . count($games) . ' games from CogniFit');
            }

            return view('cognifit.games', compact('games'));
        } catch (\Exception $e) {
            Log::error('Failed to retrieve game list', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'Failed to retrieve game list: ' . $e->getMessage());
        }
    }
    // public function launchGame($gameKey)
    // {
    //     dd(config('services.cognifit.client_id'));
    //     $userToken = session('user_token');
    //     $clientId = config('services.cognifit.client_id');

    //     if (!$userToken || !$clientId) {
    //         return response()->json(['error' => 'Authentication required'], 401);
    //     }

    //     return response()->json([
    //         'gameUrl' => "https://app.cognifit.com/gamewebsite/game/{$gameKey}",
    //         'token' => $userToken,
    //         'clientId' => $clientId
    //     ]);
    // }
}
