<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <title>Digi Trans Indonesia</title>
    <link href="{{ asset('website/icon.png') }}" rel="icon" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css?family=Quicksand:400,500,600,700&display=swap" rel="stylesheet">
    <link href="{{ asset('template/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('template/assets/css/plugins.css') }}" rel="stylesheet">
    <link href="{{ asset('template/assets/css/pages/error/style-400.css') }}" rel="stylesheet">
</head>
<body class="error404 text-center">
    <div class="container-fluid error-content">
        <div class="">
            <h1 class="error-number">503</h1>
            <p class="mini-text text-uppercase">Server Down!</p>
            <a href="{{ url('dashboard') }}" class="btn btn-primary mt-5">Dashboard</a>
        </div>
    </div>
    <script src="{{ asset('template/assets/js/libs/jquery-3.1.1.min.js') }}"></script>
    <script src="{{ asset('template/bootstrap/js/popper.min.js') }}"></script>
    <script src="{{ asset('template/bootstrap/js/bootstrap.min.js') }}"></script>
</body>
</html>
