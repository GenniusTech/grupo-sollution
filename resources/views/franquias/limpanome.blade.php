<!DOCTYPE html>
<html lang="pt-BR">

    <head>
        <meta charset="utf-8">
        <meta content="width=device-width, initial-scale=1.0" name="viewport">

        <title>Limpe seu nome - Grupo Sollution</title>

        <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Raleway:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

        <link href="{{ asset('landingPage//vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
        <link href="{{ asset('landingPage//vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
        <link href="{{ asset('landingPage//vendor/boxicons/css/boxicons.min.css') }}" rel="stylesheet">
        <link href="{{ asset('landingPage//vendor/remixicon/remixicon.css') }}" rel="stylesheet">
        <link href="{{ asset('landingPage//vendor/swiper/swiper-bundle.min.css') }}" rel="stylesheet">
        <link href="{{ asset('landingPage//css/style.css') }}" rel="stylesheet">

        <link href="https://vjs.zencdn.net/7.15.4/video-js.css" rel="stylesheet" />
        <script src="https://vjs.zencdn.net/7.15.4/video.js"></script>
    </head>

    <body>

        <header id="header" class="fixed-top ">
            <div class="container d-flex align-items-center justify-content-center">
            <a href="#" class="logo"><img src="{{ asset('landingPage//img/logo.png') }}" class="img-fluid"></a>
            </div>
        </header>

        <section id="hero" class="d-flex align-items-center">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 pt-2 pt-lg-0 order-2 order-lg-1 d-flex flex-column justify-content-center">
                        <h1>Limpe seu nome antes de quitar suas dívidas</h1>
                        <h2>Método 100% legal <i class="ri-checkbox-circle-fill"></i></h2>
                        <p> Amparado pelo Código de Defesa do Consumidor nos Art. 42 e 43 e pela Súmula 359 do Supremo Tribunal de Justiça.</p>
                        <div class="mt-3">
                            <a href="#about" class="btn-get-quote">Quero limpar meu nome agora</a>
                        </div>
                    </div>
                    <div class="col-lg-6 order-1 order-lg-2 hero-img">
                        <video width="100%" controls class="ia-video" autoplay>
                            <source src="{{ asset('landingPage//video/ia.mp4') }}" type="video/mp4">
                        </video>
                    </div>
                </div>
            </div>
        </section>

        <main id="main">

            <section id="about" class="about">
                <div class="container">

                    <div class="row content">
                        <div class="col-lg-7">
                            <h2>Seu caminho para a estabilidade financeira começa aqui!</h2>
                            <h3>Cadastre-se agora e dê o primeiro passo em direção a uma vida mais próspera e segura.</h3>
                        </div>
                        <div class="col-lg-5 pt-4 pt-lg-0">
                            <div class="formulario">
                                <form action="{{ route('vender', ['id' => $id]) }}" method="POST">
                                    <h4>Cadastre-se</h4>
                                    <input type="hidden" value={{ csrf_token() }} name="_token">
                                    <input type="hidden" name="id_vendedor" value="{{ $id }}">
                                    <input type="hidden" name="produto" value="1">
                                    <input type="hidden" name="franquia" value="limpanome">
                                    <input type="hidden" name="valor" value="997">
                                    <div class="form-group">
                                        <input type="text" class="form-control" placeholder="Nome:" name="cliente" required>
                                    </div>
                                    <div class="form-group">
                                        <input type="text" class="form-control" placeholder="CPF:" name="cpf" id="cpf" oninput="mascaraCpf(this)" required>
                                    </div>
                                    <div class="form-group">
                                        <input type="text" class="form-control" placeholder="Data Nascimento" name="dataNascimento" oninput="mascaraData(this)" required>
                                    </div>
                                    <div class="form-group">
                                        <input type="text" class="form-control" placeholder="E-mail:" name="email" required>
                                    </div>
                                    <div class="form-group">
                                        <input type="text" class="form-control" placeholder="WhatsApp:" name="whatsapp" oninput="mascaraTelefone(this)" required>
                                    </div>
                                    <button type="submit" class="btn-get-started">Concluir</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section id="why-us" class="why-us">
                <div class="container">
                    <div class="row d-flex align-items-center">
                        <div class="col-lg-6 pt-4 pt-lg-0">
                            <img src="{{ asset('landingPage//img/sonhos.png') }}" alt="" width="100%">
                        </div>

                        <div class="col-lg-6">
                            <h2>Aumente em até 90% as suas chances de voltar para o mercado de crédito novamente e poder realizar os seus sonhos.</h2>
                            <h3>Limpando seu nome você aumenta suas chances de ter um cartão de crédito, fazer empréstimos e financiamentos.</h3>
                            <div class="mt-3 pt-3">
                                <a href="" class="btn-get-quote">Quero sair do negativado agora</a>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section id="cta" class="cta">
                <div class="container">
                    <div class="section-title">
                        <h2>Como funciona a nossa ação do limpa nome</h2>
                    </div>

                    <div class="row d-flex justify-content-center text-center">
                        <div class="col-lg-7">
                            <div class="video-container">
                                <video id="my-video" class="video-js vjs-theme-fantasy" controls autoplay loop muted>
                                    <source src="{{ asset('landingPage//video/cta.mp4') }}" type="video/mp4">
                                </video>
                                <div class="overlay" id="overlay">
                                    <img src="{{ asset('landingPage//img/player.png') }}" alt="Clique para ativar/desativar o som">
                                </div>
                            </div>
                            <div class="mt-3 pt-4">
                                <a href="" class="btn-get-quote">Sim! Quero sair agora do negativado</a>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section id="faq" class="faq">
                <div class="container">

                    <div class="section-title">
                        <h2>Perguntas Frequentes</h2>
                    </div>

                    <div class="col-lg-6">
                        <ul class="faq-list">

                            <li>
                                <div data-bs-toggle="collapse" class="collapsed question" href="#faq1">Quando o meu nome vai ficar limpo?
                                    <i class="bi bi-chevron-down icon-show"></i><i class="bi bi-chevron-up icon-close"></i>
                                </div>
                                <div id="faq1" class="collapse" data-bs-parent=".faq-list">
                                    <p> Em até 30 dias úteis </p>
                                </div>
                            </li>

                            <li>
                                <div data-bs-toggle="collapse" href="#faq2" class="collapsed question"> É seguro? Qual a garantia que vocês me dão que o serviço realmente será feito?
                                    <i class="bi bi-chevron-down icon-show"></i><i class="bi bi-chevron-up icon-close"></i>
                                </div>
                                <div id="faq2" class="collapse" data-bs-parent=".faq-list">
                                    <p> Sim! Você terá um título executivo extrajudicial que é o nosso contrato, assinado por um dos nossos representantes, que garante a nossa prestação de serviços. </p>
                                </div>
                            </li>

                            <li>
                                <div data-bs-toggle="collapse" href="#faq3" class="collapsed question">Minhas dívidas sumirão?
                                    <i class="bi bi-chevron-down icon-show"></i><i class="bi bi-chevron-up icon-close"></i>
                                </div>
                                <div id="faq3" class="collapse" data-bs-parent=".faq-list">
                                    <p> Não, o que vai sumir são os apontamentos que estão te negativando nos órgãos de proteção ao crédito (SPC, Serasa, Boa Vista e CENPROT), com a nossa ação você vai sair do negativado antes de quitar suas dívidas! </p>
                                </div>
                            </li>

                            <li>
                                <div data-bs-toggle="collapse" href="#faq4" class="collapsed question">Vou ter como poder ter um cartão de crédito, pegar empréstimo, financiar um carro ou uma casa?
                                    <i class="bi bi-chevron-down icon-show"></i><i class="bi bi-chevron-up icon-close"></i>
                                </div>
                                <div id="faq4" class="collapse" data-bs-parent=".faq-list">
                                    <p> O que podemos garantir é o nosso serviço, já em relação a ter crédito só poderíamos garantir se fôssemos banco. Mas pensa comigo se você fosse banco você iria emprestar dinheiro para quem está negativado com o score ruim ou para quem está com o nome limpo e o score restaurado? O que posso te afirmar aqui é se suas chances de ter crédito com o nome limpo aumentaram em até 90%. </p>
                                </div>
                            </li>
                        </ul>

                        <div class="mt-3">
                            <a href="" class="btn-get-quote">Entendi! Quero limpar meu nome agora</a>
                        </div>
                    </div>
                </div>
            </section>

            <section id="garantia" class="garantia">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-lg-6 pt-4 pt-lg-0">
                            <img src="{{ asset('landingPage//img/garantia.png') }}" alt="">
                        </div>

                        <div class="col-lg-6">
                            <h2>Garantia</h2>
                            <h3>Sua confiança é nossa prioridade. Prometemos entregar os resultados ofertados. Se, por algum motivo, você não obtiver o resultado esperado em 30 dias, reembolsaremos o valor investido integralmente. Sem riscos! Estamos comprometidos com seu sucesso. Confiamos tanto nosso trabalho que somos a única empresa do mercado que oferece essa garantia ao cliente. </h3>
                            <div class="mt-3 pt-3">
                                <a href="" class="btn-get-quote">Quero sair do negativado agora</a>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

        </main>

        <footer id="footer">
            <div class="container py-4">
                <div class="text-center">
                    <div class="copyright"> &copy; Copyright <strong><span>Grupo Sollution</span></strong>. Todos os direitos reservados </div>
                </div>
            </div>
        </footer>

        <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

        <script src="{{ asset('landingPage//vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
        <script src="{{ asset('landingPage//vendor/swiper/swiper-bundle.min.js') }}"></script>

        <script src="{{ asset('landingPage//js/main.js') }}"></script>
        <script src="{{ asset('landingPage//js/mask.js') }}"></script>
        <script src="{{ asset('landingPage//js/player.js') }}"></script>
        <script>
            $(function() {
                $("#datepicker").datepicker({
                    dateFormat: 'dd/mm/yy',
                });
            });

            function mascaraCpf(cpfInput) {
                let cpf = cpfInput.value;
                cpf = cpf.replace(/\D/g, '');
                cpf = cpf.replace(/(\d{3})(\d)/, '$1.$2');
                cpf = cpf.replace(/(\d{3})(\d)/, '$1.$2');
                cpf = cpf.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
                cpfInput.value = cpf;
            }

            function mascaraData(dataInput) {
                let data = dataInput.value;
                data = data.replace(/\D/g, '');
                data = data.replace(/(\d{2})(\d)/, '$1-$2')
                data = data.replace(/(\d{2})(\d)/, '$1-$2');
                dataInput.value = data;
            }

            function mascaraTelefone(telefoneInput) {
                let telefone = telefoneInput.value;
                telefone = telefone.replace(/\D/g, '');
                telefone = telefone.replace(/(\d{2})(\d)/, '($1) $2');
                telefone = telefone.replace(/(\d{5})(\d)/, '$1-$2');
                telefoneInput.value = telefone;
            }

        </script>

    </body>

</html>
