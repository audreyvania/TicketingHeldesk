<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketLog extends Model
{
    /**
     * Kolom yang boleh diisi saat sistem mencatat riwayat status tiket.
     */
    protected $fillable = [
        'ticket_id',
        'user_id',
        'status',
        'note',
    ];

    /**
     * Relasi ke tiket yang sedang dicatat riwayatnya.
     */
    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    /**
     * Relasi ke user yang membuat log, bisa user pembuat tiket atau IT yang mengubah status.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
