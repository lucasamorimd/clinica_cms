@extends('adminlte::page')
@section('title')
{{$unidade->nome_unidade}}
@endsection
@section('content_header')

<h1>Informações de Unidade</h1>
@endsection
@section('content')

<div class="card">
    <div class="card-header">
        <h4 class="text-center">Informações {{$unidade->nome_unidade}}</h4>
    </div>
    <div class="card-body">
        <div class="row  justify-content-center">
            <div class="col">
                <form id="alterar" action="{{route('salvarAlteracaoUnidade')}}" method="POST">
                    @csrf

                    <div class="form-group row">
                        <label for="nome" class="col-sm-2 col-form-label">Nome</label>
                        <div class="col-sm-10">
                            <input name="nome" id="nome" type="text" class="form-control " value="{{$unidade->nome_unidade}}">
                            @error('nome')
                            <div class="alert alert-danger">
                                {{$message}}
                            </div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="telefone" class="col-sm-2 col-form-label">Telefone</label>
                        <div class="col-sm-10">
                            <input name="telefone" id="telefone" type="text" class="form-control " value="{{$unidade->telefone_unidade}}">
                            @error('telefone')
                            <div class="alert alert-danger">
                                {{$message}}
                            </div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="endereco" class="col-sm-2 col-form-label">Endereço</label>
                        <div class="col-sm-10">
                            <input name="endereco" id="endereco" type="text" class="form-control " value="{{$unidade->endereco_unidade}}">

                            @error('endereco')
                            <div class="alert alert-danger">
                                {{$message}}
                            </div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="cidade" class="col-sm-2 col-form-label">Cidade</label>
                        <div class="col-sm-10">
                            <input name="cidade" id="cidade" type="text" class="form-control " value="{{$unidade->cidade_unidade}}">
                            @error('cidade')
                            <div class="alert alert-danger">
                                {{$message}}
                            </div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="estado" class="col-sm-2 col-form-label">Estado</label>
                        <div class="col-sm-10">
                            <input name="estado" id="estado" type="text" class="form-control " value="{{$unidade->estado_unidade}}">
                            @error('estado')
                            <div class="alert alert-danger">
                                {{$message}}
                            </div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="cnpj" class="col-sm-2 col-form-label">CNPJ</label>
                        <div class="col-sm-10">
                            <input name="cnpj" id="cnpj" type="text" class="form-control " value="{{$unidade->cnpj_unidade}}">
                            @error('cnpj')
                            <div class="alert alert-danger">
                                {{$message}}
                            </div>
                            @enderror
                        </div>
                    </div>
                    <input type="hidden" name="id_unidade" value="{{$unidade->id_unidade}}">
                </form>
            </div>
        </div>
        @include('components.button_alterar')
    </div>
</div>

@endsection
@section('js')
@include('components.alterar_ajax')
@endsection
@if(session('aviso'))
@section('js')
@include('components.toast')
@endsection
@endif