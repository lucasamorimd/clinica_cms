@extends('adminlte::page')
@section('title')
{{$unidade->nome_unidade}}
@endsection
@section('content_header')
<h1> Informações de Unidade</h1>
@endsection
@section('content')
<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card card-profile ">
            <div class="card-header">
                <h2 class="text-center">
                    {{$unidade->nome_unidade}}
                </h2>
            </div>
            <div class="card-body">

                <div class="row">
                    <div class="col">
                        <div class="card">
                            <h4 class="card-header text-center">Médicos da Unidade</h4>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table text-center table-hover table-striped">
                                        <caption>Lista de Médicos dessa Unidade</caption>
                                        <thead>
                                            <tr>
                                                <th scope="col">Nome</th>
                                                <th scope="col">Área de atuação</th>
                                                <th scope="col">CRM</th>
                                                @can('admin')
                                                <th scope="col">Editar</th>
                                                @endcan
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($medicos as $medico)
                                            <tr>
                                                <th scope="row">
                                                    <a href="{{route('detalharMedico',$medico->id_medico)}}">
                                                        {{$medico->nome_medico}}
                                                    </a>
                                                </th>
                                                <td>{{$medico->area_atuacao}}</td>
                                                <td>{{$medico->crm}}</td>
                                                @can('admin')
                                                <td>
                                                    <a href="{{route('excluirMedicoUnidade',[$medico->id_medico,$unidade->id_unidade])}}" class="btn btn-danger">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
                                                </td>
                                                @endcan
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card">
                            <h4 class="card-header text-center">Servicos da Unidade</h4>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table text-center table-hover table-striped">
                                        <caption>Lista de Serviços dessa Unidade</caption>
                                        <thead>
                                            <tr>
                                                <th scope="col">Nome</th>
                                                <th scope="col">Tipo</th>
                                                <th scope="col">Preço</th>
                                                @can('admin')
                                                <th scope="col">Excluir</th>
                                                @endcan
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($servicos as $servico)
                                            <tr>
                                                <th scope="row">
                                                    <a href="{{route('detalharServico',[$servico->id_servico])}}">
                                                        {{$servico->nome_servico}}
                                                    </a>
                                                </th>
                                                <td>{{$servico->tipo_servico}}</td>
                                                <td>R$ {{number_format($servico->preco_servico, 2, ',', ' ')}}</td>
                                                @can('admin')
                                                <td>
                                                    <a href="{{route('excluirServicoUnidade',[$servico->id_servico,$unidade->id_unidade])}}" class="btn btn-danger">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
                                                </td>
                                                @endcan
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="card-footer text-muted text-center">
                @can('admin')
                <a href="{{route('alterarUnidade',$unidade->id_unidade)}}" class="btn btn-primary">Alterar</a>
                @endcan
            </div>
        </div>
    </div>
</div>
@endsection
@if(session('aviso'))
@section('js')
@include('components.toast')
@endsection
@endif