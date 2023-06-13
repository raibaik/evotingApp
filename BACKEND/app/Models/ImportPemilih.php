<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Status;
use App\Models\periode;

class ImportPemilih extends Model
{
    protected $table = 'importpemilih';
    protected $primaryKey = 'IdPemilih';
    public $timestamps = true;
    protected $fillable = [
        'nama',
        'IdAngkatan',
        'IdStatus',
        'deskripsi',
    ];

    public function angkatan()
    {
        return $this->belongsTo(Angkatan::class, 'IdAngkatan');
    }

    public function status()
    {
        return $this->belongsTo(Status::class, 'IdStatus');
    }
}
