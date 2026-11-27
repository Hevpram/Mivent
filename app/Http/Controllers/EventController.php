<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Kategori;
use App\Models\Organisasi;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

/**
 * Halaman publik: landing, jelajahi event (cari + filter), detail event.
 * Juga dipakai admin (Jelajahi Event) dengan layout admin.
 */
class EventController extends Controller
{
    public function home()
    {
        $events = Event::published()->with('organisasi', 'skp', 'kategori')
            ->orderByRaw("CASE WHEN tahap = 'pendaftaran' THEN 0 ELSE 1 END")
            ->orderBy('tanggal_mulai')->take(4)->get();

        return view('home', compact('events'));
    }

    public function index(Request $request)
    {
        $tampil = min(max((int) $request->input('tampil', 9), 9), 90); // "Temukan Lebih Banyak" menambah 9
        $query = self::filter(Event::published()->with('organisasi', 'skp', 'kategori'), $request);
        $total = (clone $query)->count();
        $events = $query->latest('tanggal_mulai')->take($tampil)->get();

        return view($request->user() && ! $request->user()->isMahasiswa() ? 'admin.jelajahi' : 'events.index', [
            'events' => $events,
            'total' => $total,
            'tampil' => $tampil,
            'organisasi' => Organisasi::orderByDesc('is_fakultas')->orderBy('id')->get(),
            'kategori' => Kategori::orderBy('id')->get(),
        ]);
    }

    /** Filter: q (nama), peran[], organisasi[], kategori[], dari, sampai. */
    public static function filter(Builder $q, Request $r): Builder
    {
        if ($r->filled('q')) {
            $kata = '%' . $r->q . '%';
            $q->where(fn ($w) => $w->where('judul', 'like', $kata)->orWhere('nama_kegiatan', 'like', $kata));
        }
        if ($r->filled('peran')) {
            $q->whereIn('peran', (array) $r->peran);
        }
        if ($r->filled('organisasi')) {
            $q->whereIn('organisasi_id', (array) $r->organisasi);
        }
        if ($r->filled('kategori')) {
            $q->whereIn('kategori_id', (array) $r->kategori);
        }
        // Rentang tanggal: event yang beririsan dengan rentang yang dipilih
        if ($r->filled('dari')) {
            $q->whereDate('tanggal_selesai', '>=', $r->dari);
        }
        if ($r->filled('sampai')) {
            $q->whereDate('tanggal_mulai', '<=', $r->sampai);
        }

        return $q;
    }

    public function show(Request $request, Event $event)
    {
        $user = $request->user();
        // Event belum tayang hanya boleh dilihat pengelolanya dan Admin FMIPA
        if ($event->status !== 'disetujui' && ! ($user && ($user->isFmipa() || $event->canBeManagedBy($user)))) {
            abort(404);
        }
        $event->load('organisasi', 'kategori', 'skp', 'timelines', 'syarat', 'divisi');
        $registration = $user ? $event->registrations()->where('user_id', $user->id)->first() : null;

        return view($user && ! $user->isMahasiswa() ? 'admin.event-detail' : 'events.show', compact('event', 'registration'));
    }

    public function faq()
    {
        return view('faq');
    }
}
