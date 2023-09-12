@extends('dashboard.layout')
    @section('conteudo')
    <div class="container-fluid">

        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Carteira</h1>
        </div>

        <div class="row">
            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Carteira (Total em Vendas)</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">R$ {{ number_format($vendaSUM, 2, ',', '.') }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-fw fa-folder fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Saques (Pedidos de saque)</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">R$ {{ number_format($saquePendente, 2, ',', '.') }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Saques (Dépositos realizados)</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">R$ {{ number_format($saqueAtendido, 2, ',', '.') }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-12 col-md-12 mb-4">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-success">Solicitações de Saque</h6>
                        <div class="dropdown no-arrow">
                            <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
                                <div class="dropdown-header">Opções:</div>
                                <a class="dropdown-item" id="exportar">Gerar Excel</a>
                                <a class="dropdown-item" href="/saqueExtrato">Extrato de Saques</a>
                                <a class="dropdown-item" href="#">Confirmar Pagamento de todos</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                                @if (session('success'))
                                    <div class="alert alert-success">
                                        {{ session('success') }}
                                    </div>
                                @endif
                                <div class="table-responsive">
                                    <table class="table table-striped" id="tabela" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th class="text-center">Valor</th>
                                                <th>Chave</th>
                                                <th>Data pedido</th>
                                                <th>Opção</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($saques as $key =>$saque)
                                            <tr>
                                                <td>{{ $saque->id }}</td>
                                                <td class="text-center">R$ {{ number_format($saque->valor, 2, ',', '.') }}</td>
                                                <td>{{ $saque->chave_pix }}</td>
                                                <td>{{ \Carbon\Carbon::parse($saque->created_at)->format('d/m/Y') }}</td>
                                                <td>
                                                    <form action="{{ route('confirmaPagamento') }}" method="POST">
                                                        <input type="hidden" value={{  csrf_token() }} name="_token">
                                                        <input type="hidden" name="id" value="{{ $saque->id }}">
                                                        <button type="submit" class="btn btn-outline-success">Confirmar Pagamento</button>
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

    </div>

    @endsection
