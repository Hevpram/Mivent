<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Skp;
use App\Notifications\MiventNotification;
use Illuminate\Http\Request;

/** Verifikasi Event oleh Admin FMIPA: setujui (+ tetapkan SKP) atau kirim revisi. */
class VerificationController extends Controller
{
    public function index(Request $request)
    {
        // Hanya pengajuan dari ormawa (event Admin FMIPA tidak melalui verifikasi)
        $base = Event::whereHas('creator', fn ($c) => $c->where('role', 'admin_ormawa'));
        $query = (clone $base)->with('organisasi')->latest();

        if ($request->filled('q')) {
            $query->where('nama_kegiatan', 'like', '%' . $request->q . '%');
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('dari')) {
            $query->whereDate('tanggal_selesai', '>=', $request->dari);
        }
        if ($request->filled('sampai')) {
            $query->whereDate('tanggal_mulai', '<=', $request->sampai);
        }

        return view('admin.verifikasi.index', [
            'events' => $query->paginate(10)->withQueryString(),
            'stats' => [
                'menunggu' => (clone $base)->where('status', 'menunggu')->count(),
                'disetujui' => (clone $base)->where('status', 'disetujui')->count(),
                'revisi' => (clone $base)->where('status', 'revisi')->count(),
            ],
        ]);
    }

    public function show(Event $event)
    {
        $event->load('organisasi', 'kategori', 'skp', 'timelines', 'syarat', 'divisi', 'creator');

        return view('admin.verifikasi.show', ['event' => $event, 'skp' => Skp::orderBy('id')->get()]);
    }

    public function approve(Request $request, Event $event)
    {
        abort_if($event->status === 'disetujui', 422, 'Event sudah disetujui.');
        $request->validate(['skp_id' => 'required|exists:skp,id'], [], ['skp_id' => 'kategori SKP']);

        $event->update([
            'status' => 'disetujui',
            'skp_id' => $request->skp_id,
            'catatan_revisi' => null,
            'verified_by' => $request->user()->id,
            'verified_at' => now(),
        ]);
        $event->creator?->notify(new MiventNotification(
            'Event disetujui', "{$event->nama_kegiatan} telah diverifikasi dan kini tayang di Mivent.", route('manage.show', $event), 'success'
        ));

        return redirect()->route('fmipa.verifikasi.index')->with('success', 'Event Berhasil Diverifikasi dan kini tayang.');
    }

    public function revise(Request $request, Event $event)
    {
        abort_if($event->status === 'disetujui', 422, 'Event sudah disetujui.');
        $request->validate(['catatan_revisi' => 'required|string|max:2000'], [], ['catatan_revisi' => 'catatan revisi']);

        $event->update(['status' => 'revisi', 'catatan_revisi' => $request->catatan_revisi]);
        $event->creator?->notify(new MiventNotification(
            'Event perlu revisi', "{$event->nama_kegiatan}: {$request->catatan_revisi}", route('manage.edit', $event), 'warning'
        ));

        return redirect()->route('fmipa.verifikasi.index')->with('success', 'Event Dikirim Kembali untuk Revisi.');
    }
}
