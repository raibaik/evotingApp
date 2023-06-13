<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\ImportPemilih;

class Calons extends Model
{
    use HasFactory;

    protected $table = 'calons';
    protected $fillable = ['id_calon', 'nama_ketua', 'foto_calon', 'visi', 'misi', 'suara'];
    protected $primaryKey = 'id_calon';

    public function user()
    {
        return $this->belongsTo(User::class, 'id');
    }

    public function importpemilih()
    {
        return $this->belongsTo(ImportPemilih::class, 'IdPemilih');
    }
}
