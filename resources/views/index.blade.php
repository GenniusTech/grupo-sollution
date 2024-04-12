@extends('layout')
@section('conteudo')
    <div class="container">
        <div class="row justify-content-center">

            <div class="col-xl-10 col-lg-12 col-md-9">
                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <div class="row">
                            <div class="col-lg-6 d-none d-lg-block bg-login-image"></div>
                            <div class="col-lg-6">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">
                                            Acesso Para Parceiros.
                                            <p class="p-min">Bem-vindo(a)!</p>
                                        </h1>
                                        @if (session('error'))
                                            <div class="alert alert-danger alert-dismissible" role="alert">
                                                {{ session('error') }}
                                            </div>
                                        @endif
                                    </div>
                                    <form class="user" method="POST" action="{{ route('login_action') }}">
                                        <input type="hidden" value={{ csrf_token() }} name="_token">
                                        <div class="form-group">
                                            <input type="text" class="form-control form-control-user" name="email"
                                                placeholder="Email">
                                        </div>
                                        <div class="form-group">
                                            <div class="input-group">
                                                <input type="password" class="form-control form-control-user"
                                                    name="password" id="password" placeholder="Senha">
                                                <div class="input-group-append">
                                                    <span class="input-group-text" id="password-toggle"
                                                        onclick="togglePasswordVisibility()">
                                                        <i class="fa fa-eye" id="eye-icon"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-primary btn-user btn-block"> Login </button>
                                    </form>
                                    <hr class="sidebar-divider">
                                    <div class="text-center">
                                        <small>V 0.0.1</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script>
        function togglePasswordVisibility() {
            var passwordInput = document.getElementById("password");
            var eyeIcon = document.getElementById("eye-icon");

            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                eyeIcon.classList.remove("fa-eye");
                eyeIcon.classList.add("fa-eye-slash");
            } else {
                passwordInput.type = "password";
                eyeIcon.classList.remove("fa-eye-slash");
                eyeIcon.classList.add("fa-eye");
            }
        }
    </script>
@endsection
