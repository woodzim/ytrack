<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class LogActivityController extends Controller
{
    public function index()
    {
        // Ambil data log, urutkan dari yang terbaru
        // Gunakan pagination agar halaman tidak berat
        $logs = ActivityLog::with('user')
            ->latest()
            ->paginate(15);

        return view('pages.admin.logs.index', compact('logs'));
    }
}