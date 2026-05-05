<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Category;
use App\Models\TicketLog;

class Ticket extends Model
{
    /**
     * Kolom yang boleh diisi secara mass assignment saat membuat atau mengupdate tiket.
     */
    protected $fillable = [
        'ticket_no',
        'user_id',
        'category_id',
        'title',
        'description',
        'status'
    ];

    /**
     * Relasi ke user pembuat tiket.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke kategori masalah tiket, misalnya Hardware, Software, atau Network.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Relasi ke seluruh riwayat perubahan status tiket.
     */
    public function logs()
    {
        return $this->hasMany(TicketLog::class);
    }

    /**
     * Mengambil log terbaru dari tiket untuk ditampilkan sebagai status/aktivitas terakhir.
     */
    public function latestLog()
    {
        return $this->hasOne(TicketLog::class)->latestOfMany();
    }
}
