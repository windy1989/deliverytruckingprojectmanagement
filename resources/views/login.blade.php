<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <title>Digitrans - Login</title>
    <link href="{{ asset('website/icon.png') }}" rel="icon">
    <link href="https://fonts.googleapis.com/css?family=Quicksand:400,500,600,700&display=swap" rel="stylesheet">
    <link href="{{ asset('template/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('template/assets/css/plugins.css') }}" rel="stylesheet">
    <link href="{{ asset('template/assets/css/authentication/form-1.css') }}" rel="stylesheet">
    <link href="{{ asset('template/assets/css/forms/theme-checkbox-radio.css') }}" rel="stylesheet">
    <link href="{{ asset('template/assets/css/forms/switches.css') }}" rel="stylesheet">
    <style>
        .form-image .l-image {
            background-image: url('{{ asset("website/bg-full.png") }}');
            width: 100%;
            height: 100%;
            background-size: cover;
        }
    </style>
</head>
<body class="form">
    <div class="form-container">
        <div class="form-form">
            <div class="form-form-wrap">
                <div class="form-container">
                    <div class="form-content">
                        <h1 class="mb-5">
                            <img src="{{ asset('website/logo.jpeg') }}" style="max-width:350px;" alt="Logo">
                        </h1>
                        @if(session('fail_login'))
                            <div class="alert alert-danger"><strong>{{ session('fail_login') }}</strong></div>
                        @elseif(session('logout'))
                            <div class="alert alert-success"><strong>{{ session('logout') }}</strong></div>
                        @endif
                        <form action="{{ url('do_login') }}" method="POST" class="text-left">
                            @csrf
                            <div class="form">
                                <div id="username-field" class="field-wrapper input">
                                    <svg class="text-dark" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                                    <input type="text" name="user" id="user" class="form-control" placeholder="Username / Email / No Telp" required>
                                </div>
                                <div id="password-field" class="field-wrapper input mb-2">
                                    <svg class="text-dark" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-lock"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                                    <input type="password" name="password" id="password" class="form-control" placeholder="Password" required>
                                </div>
                                <div class="d-sm-flex justify-content-between">
                                    <div class="field-wrapper toggle-pass">
                                        <p class="d-inline-block">Lihat Password</p>
                                        <label class="switch s-primary">
                                            <input type="checkbox" id="toggle-password" class="d-none">
                                            <span class="slider round"></span>
                                        </label>
                                    </div>
                                    <div class="field-wrapper">
                                        <button type="submit" class="btn btn-primary">Masuk</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <p class="terms-conditions">
                            © {{ date('Y') }} All Rights Reserved.
                            <a href="javascript:void(0);" class="text-warning">Digital Trans Indonesia</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-image">
            <div class="l-image"></div>
        </div>
    </div>
    <script src="{{ asset('template/assets/js/libs/jquery-3.1.1.min.js') }}"></script>
    <script src="{{ asset('template/bootstrap/js/popper.min.js') }}"></script>
    <script src="{{ asset('template/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('template/assets/js/authentication/form-1.js') }}"></script>
</body>
</html>