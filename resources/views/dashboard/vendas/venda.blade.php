@extends('dashboard.layout')
@section('conteudo')
    <div class="container-fluid">

        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Cadastro de CPF/CNPJ</h1>
        </div>

        <div class="row">
            <div class="col-xl-12 col-md-12 mb-4">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-success">Cadastro de CPF/CNPJ na lista: </h6>
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
                            <div id="pesquisa" class="col-12">
                                <div class="row">
                                    <div class="col-sm-12 col-lg-4">
                                        <div class="form-group">
                                            <input type="number" class="form-control" id="cpf"
                                                placeholder="CPF/CNPJ:">
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-lg-4">
                                        <div class="form-group">
                                            <button type="button" id="consultar" class="btn btn-success">Buscar
                                                dados</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div id="resultado" class="col-12 d-none">
                                <form action="{{ route('cadastraNome') }}" method="POST" enctype="multipart/form-data">
                                    <input type="hidden" value={{ csrf_token() }} name="_token">
                                    <input type="hidden" name="id_vendedor" value="{{ Auth::user()->id }}">
                                    <input type="hidden" name="valor" value="{{ Auth::user()->valor_limpa_nome }}">
                                    <input type="hidden" name="id_produto" value="1">

                                    <div class="row">
                                        <div class="col-sm-12 col-lg-4">
                                            <div class="form-group">
                                                <input type="text" class="form-control" name="nome"
                                                    placeholder="Nome">
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-lg-4">
                                            <div class="form-group">
                                                <input type="number" class="form-control" name="cpfcnpj"
                                                    placeholder="CPF/CNPJ">
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-lg-4">
                                            <div class="form-group">
                                                <input type="text" class="form-control" name="dataNascimento"
                                                    placeholder="D. Nascimento/Abertura">
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-lg-4">
                                            <div class="form-group">
                                                <input type="email" class="form-control" name="email"
                                                    placeholder="E-mail">
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-lg-4">
                                            <div class="form-group">
                                                <input type="text" class="form-control" name="whatsapp"
                                                    placeholder="WhatsApp">
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-lg-4">
                                            <div class="form-group">
                                                <div class="custom-file">
                                                    <input type="file" class="custom-file-input" id="documento_com_foto"
                                                        name="documento_com_foto">
                                                    <label class="custom-file-label" for="documento_com_foto">Documento com
                                                        foto (RG ou CNH)</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-lg-4 offset-lg-4 text-center">
                                            <button type="submit" class="btn btn-success w-50">Cadastrar Nome</button>
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

    <script>
        $('#consultar').click(function() {
            var cpfCnpj = $('#cpf').val();
            $('#resultado').addClass('d-none');

            Swal.fire({
                title: "Atenção!",
                text: "Aguarde enquanto buscamos os dados!",
                timer: 5000,
                timerProgressBar: true,
                icon: "info",
                showConfirmButton: false
            });

            $.ajax({
                url: '{{ route('consultar') }}',
                type: "POST",
                data: {
                    cpfCnpj: cpfCnpj,
                },
                success: function(data) {
                    $('#resultado').removeClass('d-none');
                    console.log(cpfCnpj.length);
                    if(cpfCnpj.length < 12) {
                        var dataCompleta = data.NASC;
                        var dataPartes = dataCompleta.split(' ');
                        var dataNasc = dataPartes[0];

                        var dataFormatada = new Date(dataNasc);
                        var dia = dataFormatada.getDate();
                        var mes = dataFormatada.getMonth() + 1;
                        var ano = dataFormatada.getFullYear();
                        var dataFormatadaString = dia + '-' + mes + '-' + ano;

                        $('input[name=nome]').val(data.NOME);
                        $('input[name=cpfcnpj]').val(data.CPF);
                        $('input[name=dataNascimento]').val(dataFormatadaString);
                        $('input[name=whatsapp]').val(data.CONTATOS_ID);
                    } else {
                        $('input[name=nome]').val(data.razao_social);
                        $('input[name=cpfcnpj]').val(data.cnpj);
                        $('input[name=dataNascimento]').val(data.data_inicio_ativ);
                        $('input[name=whatsapp]').val(data.telefone_1);
                    }

                },
                error: function(xhr, status, error) {
                    console.log(data);
                }
            });
        });
    </script>
@endsection
