@extends('dashboard.layout')
    @section('conteudo')
    <div class="container-fluid">

        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Extrato de Saques</h1>
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
                                <a class="dropdown-item" href="/wallet">Wallet</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    <button class="btn btn-outline-success" type="button" data-toggle="modal" data-target="#exampleModal">Filtros</button>

                                    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <form method="POST" action="{{ route('saqueExtrato') }}">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Filtros:</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <input type="hidden" value={{  csrf_token() }} name="_token">
                                                        <div class="row">
                                                            <div class="col-12">
                                                                <div class="form-group">
                                                                    <select class="form-control"  name="usuario">
                                                                        <option value="0">Todos os Usuários</option>
                                                                        @foreach ($usuarios as $key =>$usuario)
                                                                        <option value="{{ $usuario->id }}">{{ $usuario->nome }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-6">
                                                                <div class="form-group">
                                                                    <input type="date" class="form-control" name="data_inicio" placeholder="Data inicial">
                                                                </div>
                                                            </div>
                                                            <div class="col-6">
                                                                <div class="form-group">
                                                                    <input type="date" class="form-control" name="data_fim" placeholder="Data Final">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Fechar</button>
                                                        <button type="submit" class="btn btn-success">Filtrar</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <hr>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="table-responsive">
                                    <table class="table table-striped" id="dataTable" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th class="text-center">Valor</th>
                                                <th>Chave</th>
                                                <th>Data pedido</th>
                                                <th class="text-center">Data pagamento</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($saques as $key =>$saque)
                                            <tr>
                                                <td>{{ $saque->id }}</td>
                                                <td class="text-center">R$ {{ number_format($saque->valor, 2, ',', '.') }}</td>
                                                <td>{{ $saque->chave_pix }}</td>
                                                <td>{{ \Carbon\Carbon::parse($saque->created_at)->format('d/m/Y') }}</td>
                                                <td class="text-center"> {{ \Carbon\Carbon::parse($saque->updated_at)->format('d/m/Y') }} </td>
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
