<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NivelEscolar extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'nivel_escolar';

    protected $fillable = [
        'nome'
    ];
}
