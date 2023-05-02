<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Autor extends Model
{
    use HasFactory;
    public $timestamps = false;


    public function nacionalidade()
    {
        return $this->belongsTo(Nacionalidade::class, 'nacionalidade_id', 'id');
    }
}