@extends('dashboard.layout')
@section('conteudo')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.2/xlsx.full.min.js"></script>

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

        @if (Auth::user()->tipo == 1)
            <div class="row">
                <div class="col-xl-12 col-md-12 mb-4">
                    <div class="card border-left-dark shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-3">
                                        Relação de Parceiros

                                        <form action="{{ route('geraListaZip') }}" method="POST" class="mt-3">
                                            @csrf
                                            <input type="hidden" name="lista" value="{{ $lista->id }}">
                                            <button type="button" onclick="geraListaExcel(this);" data-lista="{{ $lista->id }}" class="btn btn-outline-success">Excel Geral</button>
                                            <button type="submit" class="btn btn-outline-primary">ZIP Geral</button>
                                        </form>
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
                                                @foreach ($usuarios as $key => $usuario)
                                                    <tr>
                                                        <td>{{ $usuario['nome'] }}</td>
                                                        <td>{{ $usuario['cpfcnpj'] }}</td>
                                                        <td class="text-center">{{ $usuario['totalCpfs'] }}</td>
                                                        <td class="text-center">{{ $usuario['totalCnpjs'] }}</td>
                                                        <td class="text-center">{{ $usuario['totalNomes'] }}</td>
                                                        <td class="text-center"> {{ $usuario['totalFinal'] }} </td>
                                                        <td class="text-center">
                                                            <form action="{{ route('geraListaZip') }}" method="POST">
                                                                @csrf
                                                                <input type="hidden" name="usuario" value="{{ $usuario['id'] }}">
                                                                <input type="hidden" name="lista" value="{{ $lista->id }}">
                                                                <button type="button" onclick="geraListaExcel(this);" data-usuario="{{ $usuario['id'] }}" data-lista="{{ $lista->id }}" class="btn btn-outline-success">Excel</button>
                                                                <button type="submit" class="btn btn-outline-primary">ZIP</button>
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
                                                <th class="text-center">Ficha</th>
                                                <th class="text-center">Doc. Foto</th>
                                                <th class="text-center">Cart. CNPJ</th>
                                                <th class="text-center">Consulta</th>
                                                <th class="text-center">Opções</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($nomes as $key => $nome)
                                                <tr>
                                                    <td>{{ $nome->nome }}</td>
                                                    <td>{{ $nome->cpfcnpj }}</td>
                                                    <td class="text-center">@if ($nome->ficha_associativa != null)  <a target="_blank" class="btn btn-outline-info"    href="{{ asset($nome->ficha_associativa) }}">Ficha</a> @else Não Enviado @endif</td>
                                                    <td class="text-center">@if ($nome->documento_com_foto != null) <a target="_blank" class="btn btn-outline-success" href="{{ asset($nome->documento_com_foto) }}">Doc. Foto</a> @else Não Enviado @endif</td>
                                                    <td class="text-center">@if ($nome->cartao_cnpj != null)        <a target="_blank" class="btn btn-outline-primary" href="{{ asset($nome->cartao_cnpj) }}">Cart. CNPJ</a>@else Não Enviado @endif</td>
                                                    <td class="text-center">@if ($nome->consulta != null)           <a target="_blank" class="btn btn-outline-primary" href="{{ asset($nome->consulta) }}">Consulta</a>@else Não Enviado @endif</td>
                                                    <td class="text-center">
                                                        <form action="{{ route('excluiNome') }}" method="POST">
                                                            @csrf
                                                            <input type="hidden" name="id" value="{{ $nome->id }}">
                                                            <button type="submit" class="btn btn-danger">Excluir</button>
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

        <script>
            function geraListaExcel(button) {
                var usuario = button.getAttribute('data-usuario');
                var lista = button.getAttribute('data-lista');

                $.ajax({
                    url: '{{ route('geraListaExcel') }}',
                    type: "POST",
                    data: {
                        usuario: usuario,
                        lista: lista,
                    },
                    success: function(data) {
                        geraExcel(data);
                    },
                    error: function(xhr, status, error) {
                        Swal.fire({
                            title: "Erro!",
                            text: "Falha ao gerar documento!",
                            timer: 5000,
                            timerProgressBar: true,
                            icon: "info",
                            showConfirmButton: false
                        });
                    }
                });
            }

            function geraExcel(data) {
                var wb = XLSX.utils.book_new();
                var ws = XLSX.utils.json_to_sheet(data);

                ws['A1'] = {
                    v: 'Nome',
                    t: 's'
                };
                ws['B1'] = {
                    v: 'CPF/CNPJ',
                    t: 's'
                };
                ws['C1'] = {
                    v: 'Email',
                    t: 's'
                };
                ws['D1'] = {
                    v: 'Telefone',
                    t: 's'
                };
                ws['E1'] = {
                    v: 'Valor',
                    t: 's'
                };
                ws['F1'] = {
                    v: 'Cadastro',
                    t: 's'
                };

                XLSX.utils.book_append_sheet(wb, ws, 'Dados');
                var wbout = XLSX.write(wb, {
                    bookType: 'xlsx',
                    type: 'binary'
                });
                var blob = new Blob([s2ab(wbout)], {
                    type: 'application/octet-stream'
                });
                var url = URL.createObjectURL(blob);
                var a = document.createElement('a');

                a.href = url;
                a.download = '{{ $lista->titulo }}.xlsx';
                a.style.display = 'none';
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);

                URL.revokeObjectURL(url);
            }

            function s2ab(s) {
                var buf = new ArrayBuffer(s.length);
                var view = new Uint8Array(buf);
                for (var i = 0; i < s.length; i++) {
                    view[i] = s.charCodeAt(i) & 0xFF;
                }
                return buf;
            }
        </script>
    @endsection
