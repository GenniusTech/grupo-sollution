@extends('dashboard.layout')
    @section('conteudo')
    <div class="container-fluid">

        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Venda Direta</h1>
        </div>

        <div class="row">
            <div class="col-xl-12 col-md-12 mb-4">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-success">Lançamento de Venda Direta</h6>
                        <div class="dropdown no-arrow">
                            <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
                                <div class="dropdown-header">Opções:</div>
                                <a class="dropdown-item" href="#">Dúvidas</a>
                                <a class="dropdown-item" href="/vendas/1">Minhas Vendas</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                @if (isset($msgErro))
                                    <div class="alert alert-danger">
                                        <ul>
                                            <li>{{ $msgErro }}</li>
                                        </ul>
                                    </div>
                                @endif
                                @if (isset($msgSuccesso))
                                    <div class="alert alert-success">
                                        <ul>
                                            <li>{{ $msgSuccesso }}</li>
                                        </ul>
                                    </div>
                                @endif
                            </div>
                            <div id="pesquisa" class="col-12">
                                <div class="row">
                                    <div class="col-sm-12 col-lg-4">
                                        <div class="form-group">
                                            <input type="text" class="form-control" id="cpf" placeholder="CPF/CNPJ:">
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-lg-4">
                                        <div class="form-group">
                                            <input type="text" class="form-control" id="dataNascimento" placeholder="Data de Nascimento (opcional):">
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-lg-4">
                                        <div class="form-group">
                                            <button type="button" id="consultar" class="btn btn-success">Buscar dados</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div id="resultado" class="col-12">
                                <form method="POST" action="{{ route('action_vendaDireta') }}">
                                    <input type="hidden" value={{ csrf_token() }} name="_token">
                                    <input type="hidden" name="id_vendedor" value="{{ Auth::user()->id }}">
                                    <input type="hidden" name="produto" value="1">
                                    <input type="hidden" name="franquia" value="limpanome">
                                    <input type="hidden" name="valor" value="997">

                                    <div class="row">
                                        <div class="col-sm-12 col-lg-6">
                                            <div class="form-group">
                                                <input type="text" class="form-control" name="cliente" placeholder="Nome">
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-lg-3">
                                            <div class="form-group">
                                                <input type="text" class="form-control" name="cpf" placeholder="CPF">
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-lg-3">
                                            <div class="form-group">
                                                <input type="text" class="form-control" name="dataNascimento" placeholder="D. Nascimento/Abertura">
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-lg-6">
                                            <div class="form-group">
                                                <input type="email" class="form-control" name="email" placeholder="E-mail">
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-lg-3">
                                            <div class="form-group">
                                                <input type="text" class="form-control" name="whatsapp" placeholder="WhatsApp">
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-lg-3">
                                            <div class="form-group">
                                                <select name="assas" class="form-control">
                                                    <option value="false">Criar Link de Pagamento?</option>
                                                    <option value="true">Sim</option>
                                                    <option value="false">Não</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-lg-12 text-center">
                                            <button type="submit" class="btn btn-success w-50">Lançar Venda</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    @endsection
