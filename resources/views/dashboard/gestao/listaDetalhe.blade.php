@extends('dashboard/layout')
    @section('conteudo')
    <div class="container-fluid">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
        </div>

        <div class="row">

            <div class="col-xl-6 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Lista: </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"> {{ $lista->titulo }} </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-calendar fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-6 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Associados (Cadastrados)</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"> {{ $totalNomes }} </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if(Auth::user()->tipo == 1)
        <div class="row">
            <div class="col-xl-12 col-md-12 mb-4">
                <div class="card border-left-dark shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-3">
                                    Relação de Parceiros
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="table-responsive">
                                    <table class="table table-striped" id="dataTable" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>Parceiro</th>
                                                <th>CPF/CNPJ</th>
                                                <th class="text-center">ASS. CPF</th>
                                                <th class="text-center">ASS. CNPJ</th>
                                                <th class="text-center">ASS. Total</th>
                                                <th class="text-center">Valor Final</th>
                                                <th class="text-center">Opções</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($usuarios as $key =>$usuario)
                                            <tr>
                                                <td>{{ $usuario['nome'] }}</td>
                                                <td>{{ $usuario['cpfcnpj'] }}</td>
                                                <td class="text-center">{{ $usuario['totalCpfs'] }}</td>
                                                <td class="text-center">{{ $usuario['totalCnpjs'] }}</td>
                                                <td class="text-center">{{ $usuario['totalNomes'] }}</td>
                                                <td class="text-center">{{ $usuario['totalNomes'] * Auth::user()->valor_limpa_nome }}</td>
                                                <td class="text-center">
                                                    <button class="btn btn-outline-success">Excel</button>
                                                    <button class="btn btn-outline-primary">ZIP</button>
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
        @endif

        <div class="row">
            <div class="col-xl-12 col-md-12 mb-4">
                <div class="card border-left-dark shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-3">
                                    Associados da Lista
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="table-responsive">
                                    <table class="table table-striped" id="dataTable" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>Cliente</th>
                                                <th>CPF/CNPJ</th>
                                                <th>Produto</th>
                                                <th>Lista</th>
                                                <th class="text-center">Opções</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($nomes as $key =>$nome)
                                            <tr>
                                                <td>{{ $nome->nome }}</td>
                                                <td>{{ $nome->cpfcnpj }}</td>
                                                <td>
                                                    @switch($nome->id_produto)
                                                        @case(1)
                                                            Limpa Nome
                                                            @break
                                                        @default
                                                            Produto Desconhecido
                                                    @endswitch
                                                </td>
                                                <td>{{ $nome->lista->titulo }}</td>
                                                <td class="text-center">
                                                    <form action="{{ route('excluiNome') }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="id" value="{{ $nome->id }}">
                                                        <button type="submit" class="btn btn-danger">Excluir</button>
                                                        <a class="btn btn-outline-info" href="@if($nome->ficha_associativa != null) {{ asset($nome->ficha_associativa) }} @else # @endif">Ficha</a>
                                                        <a class="btn btn-outline-primary" href="@if($nome->consulta != null) {{ asset($nome->consulta) }} @else # @endif">Consulta</a>
                                                    </form>
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
    @endsection
