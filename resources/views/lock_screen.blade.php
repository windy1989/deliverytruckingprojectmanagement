<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <title>Digitrans - Layar Kunci</title>
    <link href="{{ asset('website/icon.png') }}" rel="icon" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css?family=Quicksand:400,500,600,700&display=swap" rel="stylesheet">
    <link href="{{ asset('template/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('template/assets/css/plugins.css') }}" rel="stylesheet">
    <link href="{{ asset('template/assets/css/authentication/form-2.css') }}" rel="stylesheet">
    <link href="{{ asset('template/assets/css/forms/theme-checkbox-radio.css') }}" rel="stylesheet" >
    <link href="{{ asset('template/assets/css/forms/switches.css') }}" rel="stylesheet" >
    <script src="{{ asset('template/assets/js/libs/jquery-3.1.1.min.js') }}"></script>
    <script src="https://www.recaptcha.net/recaptcha/api.js" async defer></script>

    <style>
        body {
            background: url('{{ asset("website/bg-login.jpg") }}');
            width: 100%;
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
        }
    </style>
</head>
<body class="form">
    <div class="form-container outer">
        <div class="form-form">
            <div class="form-form-wrap">
                <div class="form-container">
                    <div class="form-content">
                        @if(session('fail_login'))
                            <div class="alert alert-danger"><strong>{{ session('fail_login') }}</strong></div>
                        @elseif(session('logout'))
                            <div class="alert alert-success"><strong>{{ session('logout') }}</strong></div>
                        @endif
                        <form action="{{ url('do_login') }}" method="POST" class="text-left">
                            @csrf
                            <input type="hidden" name="user" id="user" value="{{ session('email') }}">
                            <div class="form-group">
                                <div class="text-center text-secondary">
                                    <img src="{{ session('photo') }}" style="max-width: 150px;" class="usr-profile" alt="avatar">
                                    <h4 class="mt-3 mb-5">{{ session('name') }}</h4>
                                </div>
                            </div>
                            <div class="form">
                                <div id="password-field" class="field-wrapper input mb-2">
                                    <div class="d-flex justify-content-between">
                                        <label for="password">PASSWORD</label>
                                    </div>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-lock"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                                    <input id="password" name="password" type="password" class="form-control" placeholder="Password" required>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" id="toggle-password" class="feather feather-eye"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                </div>
                                <div class="form-group">
                                    <center>
                                        {!! NoCaptcha::display() !!}
                                    </center>
                                </div>
                                <div class="d-sm-flex justify-content-between">
                                    <div class="field-wrapper">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <button type="button" onclick="clearSession()" class="btn btn-danger">Keluar</button>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <button type="submit" class="btn btn-primary">Masuk</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function clearSession() {
            '{{ session()->flush() }}';
            location.reload();
        }
    </script>

    <script src="{{ asset('template/bootstrap/js/popper.min.js') }}"></script>
    <script src="{{ asset('template/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('template/assets/js/authentication/form-2.js') }}"></script>
</body>
</html>
