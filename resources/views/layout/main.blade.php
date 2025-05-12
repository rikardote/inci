<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Default')</title>


   <!-- <link rel="stylesheet" href="{{ asset('plugins/fontawesome/css/font-awesome.min.css') }}">-->
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,700,600,300' rel='stylesheet' type='text/css'>

    <link rel="stylesheet" href="{{ asset('plugins/flatpickr/flatpickr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datetextentry/datetextentry.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/sweetalert/dist/sweetalert.css') }}">

    <link rel="stylesheet" href="{{ asset('plugins/chosen/chosen.css') }}">

    <link rel="stylesheet" href="{{ asset('css/card.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/toastr/css/toastr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/jquery/css/themes/smothness/jquery-ui.css') }}">
    <link rel="stylesheet" href="{{ asset('css/application.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/bootstrap/css/bootstrap-theme-guinda.css') }}">

<!-- filepath: /d:/swtools/laragon/www/incidencias/resources/views/layout/app.blade.php -->
<!-- Reemplazar la antigua referencia a Font Awesome -->
<!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css"> -->
<link rel="stylesheet" href="{{ asset('plugins/fontawesome6/css/all.min.css') }}">
    @yield('css')

</head>

<body class="skin-guinda">

    <!--NAVEGACION PARTIAL-->
    <div class="iner">
        @include('layout._nav')
    </div>
    <div class="supreme_container">

        <!--CONTENIDO-->
        @if(check_manto())
        <div class="panel panel-warning">
            @else
            <div class="panel panel-primary">
                @endif
                <div class="panel-heading supreme-container">
                    <h3 class="panel-title">@yield('title')</h3>
                </div>
                <div class="panel-body">
                    <div id="msj-success">
                        @include('flash::message')
                        {!! Toastr::render() !!}
                    </div>
                    @if(check_manto() && !\Auth::user()->admin())
                    @include('admin.mantenimiento.index')
                    @else
                    @yield('content')
                    @endif
                </div>
            </div>


        </div>

        <script src="{{ asset('plugins/jquery/js/jquery.js') }}"></script>
        <script src="{{ asset('plugins/datepicker/js/jquery-ui.min.js') }}"></script>
        <script src="{{ asset('plugins/datepicker/js/ui.datepicker-es-MX.js') }}"></script>
        <script src="{{ asset('plugins/bootstrap/js/bootstrap.js') }}"></script>
        <script src="{{ asset('plugins/chosen/chosen.jquery.js') }}"></script>
        <script src="{{ asset('plugins/datetextentry/datetextentry.js') }}"></script>
        <script src="{{ asset('plugins/sweetalert/dist/sweetalert.min.js') }}"></script>
        <script src="{{ asset('plugins/toastr/js/toastr.min.js') }}"></script>
        <script src="{{ asset('plugins/flatpickr/flatpickr.js') }}"></script>
        <script src="{{ asset('plugins/flatpickr/dist_l10n_es.js') }}"></script>

        <script src="{{ asset('js/script.js') }}"></script>


        @yield('js')

</body>

</html>
