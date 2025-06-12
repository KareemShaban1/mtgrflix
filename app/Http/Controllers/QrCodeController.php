<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QrCodeController extends Controller
{
    public function generate()
    {
        // Generate QR code with text "Hello, Laravel 11!"
        $qrCode = QrCode::size(300)->generate('Hello, Laravel 11!');

        return response($qrCode)->header('Content-Type', 'image/svg+xml');
    }
}
