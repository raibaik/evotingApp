<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voting extends Model
{
    use HasFactory;

    protected $table = 'voting';
    protected $primaryKey = 'IdVoting';
    public $timestamps = false;

    protected $fillable = [
        'IdUser',
        'IdKandidat',
        'IdPemilih',
        'WaktuVote',
    ];

    // Relasi dengan model User
    public function user()
    {
        return $this->belongsTo(User::class, 'IdUser');
    }

    // Relasi dengan model Kandidat
    public function calons()
    {
        return $this->belongsTo(Calons::class, 'IdCalon');
    }

    // Relasi dengan model Pemilihan
    public function importpemilih()
    {
        return $this->belongsTo(ImportPemilih::class, 'IdPemilih');
    }
}
