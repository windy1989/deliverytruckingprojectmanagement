<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title }}</title>

    <link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
    <link href="{{ asset('website/icon.png') }}" rel="icon" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css?family=Quicksand:400,500,600,700&display=swap" rel="stylesheet">
    <link href="{{ asset('template/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('template/assets/css/bootstrap-notifications.min.css') }}" rel="stylesheet">
    <link href="{{ asset('template/assets/css/plugins.css') }}" rel="stylesheet">
    <link href="{{ asset('template/plugins/select2/select2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('template/plugins/animate/animate.css') }}" rel="stylesheet">
    <link href="{{ asset('template/plugins/table/datatable/datatables.css') }}" rel="stylesheet">
    <link href="{{ asset('template/plugins/table/datatable/dt-global_style.css') }}" rel="stylesheet">
    <link href="{{ asset('template/plugins/notification/snackbar/snackbar.min.css') }}" rel="stylesheet">
    <link href="{{ asset('template/assets/css/forms/theme-checkbox-radio.css') }}" rel="stylesheet">
    <link href="{{ asset('template/assets/css/elements/alert.css') }}" rel="stylesheet">
    <link href="{{ asset('template/plugins/sweetalerts/sweetalert2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('template/plugins/sweetalerts/sweetalert.css') }}" rel="stylesheet">
    <link href="{{ asset('template/assets/css/components/custom-sweetalert.css') }}" rel="stylesheet">
    <link href="{{ asset('template/assets/css/components/cards/card.css') }}" rel="stylesheet">
    <link href="{{ asset('template/assets/css/forms/theme-checkbox-radio.css') }}" rel="stylesheet">
    <link href="{{ asset('template/assets/css/components/tabs-accordian/custom-accordions.css') }}" rel="stylesheet">
    <link href="{{ asset('template/assets/css/apps/invoice.css') }}" rel="stylesheet">
    <link href="{{ asset('template/plugins/perfect-scrollbar/perfect-scrollbar.css') }}" rel="stylesheet">
    <link href="{{ asset('template/plugins/pricing-table/css/component.css') }}" rel="stylesheet">
    <link href="{{ asset('template/plugins/file-upload/file-upload-with-preview.min.css') }}" rel="stylesheet">
    <link href="{{ asset('template/assets/css/components/timeline/custom-timeline.css') }}" rel="stylesheet">
    <link href="{{ asset('template/assets/css/forms/switches.css') }}" rel="stylesheet">
    <link href="{{ asset('plugins/lightbox/dist/css/lightbox.min.css') }}" rel="stylesheet">
    <link href="{{ asset('plugins/fontawesome/css/all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('plugins/waitme/waitMe.min.css') }}" rel="stylesheet">
    <link href="{{ asset('plugins/chart-js/dist/Chart.min.css') }}" rel="stylesheet">
    <link href="{{ asset('plugins/custom.css') }}" rel="stylesheet">
    <script src="{{ asset('template/assets/js/libs/jquery-3.1.1.min.js') }}"></script>
    <script src="{{ asset('template/plugins/table/datatable/datatables.js') }}"></script>
    <script src="{{ asset('template/plugins/select2/select2.min.js') }}"></script>
    <script src="{{ asset('template/plugins/notification/snackbar/snackbar.min.js') }}"></script>
    <script src="{{ asset('template/plugins/input-mask/jquery.inputmask.bundle.min.js') }}"></script>
    <script src="{{ asset('template/plugins/sweetalerts/promise-polyfill.js') }}"></script>
    <script src="{{ asset('template/plugins/sweetalerts/sweetalert2.min.js') }}"></script>
    <script src="{{ asset('template/plugins/file-upload/file-upload-with-preview.min.js') }}"></script>
    <script src="{{ asset('plugins/lightbox/dist/js/lightbox.min.js') }}"></script>
    <script src="{{ asset('plugins/waitme/waitMe.min.js') }}"></script>
    <script src="{{ asset('plugins/number_format/jquery.number.min.js') }}"></script>
    <script src="{{ asset('plugins/chart-js/dist/chart.min.js') }}"></script>
    <script src="{{ asset('plugins/custom.js') }}"></script>
    <script src="{{asset('plugins/dropzone/dropzone.min.js')}}"></script>
    {{-- <script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script> --}}
    <style>
        .user-cover-image {
            background-image: url('{{ asset("website/bg-full.png") }}') !important;
            background-size: cover !important;
        }
    </style>
</head>