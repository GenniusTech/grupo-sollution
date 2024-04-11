@extends('dashboard.layout')
@section('conteudo')
    <div class="container-fluid">

        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Gestão de Mensagem</h1>
        </div>

        <div class="row">
            <div class="col-xl-12 col-md-12 mb-4">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-success">Gestão de Mensagem: </h6>
                    </div>
                    <div class="card-body">
                        <div class="row">

                            <div class="col-12">
                                @if (session('error'))
                                    <div class="alert alert-danger">
                                        {{ session('error') }}
                                    </div>
                                @endif
                                @if (session('success'))
                                    <div class="alert alert-success">
                                        {{ session('success') }}
                                    </div>
                                @endif
                            </div>

                            <div id="resultado" class="col-12">
                                <form action="{{ route('cadastrar-message') }}" method="POST">
                                    @csrf

                                    <div class="row">
                                        <div class="col-sm-12 col-lg-12">
                                            <div class="form-group">
                                                <input type="text" class="form-control" name="titulo" placeholder="Titulo:" required>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-lg-12">
                                            <div class="form-group">
                                                <textarea name="message" class="form-control" cols="30" rows="10">Mensagem:</textarea>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-lg-4 offset-lg-4 text-center">
                                            <button type="submit" class="btn btn-success w-50">Gerar Mensagem</button>
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
