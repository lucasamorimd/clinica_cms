<?php

namespace App\Http\Controllers;

use App\Models\Agendamento;
use App\Models\Medico;
use App\Models\Prontuario;
use App\Models\Servico;
use App\Models\Unidade;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AgendamentoController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('can:admin-func');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($situacaoInicial)
    {

        $situacao = substr($situacaoInicial, 0, -1);
        $titulo = $situacaoInicial;
        if ($situacaoInicial == 'aguardando-resultado') {
            $situacao = $situacaoInicial;
            $titulo_divi = explode('-', $situacaoInicial);
            $titulo = ucfirst($titulo_divi[0]) . ' ' . $titulo_divi[1];
        }
        $agendamentos = Agendamento::where('situacao', $situacao)->get();
        if (Auth::user()->tipo_perfil === 'funcionario') {
            $agendamentos = Agendamento::where('situacao', $situacao)->where('id_unidade', Auth::user()->id_unidade)->get();
        }
        return view('agendamentos.home', ['agendamentos' => $agendamentos, 'url' => $situacaoInicial, 'titulo' => $titulo]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Agendamento  $agendamento
     * @return \Illuminate\Http\Response
     */
    public function show(
        Agendamento $agendamento,
        Usuario $usuario,
        Unidade $unidade,
        Servico $servico,
        Medico $medico,
        Prontuario $prontuario,
        $situacao,
        $id
    ) {
        $dados_agendamento = $agendamento->where('id_agendamento', $id)->first();
        $dados_usuario = $usuario->where('id_usuario', $dados_agendamento->id_usuario)->first();
        $dados_unidade = $unidade->where('id_unidade', $dados_agendamento->id_unidade)->first();
        $dados_servico = $servico->where('id_servico', $dados_agendamento->id_servico)->first();
        $dados_medico = $medico->where('id_medico', $dados_agendamento->id_medico)->first();
        $dados_prontuario = $prontuario->where('id_prontuario', $dados_agendamento->id_prontuario);
        return view('agendamentos.detalhar', [
            'agendamento' => $dados_agendamento,
            'usuario' => $dados_usuario,
            'unidade' => $dados_unidade,
            'servico' => $dados_servico,
            'medico' => $dados_medico,
            'situacao' => $situacao,
            'prontuario' => $dados_prontuario
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Agendamento  $agendamento
     * @return \Illuminate\Http\Response
     */
    public function edit(Agendamento $agendamento, $id)
    {
        $agendamento->where('id_agendamento', $id)->update([
            'situacao' => 'aguardando-resultado'
        ]);
        $dados_agendamento = $agendamento->where('id_agendamento', $id)->first();
        $dados_usuario = Usuario::where('id_usuario', $dados_agendamento->id_usuario)->first();
        $dados_unidade = Unidade::where('id_unidade', $dados_agendamento->id_unidade)->first();
        $dados_servico = Servico::where('id_servico', $dados_agendamento->id_servico)->first();
        $dados_medico = Medico::where('id_medico', $dados_agendamento->id_medico)->first();
        return view('agendamentos.atenderPendente', [
            'agendamento' => $dados_agendamento,
            'usuario' => $dados_usuario,
            'unidade' => $dados_unidade,
            'servico' => $dados_servico,
            'medico' => $dados_medico,

        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Agendamento  $agendamento
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Agendamento $agendamento)
    {
        $validar_arquivo = Validator::make($request->all(), [
            'resumo' => ['required'],
            'arquivo_prontuario' => ['required', 'file', 'mimes:pdf']
        ], [
            
            'resumo.required' => 'Digitar algum resumo do prontuário do paciente',
            'arquivo_prontuario.required' => 'Insira um arquivo PDF',
            'arquivo_prontuario.file' => 'Arquivo inválido',
            'arquivo_prontuario.mimes' => 'Arquivo inserido não é pdf'
        ]);
        
        if ($validar_arquivo->fails()) {
            return redirect()->route('atenderPendente', $request->id_agendamento)->withErrors($validar_arquivo);
        }

        $nome_arquivo = md5($request->id_agendamento . $request->nome_paciente) . '.pdf';

        $novo_prontuario = Prontuario::create([
            'resumo' => $request->resumo,
            'nome_arquivo' => $nome_arquivo,

        ]);

        $notify_title = 'Atendimento';
        $notify_subtitle = 'Funcionário';

        if ($novo_prontuario) {
            $agendamento->where('id_agendamento', $request->id_agendamento)->update([
                'id_prontuario' => $novo_prontuario->id,
                'situacao' => 'realizado'
            ]);
        }
        $request->arquivo_prontuario->storeAs('public/prontuarios', $nome_arquivo);
        $bg_notificacao = 'success';
        $msg = 'Atendimento Finalizado';
        $notify_title = $request->nome_servico;
        $notify_subtitle = ucfirst($request->tipo_servico);
        $route = route('agendamentos', 'realizados');
        return  [
            'msg' => $msg,
            'bg_notificacao' => $bg_notificacao,
            'titulo_notificacao' => $notify_title,
            'subtitulo_notificacao' => $notify_subtitle,
            'route' => $route
        ];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Agendamento  $agendamento
     * @return \Illuminate\Http\Response
     */
    public function destroy(Agendamento $agendamento)
    {
        //
    }
}
