@extends('dashboard.layout')
    @section('conteudo')
    <div class="container-fluid">

        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Minhas Vendas</h1>
        </div>

        <div class="row">
            <div class="col-xl-12 col-md-12 mb-4">
                <div class="card border-left-dark shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    <button class="btn btn-outline-success" type="button" data-toggle="modal" data-target="#exampleModal">Cadastrar</button>

                                    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <form method="POST" action="{{ route('usuario') }}">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Cadastro de Parceiro</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <input type="hidden" value={{  csrf_token() }} name="_token">
                                                        <div class="row">
                                                            <div class="col-6">
                                                                <div class="form-group">
                                                                    <input type="text" class="form-control" name="nome" placeholder="Nome">
                                                                </div>
                                                            </div>
                                                            <div class="col-6">
                                                                <div class="form-group">
                                                                    <input type="number" class="form-control" name="cpf" placeholder="CPF">
                                                                </div>
                                                            </div>
                                                            <div class="col-6">
                                                                <div class="form-group">
                                                                    <input type="email" class="form-control" name="email" placeholder="Email">
                                                                </div>
                                                            </div>
                                                            <div class="col-6">
                                                                <div class="form-group">
                                                                    <input type="password" class="form-control" name="password" placeholder="Senha">
                                                                </div>
                                                            </div>
                                                            <div class="col-12">
                                                                <div class="form-group">
                                                                    <select class="form-control"  name="tipo">
                                                                        <option value="1">Tipo</option>
                                                                        @if (Auth::user()->tipo == 1) <option value="3">Administrador</option> @endif
                                                                        <option value="3">Influencer</option>
                                                                        <option value="2">Afiliado</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-6">
                                                                <div class="form-group">
                                                                    <input type="number" class="form-control" name="comissao" placeholder="Comissão Máx: {{ Auth::user()->comissao }}">
                                                                </div>
                                                            </div>
                                                            <div class="col-6">
                                                                <div class="form-group">
                                                                    <input type="text" class="form-control" name="chave_pix" placeholder="Informe uma chave pix">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Fechar</button>
                                                        <button type="submit" class="btn btn-success">Cadastrar</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <hr>
                                </div>
                            </div>
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
                                    <table class="table table-striped" id="dataTable" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Nome</th>
                                                <th class="text-center">Comissão</th>
                                                @if(Auth::user()->tipo == 1) <th class="text-center">Saldo</th> @endif
                                                <th class="text-center">Data Cadastro</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($usuarios as $key =>$usuario)
                                            <tr>
                                                <td>{{ $usuario->id }}</td>
                                                <td>{{ $usuario->nome }}</td>
                                                <td class="text-center">{{ number_format($usuario->comissao, 2, ',', '.') }}</td>
                                                @if(Auth::user()->tipo == 1) <td class="text-center">{{ number_format($usuario->saldo, 2, ',', '.') }}</td> @endif
                                                <td class="text-center">{{ \Carbon\Carbon::parse($usuario->created_at)->format('d/m/Y') }}</td>
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
