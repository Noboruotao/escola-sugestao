<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aluno extends Model
{
    use HasFactory;
    public $timestamps = false;


    /**
     * acessar os dados de Pessoa com mesmo id
     */
    public function pessoa()
    {
        return $this->belongsTo(Pessoa::class, 'id', 'id');
    }


    public function ano()
    {
        return $this->belongsTo(Ano::class, 'ano_id', 'id');
    }


    public function situacao()
    {
        return $this->belongsTo(SituacaoAluno::class, 'situacao_id', 'id');
    }


    
}
