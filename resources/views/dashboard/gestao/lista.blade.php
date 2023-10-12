@extends('dashboard.layout')
    @section('conteudo')
    <div class="container-fluid">

        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Listas</h1>
        </div>

        <div class="row">
            <div class="col-xl-12 col-md-12 mb-4">
                <div class="card border-left-dark shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    @if(Auth::user()->tipo == 1) <button class="btn btn-outline-success mb-3" type="button" data-toggle="modal" data-target="#exampleModal">Cadastrar</button> @endif

                                    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="modalLista" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <form method="POST" action="{{ route('cadastraLista') }}">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="modalLista">Cadastro de Listas</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <input type="hidden" value={{  csrf_token() }} name="_token">
                                                        <div class="row">
                                                            <div class="col-12">
                                                                <div class="form-group">
                                                                    <input type="text" class="form-control" name="titulo" placeholder="Título">
                                                                </div>
                                                            </div>
                                                            <div class="col-12">
                                                                <div class="form-group">
                                                                    <input type="text" class="form-control" name="descricao" placeholder="Descrição">
                                                                </div>
                                                            </div>
                                                            <div class="col-6">
                                                                <div class="form-group">
                                                                    <input type="date" class="form-control" name="inicio">
                                                                </div>
                                                            </div>
                                                            <div class="col-6">
                                                                <div class="form-group">
                                                                    <input type="date" class="form-control" name="fim">
                                                                </div>
                                                            </div>
                                                            <div class="col-12">
                                                                <div class="form-group">
                                                                    <select class="form-control" name="status">
                                                                        <option>Status</option>
                                                                        <option value="1">Aberta</option>
                                                                        <option value="2">Fechada</option>
                                                                    </select>
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
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="table-responsive">
                                    <table class="table table-striped" id="dataTable" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>Título</th>
                                                <th>Descrição</th>
                                                <th>Status</th>
                                                <th>Início</th>
                                                <th>Fim</th>
                                                <th class="text-center">Opções</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($listas as $key =>$lista)
                                            <tr>
                                                <td>{{ $lista->titulo }}</td>
                                                <td>{{ $lista->descricao }}</td>
                                                <td>@if($lista->status == 1) Aberta @else Fechada @endif</td>
                                                <td>{{ date('d/m/Y', strtotime($lista->inicio)) }}</td>
                                                <td>{{ date('d/m/Y', strtotime($lista->fim)) }}</td>
                                                <td class="text-center">
                                                    <form action="{{ route('excluiLista') }}" method="POST">
                                                        <input type="hidden" value={{  csrf_token() }} name="_token">
                                                        <input type="hidden" value="{{ $lista->id }}" name="id">
                                                        @if(Auth::user()->tipo == 1)
                                                            <button class="btn btn-outline-danger" type="submit"><i class="fa fa-trash"></i></button>
                                                            <button class="btn btn-outline-warning" type="button" data-toggle="modal" data-target="#modalLista{{ $lista->id }}"><i class="fa fa-pencil"></i></button>
                                                        @endif
                                                        <a class="btn btn-outline-success" href="{{ route('listaDetalhe', ['id' => $lista->id]) }}"><i class="fa fa-eye"></i></a>
                                                    </form>
                                                </td>
                                            </tr>
                                            <div class="modal fade" id="modalLista{{ $lista->id }}" tabindex="-1" role="dialog" aria-labelledby="modalLista" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <form method="POST" action="{{ route('atualizaLista') }}">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="modalLista">Atualização de Listas</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <input type="hidden" value={{  csrf_token() }} name="_token">
                                                                <input type="hidden" value="{{ $lista->id }}" name="id">
                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        <div class="form-group">
                                                                            <input type="text" class="form-control" name="titulo" placeholder="Título" value="{{ $lista->titulo }}">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-12">
                                                                        <div class="form-group">
                                                                            <input type="text" class="form-control" name="descricao" placeholder="Descrição" value="{{ $lista->descricao }}">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-6">
                                                                        <div class="form-group">
                                                                            <input type="date" class="form-control" name="inicio" value="{{ $lista->inicio }}">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-6">
                                                                        <div class="form-group">
                                                                            <input type="date" class="form-control" name="fim" value="{{ $lista->fim }}">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-12">
                                                                        <div class="form-group">
                                                                            <select class="form-control" name="status">
                                                                                <option>Status</option>
                                                                                <option value="1">Aberta</option>
                                                                                <option value="2">Fechada</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-danger" data-dismiss="modal">Fechar</button>
                                                                <button type="submit" class="btn btn-success">Atualizar</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
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
