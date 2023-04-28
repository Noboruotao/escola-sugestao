<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateDisciplinasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        Schema::create('disciplinas', function (Blueprint $table) {
            $table->id();
            $table->string('nome')->unique();
            $table->integer('carga_horaria');
            $table->timestamps();
        });

                
        Schema::create('situacao_da_disciplina', function (Blueprint $table){
            $table->id();
            $table->string('nome');
        });


        Schema::create('alunos_Disciplinas', function (Blueprint $table){
            $table->foreignId('aluno_id')
                    ->constrained('alunos')
                    ->onDelete('cascade');
            $table->foreignId('disciplina_id')
                    ->constrained('disciplinas')
                    ->onDelete('cascade');
            $table->foreignId('situacao_id')
                    ->constrained('situacao_da_disciplina');
            $table->float('nota_final')
                    ->default(null)
                    ->nullable();
        });


        Schema::create('disciplinas_professores', function (Blueprint $table){
            $table->foreignId('professor_id')
                    ->constrained('professores')
                    ->onDelete('cascade');
            $table->foreignId('disciplina_id')
                    ->constrained('disciplinas')
                    ->onDelete('cascade');
        });


        Schema::create('tipos_de_avaliacoes', function (Blueprint $table){
            $table->id();
            $table->string('nome');
        });


        Schema::create('notas', function (Blueprint $table){
            $table->id();
            $table->foreignId('aluno->id')
                    ->constrained('alunos');
            $table->foreignId('tipo_de_avaliacao_id')
                    ->constrained('tipos_de_avaliacoes');
            $table->foreignId('disciplina_id')
                    ->constrained('disciplinas');
            $table->float('nota')
            ->onDelete('cascade');
            $table->integer('peso_da_nota');
        });



        Schema::create('materiais_sugeridos', function (Blueprint $table){
            $table->foreignId('disciplina_id')
                    ->constrained('disciplinas')
                    ->onDelete('cascade');
            $table->foreignId('acervo_id')
                    ->constrained('acervos')
                    ->onDelete('cascade');
        });


        Schema::create('anos_disciplinas', function (Blueprint $table){
                $table->foreignId('ano_id')
                        ->constrained('alunos')
                        ->onDelete('cascade');
                $table->foreignId('disciplina_id')
                        ->constrained('disciplinas')
                        ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('disciplinas');
        Schema::dropIfExists('alunos_Disciplinas');
        Schema::dropIfExists('situacao_da_disciplina');
        Schema::dropIfExists('disciplinas_professores');
        Schema::dropIfExists('notas');
        Schema::dropIfExists('tipos_de_avaliacoes');
        Schema::dropIfExists('materiais_recomendados');
        Schema::dropIfExists('ano_disciplinas');
        
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
