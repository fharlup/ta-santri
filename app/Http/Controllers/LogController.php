<?php

namespace App\Http\Controllers;

use App\Models\Log;
use Illuminate\Http\Request;

class LogController extends Controller
{
    // FR-06: Melihat riwayat aktivitas pengguna (Log System)
    public function index()
    {
        // Mengambil log beserta data User yang melakukannya
        $logs = Log::with('user')->latest()->get();
        
        return view('logs.index', compact('logs'));
    }
}