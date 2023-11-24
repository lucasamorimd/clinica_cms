<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAllTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id('user_id');
            $table->string('name');
            $table->string('email')->unique();
            $table->integer('is_deleted')->default(0);
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id('id_usuario');
            $table->string('nome');
            $table->string('rg');
            $table->string('data_nascimento');
            $table->string('telefone');
            $table->string('endereco');
            $table->string('email');
            $table->string('cep');
            $table->string('cidade');
            $table->string('estado');
            $table->string('login');
            $table->string('senha');
            $table->string('password');
            $table->string('sexo');
            $table->string('token');
            $table->integer('status')->default(0);
            $table->integer('is_deleted')->default(0);
            $table->string('foto')->nullable()->default(null);
            $table->timestamps();
        });
        Schema::create('unidades', function (Blueprint $table) {
            $table->id('id_unidade');
            $table->string('nome_unidade', 100);
            $table->string('endereco_unidade', 100);
            $table->string('cidade_unidade', 100);
            $table->string('estado_unidade', 100);
            $table->string('telefone_unidade', 100);
            $table->string('cnpj_unidade', 100);
            $table->timestamps();
        });
        Schema::create('funcionarios', function (Blueprint $table) {
            $table->id();
            $table->string('nome_funcionario');
            $table->string('email');
            $table->string('password');
            $table->string('tipo_perfil')->default('funcionario');
            $table->rememberToken();
            $table->integer('is_deleted')->default(0);
            $table->unsignedBigInteger('id_unidade');
            $table->foreign('id_unidade')->references('id_unidade')->on('unidades')->onDelete('cascade');
            $table->timestamps();
        });
        Schema::create('medicos', function (Blueprint $table) {
            $table->id('id_medico');
            $table->unsignedBigInteger('id_unidade');
            $table->string('crm', 100);
            $table->string('nome_medico', 100);
            $table->string('foto_medico', 100)->nullable();
            $table->string('area_atuacao', 100);
            $table->datetime('data_cadastro')->useCurrent();
            $table->timestamps();
            $table->integer('is_deleted')->default(0);
            $table->foreign('id_unidade')->references('id_unidade')->on('unidades')->onDelete('cascade');
        });
        Schema::create('servicos', function (Blueprint $table) {
            $table->id('id_servico');
            $table->string('nome_servico', 100);
            $table->string('tipo_servico');
            $table->string('foto_principal', 200);
            $table->string('tempo_estimado', 100);
            $table->decimal('preco_servico', $precision = 8, $scale = 2);
            $table->text('descricao_servico');
            $table->timestamps();
        });

        Schema::create('galerias', function (Blueprint $table) {
            $table->id('id_foto');
            $table->unsignedBigInteger('id_servico');
            $table->string('nome_foto', 100);
            $table->foreign('id_servico')->references('id_servico')->on('servicos')->onDelete('cascade');
        });
        Schema::create('agendamentos', function (Blueprint $table) {
            $table->id('id_agendamento');
            $table->unsignedBigInteger('id_usuario');
            $table->unsignedBigInteger('id_unidade');
            $table->unsignedBigInteger('id_servico');
            $table->unsignedBigInteger('id_medico');
            $table->string('nome_paciente', 100);
            $table->string('email_paciente', 100);
            $table->string('telefone_paciente', 100);
            $table->string('tipo_atendimento', 100);
            $table->string('nome_atendimento', 100);
            $table->date('data_atendimento');
            $table->time('hora_atendimento');
            $table->dateTime('data_abertura')->useCurrent();
            $table->decimal('preco_servico', $precision = 8, $scale = 2);
            $table->string('situacao', 100)->default('pendente');
            $table->timestamps();
            $table->integer('id_prontuario')->nullable();
            $table->foreign('id_usuario')
                ->references('id_usuario')
                ->on('usuarios')
                ->onDelete('cascade');
            $table->foreign('id_unidade')
                ->references('id_unidade')
                ->on('unidades')
                ->onDelete('cascade');
            $table->foreign('id_servico')
                ->references('id_servico')
                ->on('servicos')
                ->onDelete('cascade');
            $table->foreign('id_medico')
                ->references('id_medico')
                ->on('medicos')
                ->onDelete('cascade');
        });

        Schema::create('medico_servicos', function (Blueprint $table) {
            $table->id('id_med_serv');
            $table->unsignedBigInteger('id_medico');
            $table->unsignedBigInteger('id_servico');
            $table->string('nome_servico', 100);
            $table->integer('is_deleted')->default(0);
            $table->foreign('id_servico')
                ->references('id_servico')
                ->on('servicos')
                ->onDelete('cascade');
            $table->foreign('id_medico')
                ->references('id_medico')
                ->on('medicos')
                ->onDelete('cascade');
        });
        Schema::create('prontuarios', function (Blueprint $table) {
            $table->id('id_prontuario');
            $table->text('resumo');
            $table->string('nome_arquivo', 200);
            $table->timestamps();
        });

        Schema::create('unidade_medicos', function (Blueprint $table) {
            $table->id('id_uni_med');
            $table->unsignedBigInteger('id_unidade');
            $table->unsignedBigInteger('id_medico');
            $table->integer('is_deleted')->default(0);
            $table->foreign('id_unidade')
                ->references('id_unidade')
                ->on('unidades')
                ->onDelete('cascade');
            $table->foreign('id_medico')
                ->references('id_medico')
                ->on('medicos')
                ->onDelete('cascade');
        });
        Schema::create('unidade_servicos', function (Blueprint $table) {
            $table->id('id_us');
            $table->unsignedBigInteger('id_unidade');
            $table->unsignedBigInteger('id_servico');
            $table->string('nome_servico');
            $table->foreign('id_unidade')
                ->references('id_unidade')
                ->on('unidades')
                ->onDelete('cascade');
            $table->foreign('id_servico')
                ->references('id_servico')
                ->on('servicos')
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
        Schema::dropIfExists('usuarios');
        Schema::dropIfExists('agendamentos');
        Schema::dropIfExists('funcionarios');
        Schema::dropIfExists('galerias');
        Schema::dropIfExists('medicos');
        Schema::dropIfExists('medicos_servicos');
        Schema::dropIfExists('prontuarios');
        Schema::dropIfExists('servicos');
        Schema::dropIfExists('unidades');
        Schema::dropIfExists('unidade_medicos');
        Schema::dropIfExists('unidade_servicos');
    }
}
