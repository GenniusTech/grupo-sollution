@extends('dashboard.layout')
    @section('conteudo')
    <div class="container-fluid">

        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Documentos de Apoio</h1>
        </div>

        <div class="row">
            <div class="col-xl-12 col-md-12 mb-4">
                <div class="card border-left-dark shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="table-responsive">
                                    <table class="table table-striped" id="dataTable" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Nome</th>
                                                <th>Descrição</th>
                                                <th class="text-center">Arquivo/Url</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($materiais as $key =>$material)
                                            <tr>
                                                <td>{{ $material->id }}</td>
                                                <td>{{ $material->nome }}</td>
                                                <td>{{ $material->descricao }}</td>
                                                <td class="text-center">
                                                    @if (Str::startsWith($material->arquivo, '/storage/'))
                                                        <a class="btn btn-outline-success" href="{{ asset($material->arquivo) }}" download>Baixar</a>
                                                    @else
                                                        <a class="btn btn-outline-success" href="{{ $material->arquivo }}" target="_blank">Acessar</a>
                                                    @endif
                                                </td>
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
        </div>

    </div>

    @endsection
