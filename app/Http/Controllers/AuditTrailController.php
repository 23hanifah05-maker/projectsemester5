<?php

namespace App\Http\Controllers;

use App\Models\AuditTrail;
use Illuminate\Http\Request;

class AuditTrailController extends Controller
{
    public function index(Request $request)
    {
        $dari = $request->input('dari');
        $sampai = $request->input('sampai');
        $keyword = trim($request->input('keyword', ''));

        $logs = AuditTrail::query()
            ->when($dari, fn ($q) => $q->whereDate('created_at', '>=', $dari))
            ->when($sampai, fn ($q) => $q->whereDate('created_at', '<=', $sampai))
            ->when($keyword !== '', function ($q) use ($keyword) {
                $q->where(function ($w) use ($keyword) {
                    $w->where('username', 'like', "%{$keyword}%")
                      ->orWhere('aktivitas', 'like', "%{$keyword}%")
                      ->orWhere('modul', 'like', "%{$keyword}%")
                      ->orWhere('no_rm', 'like', "%{$keyword}%")
                      ->orWhere('bidang_kolom', 'like', "%{$keyword}%");
                });
            })
            ->orderByDesc('id')
            ->get();

        return view('audit-trail.index', compact('logs', 'dari', 'sampai', 'keyword'));
    }
}