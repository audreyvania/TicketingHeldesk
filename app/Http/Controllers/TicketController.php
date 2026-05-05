<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Ticket;
use App\Models\TicketLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class TicketController extends Controller
{
    /**
     * Urutan status tiket yang boleh dilakukan oleh IT.
     * Alur ini mencegah status dilompat secara bebas, misalnya Open langsung Closed.
     */
    private const STATUS_FLOW = [
        'Open' => 'On Progress',
        'On Progress' => 'Resolved',
        'Resolved' => 'Closed',
    ];

    /**
     * Memastikan semua method di controller ini hanya bisa diakses oleh user yang sudah login.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    // ================= USER =================

    /**
     * Menentukan dashboard yang ditampilkan berdasarkan role user.
     * Role IT diarahkan ke dashboard admin, sedangkan role user ke dashboard pribadi.
     */
    public function dashboard()
    {
        if (Auth::user()->role === 'it') {
            return $this->adminDashboard();
        }

        return $this->userDashboard();
    }

    /**
     * Menampilkan dashboard user berisi tiket miliknya sendiri dan jumlah tiket per status.
     */
    public function userDashboard()
    {
        $tickets = Ticket::with(['category', 'latestLog.user'])
            ->where('user_id', '=', Auth::id())
            ->latest('updated_at')
            ->get();

        $open = Ticket::query()->where('user_id', '=', Auth::id())->where('status', '=', 'Open')->count();
        $progress = Ticket::query()->where('user_id', '=', Auth::id())->where('status', '=', 'On Progress')->count();
        $resolved = Ticket::query()->where('user_id', '=', Auth::id())->where('status', '=', 'Resolved')->count();
        $closed = Ticket::query()->where('user_id', '=', Auth::id())->where('status', '=', 'Closed')->count();

        return response()
            ->view('dashboard', compact('tickets', 'open', 'progress', 'resolved', 'closed'))
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
    }

    /**
     * Menampilkan form pembuatan tiket beserta pilihan kategori masalah.
     */
    public function create()
    {
        $categories = Category::all();
        return view('tickets.create', compact('categories'));
    }

    /**
     * Menampilkan semua tiket milik user yang sedang login.
     */
    public function myTickets()
    {
        $tickets = Ticket::with(['category', 'latestLog.user'])
            ->where('user_id', '=', Auth::id())
            ->latest('updated_at')
            ->get();

        return response()
            ->view('tickets.my', compact('tickets'))
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
    }

    /**
     * Menyimpan tiket baru dari user.
     * Setelah tiket dibuat, sistem juga membuat log awal agar riwayat tiket tercatat sejak status Open.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'category_id' => 'required|exists:categories,id',
            'description' => 'required'
        ]);

        $ticket = Ticket::create([
            'ticket_no' => 'TCK-' . time(),
            'user_id' => $request->user()->id,
            'category_id' => $request->category_id,
            'title' => $request->title,
            'description' => $request->description,
            'status' => 'Open'
        ]);

        TicketLog::create([
            'ticket_id' => $ticket->id,
            'user_id' => $request->user()->id,
            'status' => 'Open',
            'note' => 'Ticket created'
        ]);

        return redirect()->route('tickets.my')->with('success', 'Ticket created successfully');
    }

    // ================= IT =================

    /**
     * Menampilkan dashboard IT berisi ringkasan jumlah tiket dan daftar tiket terbaru.
     */
    public function adminDashboard()
    {
        $tickets = Ticket::with(['user', 'category'])
            ->latest()
            ->limit(8)
            ->get();

        $open = Ticket::query()->where('status', '=', 'Open')->count();
        $progress = Ticket::query()->where('status', '=', 'On Progress')->count();
        $resolved = Ticket::query()->where('status', '=', 'Resolved')->count();
        $closed = Ticket::query()->where('status', '=', 'Closed')->count();

        return view('admin.dashboard', compact('tickets', 'open', 'progress', 'resolved', 'closed'));
    }

    /**
     * Menampilkan daftar semua tiket untuk IT.
     * Data dapat difilter berdasarkan status, kategori, dan tanggal pembuatan.
     */
    public function index(Request $request)
    {
        $request->validate([
            'status' => 'nullable|in:Open,On Progress,Resolved,Closed',
            'category' => 'nullable|exists:categories,id',
            'date' => 'nullable|date',
        ]);

        $tickets = Ticket::with(['user', 'category'])
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('status', $request->status);
            })
            ->when($request->filled('category'), function ($query) use ($request) {
                $query->where('category_id', $request->category);
            })
            ->when($request->filled('date'), function ($query) use ($request) {
                $query->whereDate('created_at', $request->date);
            })
            ->latest()
            ->get();
        $categories = Category::all();

        return view('tickets.index', compact('tickets', 'categories'));
    }

    /**
     * Menampilkan detail tiket beserta riwayat log-nya.
     * User biasa hanya boleh melihat tiket miliknya sendiri, sedangkan IT boleh melihat semua tiket.
     */
    public function show(int $id)
    {
        $ticket = Ticket::with(['user', 'category', 'logs'])
            ->findOrFail($id);

        if (Auth::user()->role !== 'it' && $ticket->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $logs = $ticket->logs()
            ->with('user')
            ->latest()
            ->get();
        $nextStatus = self::STATUS_FLOW[$ticket->status] ?? null;
        $backUrl = Auth::user()->role === 'it'
            ? route('admin.dashboard')
            : route('dashboard');

        return view('tickets.show', compact('ticket', 'logs', 'nextStatus', 'backUrl'));
    }

    /**
     * Mengubah status tiket oleh IT sesuai alur STATUS_FLOW.
     * Setiap perubahan status disimpan ke ticket_logs bersama catatan dari IT.
     */
    public function updateStatus(Request $request, int $id)
    {
        $ticket = Ticket::findOrFail($id);
        $nextStatus = self::STATUS_FLOW[$ticket->status] ?? null;

        if (! $nextStatus) {
            return back()->withErrors([
                'status' => 'This ticket is already Closed and cannot be updated anymore.',
            ]);
        }

        $request->validate([
            'status' => ['required', Rule::in([$nextStatus])],
            'note' => ['required', 'string', 'max:1000'],
        ]);

        $ticket->update([
            'status' => $request->status
        ]);

        TicketLog::create([
            'ticket_id' => $ticket->id,
            'user_id' => $request->user()->id,
            'status' => $request->status,
            'note' => $request->note
        ]);

        return back()->with('success', 'Status updated');
    }
}
