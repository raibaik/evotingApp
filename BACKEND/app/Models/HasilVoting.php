<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Calons;

class HasilVoting extends Model
{
    use HasFactory;

    protected $table = 'hasilvotings';
    protected $primaryKey = 'IdHasilVoting';

    protected $fillable = [
        'id_calon',
        'total_suara',
    ];

    public function calon()
    {
        return $this->belongsTo(Calons::class, 'id_calon'); // Perbaikan pada pemanggilan model Calons
    }
}