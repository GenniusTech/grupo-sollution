<html>

<head>
    <meta content="text/html; charset=UTF-8" http-equiv="content-type">
    <style type="text/css">
        @import url(https://themes.googleusercontent.com/fonts/css?kit=fpjTOVmNbO4Lz34iLyptLUXza5VhXqVC6o75Eld_V98);
        .text-center {
            text-align: center;
        }

        .c9 {
            text-align: center
        }

        p {
            margin: 0;
            color: #000000;
            font-size: 13pt;
            font-family: "Calibri"
        }

        h1 {
            padding-top: 0pt;
            color: #000000;
            font-size: 14pt;
            font-family: "Calibri";
            line-height: 1.0791666666666666;
            orphans: 2;
            widows: 2;
            text-align: center
        }

        .c8 {
            text-align: justify;
            text-indent:4em
        }

    </style>
</head>

<body class="c2 doc-content">
    <p class="c4">
        <span>NOME: <?php echo $data['nome']; ?></span>
    </p>

    <p class="c3">
        <span class="c0">CPF/CNPJ: <?php echo $data['cpfcnpj']; ?></span>
    </p>

    <h1 class="c9">
        <span>FICHA DE INSCRIÇÃO ASSOCIATIVA / DECLARAÇÃO / AUTORIZAÇÃO</span>
    </h1>

    <p class="text-center c8">
        Por meio da presente, venho requerer a minha inscri&ccedil;&atilde;o como associado (a), desta associa&ccedil;&atilde;o. Ao assinar este instrumento, declaro estar ciente do inteiro teor do estatuto social da Associa&ccedil;&atilde;o, bem como dos direitos e deveres impostos aos membros desta institui&ccedil;&atilde;o. Declaro que consinto com a propositura de A&ccedil;&atilde;o de Obriga&ccedil;&atilde;o de Fazer com Pedido de Tutela de Urg&ecirc;ncia e Indeniza&ccedil;&atilde;o por Danos Morais, para defesa de direito difuso ou coletivo, em meu nome, movida por esta associa&ccedil;&atilde;o.
    </p>
    <p class="c5" style="margin-top: 30px;">
        <span>Assinatura</span>
        <hr style="border: 2px solid; width: 60%; margin-top: 5px;">
    </p>
    <div class="text-center" style="margin-top: 30px;">
        <img src="data:image/jpeg;base64,{{ $base64Image }}" style="width: 300px; height: 400px;">
    </div>
</body>

</html>
