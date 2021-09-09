<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\ServicoStoreRequest;
use App\Http\Requests\ServicoUpdateStoreRequest;
use App\Models\Medico;
use App\Models\Medico_servico;
use App\Models\Servico;
use App\Models\Unidade;
use App\Models\Unidade_servico;
use App\Models\Galeria;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class ServicoController extends Controller
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
        if (Auth::user()->tipo_perfil === 'administrador') {

            $dados_servicos = Servico::all();
        } else {
            $id_servicos = Unidade_servico::where('id_unidade', Auth::user()->id_unidade)
                ->select('id_servico')
                ->get();
            $dados_servicos = Servico::whereIn('id_servico', $id_servicos)->get();
        }
        return view('servicos.home', ['servicos' => $dados_servicos]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!Gate::allows('admin')) {
            return redirect()->route('home')->with('aviso', ['msg' => 'Não autorizado']);
        }
        $unidades = Unidade::all();
        return view('servicos.cadastrar', ['unidades' => $unidades]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ServicoStoreRequest $request)
    {
        if (!Gate::allows('admin')) {
            return redirect()->route('home')->with('aviso', ['msg' => 'Não autorizado']);
        }
        $novo_servico = Servico::create([
            'nome_servico' => $request->nome,
            'tipo_servico' => strtolower($request->tipo_servico),
            'tempo_estimado' => $request->tempo_estimado,
            'preco_servico' => $request->preco,
            'descricao_servico' => $request->descricao,
            'foto_principal' => 'teste'
        ]);
        foreach ($request->fotos_servico as $foto) {
            $nome_arquivo = md5($novo_servico->id . '_' . date('H:i:s') . rand(0, 999)) . '.' . $foto->extension();
            Galeria::create(
                [
                    'id_servico' => $novo_servico->id,
                    'nome_foto' => $nome_arquivo
                ]
            );
            $foto->storeAs('public/servicos/' . strtolower($request->tipo_servico) . '/' . $request->nome, $nome_arquivo);
        }
        $nome_foto = md5(date('H:i:s') . rand(1, 99) . 'principal') . '.' . $request->foto_principal->extension();
        $request->foto_principal->storeAs('public/servicos/' . strtolower($request->tipo_servico) . '/' . $request->nome, $nome_foto);
        Servico::where('id_servico', $novo_servico->id)->update(['foto_principal' => $nome_foto]);
        if ($request->unidades) {
            foreach ($request->unidades as $unidades) {
                Unidade_servico::create([
                    'id_servico' => $novo_servico->id,
                    'id_unidade' => $unidades,
                    'nome_servico' => strtolower($request->tipo_servico)
                ]);
            }
        }
        $notify_title = 'Cadastrar';
        $notify_subtitle = 'Serviço';
        if ($novo_servico) {
            $msg = 'Serviço Cadastrado!';
            $bg_notificacao = 'bg-primary';
        } else {
            $msg = 'Erro no cadastro';
            $bg_notificacao = 'bg-danger';
        }

        $bg_notificacao = 'success';
        $msg = 'Serviço Cadastrado';
        $notify_title = 'Cadastro';
        $notify_subtitle = 'Serviços';
        $route = route('servicos');
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
     * @param  \App\Models\Servico  $servico
     * @return \Illuminate\Http\Response
     */
    public function show(Servico $servico, $id)
    {
        $dados_servico = $servico->where('id_servico', $id)->first();
        return view('servicos.detalhar', ['servico' => $dados_servico]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Servico  $servico
     * @return \Illuminate\Http\Response
     */
    public function edit(Servico $servico, $id)
    {

        if (!Gate::allows('admin')) {
            return redirect()->route('home')->with('aviso', ['msg' => 'Não autorizado']);
        }
        //Dados do serviço solicitado para alterar
        $dados_servico = $servico->where('id_servico', $id)->first();

        //ID DAS UNIDADES QUE TEM ESSE SERVIÇO
        $id_unidades = Unidade_servico::where('id_servico', $id)->select('id_unidade')->get();

        //ID DOS MÉDICOS QUE ATENDEM ESSE SERVIÇO
        $id_medicos = Medico_servico::where('id_servico', $id)->where('is_deleted', 0)->select('id_medico')->get();

        //DADOS DAS UNIDADES QUE TEM O SERVIÇO
        $dados_servico_unidades = Unidade::whereIn('id_unidade', $id_unidades)->get();

        //DADOS DOS MÉDICOS QUE ATENDEM ESSE SERVIÇO NAS UNIDADES
        $dados_servico_medicos = Medico::whereIn('id_medico', $id_medicos)
            ->whereIn('id_unidade', $id_unidades)
            ->where('is_deleted', 0)
            ->get();
        //DADOS GERAIS DAS UNIDADES CADASTRADAS NO SISTEMA
        $dados_gerais_unidades = Unidade::all();

        //DADOS GERAIS DOS MÉDICOS CADASTRADOS NO SISTEMA
        $dados_gerais_medicos = Medico::where('is_deleted', 0)->get();


        //COLLECTIONS PARA COMPARAR AS UNIDADES QUE TEM O SERVIÇO E QUE NÃO TEM
        $collection_unidades = collect($dados_servico_unidades);
        $collection_gerais_unidades = collect($dados_gerais_unidades);

        $unidades_disponiveis = $collection_gerais_unidades->diff($collection_unidades);

        //COLLECTIONS PARA COMPARAR OS MÉDICOS QUE ATENDEM E NÃO ATENDEM O SERVIÇO
        $collection_medicos = collect($dados_servico_medicos);
        $collection_gerais_medicos = collect($dados_gerais_medicos);

        $medicos_disponiveis = $collection_gerais_medicos->diff($collection_medicos);
        return view('servicos.alterar', [
            'servico' => $dados_servico,
            'unidades' => $dados_servico_unidades,
            'medicos' => $dados_servico_medicos,
            'unidades_disp' => $unidades_disponiveis,
            'medicos_disp' => $medicos_disponiveis
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Servico  $servico
     * @return \Illuminate\Http\Response
     */
    public function update(ServicoUpdateStoreRequest $request, Servico $servico)
    {
        if (!Gate::allows('admin')) {
            return redirect()->route('home')->with('aviso', ['msg' => 'Não autorizado']);
        }
        $update_servico = $servico->where('id_servico', $request->id_servico)->update([
            'nome_servico' => $request->nome,
            'tipo_servico' => $request->tipo_servico,
            'tempo_estimado' => $request->tempo_estimado,
            'preco_servico' => $request->preco,
            'descricao_servico' => $request->descricao
        ]);

        if ($update_servico === 1) {
            Unidade_servico::where('id_servico', $request->id_servico)->delete();
            Medico_servico::where('id_servico', $request->id_servico)->where('is_deleted', 0)->delete();
            if ($request->unidades) {
                foreach ($request->unidades as $unidade) {
                    Unidade_servico::create([
                        'id_unidade' => $unidade,
                        'id_servico' => $request->id_servico,
                        'nome_servico' => $request->tipo_servico
                    ]);
                }
            }
            if ($request->medicos) {
                foreach ($request->medicos as $medico) {
                    $id_medico_unidade = Medico::where('id_medico', $medico)->select('id_unidade')->first();
                    if ($request->unidades && in_array($id_medico_unidade->id_unidade, $request->unidades)) {
                        Medico_servico::create([
                            'id_medico' => $medico,
                            'id_servico' => $request->id_servico,
                            'nome_servico' => $request->tipo_servico
                        ]);
                    }
                }
            }
        }

        $bg_notificacao = 'success';
        $msg = 'Serviço alterado';
        $notify_title = 'Alteração';
        $notify_subtitle = 'Serviço';

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
     * @param  \App\Models\Servico  $servico
     * @return \Illuminate\Http\Response
     */
    public function destroy(Servico $servico)
    {
        //
    }
}
