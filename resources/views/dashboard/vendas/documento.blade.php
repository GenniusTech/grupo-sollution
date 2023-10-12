@extends('dashboard.layout')
@section('conteudo')
    <div class="container-fluid">

        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Upload de Documentos para: {{ $nome->nome }}</h1>
        </div>

        <div class="row">
            <div class="col-xl-12 col-md-12 mb-4">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-success">Upload de Documentos: </h6>
                    </div>
                    <div class="card-body">
                        @if (strlen($nome->cpfcnpj) < 12)
                            <form action="{{ route('cadastraDocumento') }}" method="POST" enctype="multipart/form-data">
                                <input type="hidden" name="id" value="{{ $nome->id }}">
                                @csrf
                                <div class="row">
                                    <div class="col-sm-12 col-lg-6 offset-lg-3">
                                        <div class="form-group">
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" id="ficha_associativa"
                                                    name="ficha_associativa" required>
                                                <label class="custom-file-label" for="ficha_associativa">Ficha Associativa
                                                    (Assinada)</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-lg-6 offset-lg-3">
                                        <div class="form-group">
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" id="consulta"
                                                    name="consulta" required>
                                                <label class="custom-file-label" for="consulta">Consulta</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-lg-6 offset-lg-3">
                                        <div class="form-group d-flex text-center">
                                            <form action="">
                                                <a href="{{ asset($nome->ficha_associativa) }}"
                                                    class="btn btn-outline-primary m-1 w-50" download>Baixar Ficha</a>
                                                <a href="#" class="btn btn-outline-info m-1 w-50" onclick="geraConsulta()">Gerar
                                                    Consulta</a>
                                                <button type="submit" class="btn btn-success m-1 w-50">Enviar</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        @else
                            <form action="{{ route('cadastraDocumento') }}" method="POST" enctype="multipart/form-data">
                                <input type="hidden" name="id" value="{{ $nome->id }}">
                                @csrf
                                <div class="row">
                                    <div class="col-sm-12 col-lg-6 offset-lg-3">
                                        <div class="form-group">
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" id="ficha_associativa"
                                                    name="ficha_associativa" required>
                                                <label class="custom-file-label" for="ficha_associativa">Ficha Associativa
                                                    (Assinada)</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-lg-6 offset-lg-3">
                                        <div class="form-group">
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" id="cartao_cnpj"
                                                    name="cartao_cnpj" required>
                                                <label class="custom-file-label" for="cartao_cnpj">Cartão CNPJ</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-lg-6 offset-lg-3">
                                        <div class="form-group">
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" id="consulta"
                                                    name="consulta" required>
                                                <label class="custom-file-label" for="consulta">Consulta</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-lg-6 offset-lg-3">
                                        <div class="form-group d-flex text-center">
                                            <form action="">
                                                <a href="{{ asset($nome->ficha_associativa) }}"
                                                    class="btn btn-outline-primary m-1 w-25" download>Gerar Ficha</a>
                                                <a href="#" class="btn btn-outline-info m-1 w-25" onclick="geraConsulta()">Gerar
                                                    Consulta</a>
                                                <a href="https://solucoes.receita.fazenda.gov.br/servicos/cnpjreva/cnpjreva_solicitacao.asp"
                                                    class="btn btn-outline-warning m-1 w-25" target="_blank">Gerar Cart
                                                    CNPJ</a>
                                                <button type="submit" class="btn btn-success m-1 w-25">Enviar</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script>
        function geraConsulta() {
            let cnpfcnpj = {{$nome->cpfcnpj}};
            if(cnpfcnpj.length > 12) {
                let url = 'https://consulta.gruposollution.com.br/painel/dashboard/consulta/consulta_2/final.php?lista=' + {{ $nome->cpfcnpj }} + '|NVdOaUFFRkZyZlNEUE1uSnk2VUtiZEhSd0R3OVgzVXRDN2ZTPUJ3dS01aXFvP2dTUWwyd2VJNnE9RFVUaVJHM3JSWVoyODI4YUpicG5pVU0yVlNlcHNDPzlWMEZLZ090WGZyYXpONGhuZVpvdTBEeXk4ZjZJSVpPWUsvUkE4alk5ajM3eFliOFZGcHpNNjRxbVJRMzRVa3QyVnlMYUhoSC1jTE82UFAxV1MySmE9ZGMwdVkxP0tsZnpxNDdVRnljQjlJS1kxQnpla3UxSjYzYnpzR3FHT3ZXanV5QWgvNWxWbWluVzVhWFEhdGdneURWbGUvTXdXV0JhaXJrNlQ1ag==|cGhzbG9mYzpKb3JnZTAxMDEu';
                window.open(url, '_blank');
            } else {
                let url = 'https://consulta.gruposollution.com.br/painel/dashboard/consulta/consulta_5/final.php?lista=' + {{ $nome->cpfcnpj }} + '|NVdOaUFFRkZyZlNEUE1uSnk2VUtiZEhSd0R3OVgzVXRDN2ZTPUJ3dS01aXFvP2dTUWwyd2VJNnE9RFVUaVJHM3JSWVoyODI4YUpicG5pVU0yVlNlcHNDPzlWMEZLZ090WGZyYXpONGhuZVpvdTBEeXk4ZjZJSVpPWUsvUkE4alk5ajM3eFliOFZGcHpNNjRxbVJRMzRVa3QyVnlMYUhoSC1jTE82UFAxV1MySmE9ZGMwdVkxP0tsZnpxNDdVRnljQjlJS1kxQnpla3UxSjYzYnpzR3FHT3ZXanV5QWgvNWxWbWluVzVhWFEhdGdneURWbGUvTXdXV0JhaXJrNlQ1ag==|cGhzbG9mYzpKb3JnZTAxMDEu';
                window.open(url, '_blank');
            }
        }
    </script>

@endsection
