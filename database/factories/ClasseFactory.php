<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Validation\Rules\Exists;

class ClasseFactory extends customFactory
{
    public function definition()
    {
        dump('Starting Classe seeding');
        $classe = new ClasseFactory();

        $classe->insertClasse();
        $classe->alunoClasse();
    }


    protected function insertClasse()
    {
        $datas = [];
        $disciplinas = \App\Models\Disciplina::all();
        foreach($disciplinas as $disciplina)
        {
            do{
                $professor = \App\Models\Professor::inRandomOrder()->first();
                if( $disciplina->areas->intersect( $professor->getAreas ) ) 
                {
                    $datas[] = [
                        'professor_id'=> $professor->id,
                        'disciplina_id'=> $disciplina->id
                    ];
                }    

            }while( end($datas)['disciplina_id'] != $disciplina->id );
            
        }
        $this->insertDatas('classes', $datas);
    }


    protected function alunoClasse()
    {
       $datasAlunoClasse = [];
       $datasAlunoDisciplina = [];
       
       $classes = \App\Models\Classe::all();

       foreach($classes as $classe)
       {
            $disciplina = $classe->disciplina;

            if( count($disciplina->anos)!=0 )
            {
                $alunos = $disciplina->anos[0]->alunos;
                foreach($alunos as $aluno)
                {
                    $datasAlunoClasse[] = [
                        'aluno_id'=> $aluno->id,
                        'classe_id'=> $classe->id
                    ];

                    $datasAlunoDisciplina[] = [
                        'aluno_id'=> $aluno->id,
                        'disciplina_id'=> $disciplina->id,
                        'situacao_id'=> 11
                    ];
                    
                }
            }
        }
        $this->insertDatas('aluno_classe', $datasAlunoClasse);
        $this->insertDatas('aluno_disciplina', $datasAlunoDisciplina);
    }
}
