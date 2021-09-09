<?php

namespace App\Http\Controllers;

use App\Http\Requests\UnidadeStoreRequest;
use App\Models\Medico;
use App\Models\Medico_servico;
use App\Models\Servico;
use App\Models\Unidade;
use App\Models\Unidade_medico;
use App\Models\Unidade_servico;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class UnidadeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $unidades = Unidade::all();
        return view('unidades.home', ['unidades' => $unidades]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!Gate::allows('admin')) {
            return redirect()->route('home')->with('aviso', ['msg' => 'Não autorizado!']);
        }
        $dados_servicos = Servico::all();
        return view('unidades.cadastrar', ['servicos' => $dados_servicos]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UnidadeStoreRequest $request)
    {
        if (!Gate::allows('admin')) {
            return redirect()->route('home')->with('aviso', ['msg' => 'Não autorizado!']);
        }
        $nova_unidade = Unidade::create(
            [
                'nome_unidade' => $request->nome,
                'endereco_unidade' => $request->endereco,
                'cidade_unidade' => $request->cidade,
                'estado_unidade' => $request->estado,
                'telefone_unidade' => $request->telefone,
                'cnpj_unidade' => $request->cnpj,
            ]

        );
        if ($request->servicos) {
            foreach ($request->servicos as $servico) {
                $nome_servico = Servico::where('id_servico', $servico)->select('tipo_servico')->first();

                Unidade_servico::create([
                    'id_servico' => $servico,
                    'id_unidade' => $nova_unidade->id,
                    'nome_servico' => $nome_servico->tipo_servico
                ]);
            }
        }
        $bg_notificacao = 'success';
        $msg = 'Unidade Cadastrada';
        $notify_title = 'Cadastro';
        $notify_subtitle = 'Unidade';
        $route = route('unidades');
        return  [
            'msg' => $msg,
            'bg_notificacao' => $bg_notificacao,
            'titulo_notificacao' => $notify_title,
            'subtitulo_notificacao' => $notify_subtitle,
            'route' => $route
        ];
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Unidade  $unidade
     * @return \Illuminate\Http\Response
     */
    public function show(
        Unidade $unidade,
        Medico $medico,
        Unidade_servico $id_servicos,
        Servico $servicos,
        $id
    ) {

        $dados_unidade = $unidade->where('id_unidade', $id)->first();
        $id_servicos = $id_servicos->where('id_unidade', $id)->select('id_servico')->get();
        $dados_servicos = $servicos->whereIn('id_servico', $id_servicos)->get();
        $dados_medicos = $medico->where('id_unidade', $id)->where('is_deleted', 0)->get();

        return view(
            'unidades.detalhar',
            [
                'unidade' => $dados_unidade,
                'medicos' => $dados_medicos,
                'servicos' => $dados_servicos
            ]
        );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Unidade  $unidade
     * @return \Illuminate\Http\Response
     */
    public function edit(
        Unidade $unidade,
        $id
    ) {
        if (!Gate::allows('admin')) {
            return redirect()->route('home')->with('aviso', ['msg' => 'Não autorizado!']);
        }
        $dados_unidade = $unidade->where('id_unidade', $id)->first();

        return view('unidades.alterar', [

            'unidade' => $dados_unidade

        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Unidade  $unidade
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Unidade $unidade)
    {
        if (!Gate::allows('admin')) {
            return redirect()->route('home')->with('aviso', ['msg' => 'Não autorizado!']);
        }
        $alterar_unidade = $unidade->where('id_unidade', $request->id_unidade)->update(
            [
                'nome_unidade' => $request->nome,
                'endereco_unidade' => $request->endereco,
                'cidade_unidade' => $request->cidade,
                'estado_unidade' => $request->estado,
                'telefone_unidade' => $request->telefone,
                'cnpj_unidade' => $request->cnpj
            ]
        );

        if ($alterar_unidade === 1) {
            $bg_notificacao = 'success';
            $msg = 'Unidade alterada';
            $notify_title = 'Alteração';
            $notify_subtitle = 'Unidade';
        }

        return  [
            'msg' => $msg,
            'bg_notificacao' => $bg_notificacao,
            'titulo_notificacao' => $notify_title,
            'subtitulo_notificacao' => $notify_subtitle
        ];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Unidade  $unidade
     * @return \Illuminate\Http\Response
     */
    public function destroy(Unidade $unidade)
    {
    }
    public function destroyServicoUnidade($id_servico, $id_unidade)
    {
        if (!Gate::allows('admin')) {
            return redirect()->route('home')->with('aviso', ['msg' => 'Não autorizado!']);
        }
        Unidade_servico::where('id_servico', $id_servico)->where('id_unidade', $id_unidade)->delete();
        $id_medicos = Medico::where('id_unidade', $id_unidade)->select('id_medico')->get();
        Medico_servico::whereIn('id_medico', $id_medicos)->where('id_servico', $id_servico)->delete();
        $bg_notificacao = 'success';
        $msg = 'Serviço removido';
        $notify_title = 'Remover';
        $notify_subtitle = 'Serviço';


        return redirect()->route('detalharUnidade', $id_unidade)->with('aviso', [
            'msg' => $msg,
            'bg_notificacao' => $bg_notificacao,
            'titulo_notificacao' => $notify_title,
            'subtitulo_notificacao' => $notify_subtitle
        ]);
    }
    public function destroyMedicoUnidade(Medico $medico, $id, $id_unidade)
    {

        if (!Gate::allows('admin')) {
            return redirect()->route('home')->with('aviso', ['msg' => 'Não autorizado']);
        }

        $deletar_medico = $medico->where('id_medico', $id)->update(['is_deleted' => 1]);
        Unidade_medico::where('id_medico', $id)->update(['is_deleted' => 1]);
        Medico_servico::where('id_medico', $id)->update(['is_deleted' => 1]);
        if ($deletar_medico === 1) {
            $msg = "Médico excluído com sucesso";
            $bg_notificacao = 'success';
        } else {
            $msg = "Erro ao excluír, tente novamente";
            $bg_notificacao = 'error';
        }
        $notify_title = 'Deletar';
        $notify_subtitle = 'Médico';

        return redirect()->route('detalharUnidade', [$id_unidade])->with('aviso', [
            'msg' => $msg,
            'bg_notificacao' => $bg_notificacao,
            'titulo_notificacao' => $notify_title,
            'subtitulo_notificacao' => $notify_subtitle
        ]);
    }
}
