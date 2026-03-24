<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PaymentController extends Controller
{
    //
    public function yellowcardChannels()
    {
        $response = Http::withHeaders([
            'accept' => 'application/json',
        ])->get('https://sandbox.api.yellowcard.io/business/channels');

        if ($response->successful()) {
            $data = $response->json(); // Automatically decodes the JSON response

            // You can inspect the data or return it
            return $data;
        }

        // Handle any errors
        return response()->json([
            'error' => 'Failed to fetch channels from Yellow Card API'
        ], $response->status());
    }

}