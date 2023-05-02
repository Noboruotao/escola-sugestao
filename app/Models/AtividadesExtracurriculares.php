<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class AtividadesExtracurriculares extends Model
{
    use HasFactory;
    protected $table = 'atividade_extracurriculares';
    use SoftDeletes;

}
