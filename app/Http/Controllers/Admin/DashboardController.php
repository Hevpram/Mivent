<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\User;
use Illuminate\Http\Request;

/** Dashboard Admin Ormawa & Admin FMIPA. */
class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user()->load('organisasi');

        if ($user->isOrmawa()) {
            $base = Event::managedBy($user);

            return view('admin.dashboard-ormawa', [
                'user' => $user,
                'stats' => [
                    'total' => (clone $base)->count(),
                    'aktif' => (clone $base)->published()->where('tahap', '!=', 'selesai')->count(),
                    'selesai' => (clone $base)->published()->where('tahap', 'selesai')->count(),
                ],
                'events' => (clone $base)->withCount('registrations')->with('divisi')->latest()->take(5)->get(),
            ]);
        }

        return view('admin.dashboard-fmipa', [
            'user' => $user,
            'stats' => [
                'menunggu' => Event::where('status', 'menunggu')->count(),
                'disetujui' => Event::published()->count(),
                'revisi' => Event::where('status', 'revisi')->count(),
                'ormawa' => User::where('role', User::ORMAWA)->count(),
            ],
            // Pengajuan dari ormawa terbaru (antrean di atas)
            'events' => Event::whereHas('creator', fn ($c) => $c->where('role', User::ORMAWA))->with('organisasi')
                ->orderByRaw("CASE status WHEN 'menunggu' THEN 0 WHEN 'revisi' THEN 1 ELSE 2 END")->latest()->take(5)->get(),
        ]);
    }
}
