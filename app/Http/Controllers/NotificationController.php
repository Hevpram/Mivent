<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/** Halaman Notifikasi (semua role): tandai dibaca, hapus. */
class NotificationController extends Controller
{
    public function index(Request $request)
    {
        return view('notifications.index', ['notifications' => $request->user()->notifications()->paginate(10)]);
    }

    public function read(Request $request, string $id)
    {
        $request->user()->notifications()->findOrFail($id)->markAsRead();

        return back();
    }

    /** Buka tautan notifikasi sekaligus menandai dibaca. */
    public function open(Request $request, string $id)
    {
        $n = $request->user()->notifications()->findOrFail($id);
        $n->markAsRead();

        return redirect($n->data['url'] ?? route('notifications'));
    }

    public function readAll(Request $request)
    {
        $request->user()->unreadNotifications->markAsRead();

        return back()->with('success', 'Semua notifikasi ditandai dibaca.');
    }

    public function destroy(Request $request, string $id)
    {
        $request->user()->notifications()->findOrFail($id)->delete();

        return back()->with('success', 'Notifikasi dihapus.');
    }
}
