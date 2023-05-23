<?php

namespace Database\Factories;

use App\Models\Emprestimo;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;
use App\Models\Pessoa;
use App\Models\Aluno;
use App\Models\Acervo;

class EmprestimoFactory extends customFactory
{
    public function definition()
    {
        dump('Starting Emprestimo seeding');
        $emprestimos = new EmprestimoFactory();
        
        $emprestimos->makeEmprestimos();
        $emprestimos->attributeAcervoAreasToAluno();
        $emprestimos->insertMultas();

    }


    protected function makeEmprestimos()
    {
        echo "    start makeEmprestimos()". PHP_EOL;
        $datas = [];

        $pessoas = Aluno::whereBetween('ano_id', [6, 17])->get()->merge(\App\Models\Professor::all());
        $bibliotecarios = Pessoa::getPessoaByRole('Bibliotecário');
        $acervos = \App\Models\Acervo::all();

        foreach($pessoas as $pessoa)
        {
            $numero_de_emprestimos = $this->faker->numberBetween($min = 0, $max = 20);
            do
            {
                $data_de_emprestimo = $this->faker->dateTimeBetween($startDate = '-3 years', $endDate = '-1 week', $timezone = null);
                $devolucao_date = clone $data_de_emprestimo;

                $datas[] = [
                    'acervo_id'=> $acervos->random()->id,
                    'bibliotecario_id'=> $this->faker->randomElement($bibliotecarios)->id,
                    'leitor_id'=> $pessoa->id,
                    'data_de_emprestimo'=> $data_de_emprestimo,
                    'data_de_devolucao'=> $this->faker->dateTimeBetween($data_de_emprestimo, $devolucao_date->modify('+20 days')),
                    'created_at'=> now(),
                    'updated_at'=> now()
                ];

                $numero_de_emprestimos--;
            }while($numero_de_emprestimos>0);
            $this->insertDatasMidway('emprestimos', $datas);
             
        }
        $this->insertDatas('emprestimos', $datas);
    }


    protected function getAreasFromAcervos($acervos)
    {
        $areas = collect();
        foreach($acervos as $acervo)
        {
            $areas = $areas->merge(($acervo->areas)->diff($areas));
        }
        return $areas;
    }


    protected function attributeAcervoAreasToAluno()
    {
        echo "    start attributeAcervoAreasToAluno()". PHP_EOL;

        $todos_emprestimos = Emprestimo::whereIn('leitor_id', Aluno::pluck('id')->toArray())->get();

        foreach($todos_emprestimos as $emprestimo)
        {
            $leitor = aluno::find($emprestimo->leitor->id);
            $acervo = $emprestimo->acervo;

            Emprestimo::updateAlunoAreas($leitor, $acervo);
        }
    }


    protected function insertMultas()
    {
        echo "    start insertMultas()". PHP_EOL;

        $emprestimos_atrasados = Emprestimo::whereRaw('DATEDIFF(data_de_devolucao, data_de_emprestimo) > 14')
                                    ->get();

        
        $datas = [];

        foreach($emprestimos_atrasados as $emprestimo)
        {
            $data_de_emprestimo = Carbon::parse($emprestimo->data_de_emprestimo);
            $data_de_devolucao = Carbon::parse($emprestimo->data_de_devolucao);

            $dias_atrasados = $data_de_devolucao->diffInDays($data_de_emprestimo);

            $datas[] = [
                'emprestimo_id'=> $emprestimo->id,
                'dias_atrasados'=> $dias_atrasados,
                'valor_da_multa'=> ($dias_atrasados-14) * $emprestimo->acervo->tipo->multa,
                'pago'=> $data_de_devolucao
            ];
            $this->insertDatasMidway('multas', $datas);

        }
        $this->insertDatas('multas', $datas);
    }
}
