<header>
    <nav class="navbar navbar-guinda navbar-fixed-top">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-target="#app-navbar-collapse" data-toggle="collapse">
                    <span class="sr-only">Interruptor de Navegación</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="/incidencias">
                    <img src="{{ asset('images/avatar.png') }}" ">
                </a>
            </div>

            <div class="collapse navbar-collapse" id="app-navbar-collapse">
                <ul class="nav navbar-nav navbar-right">
                    @if (\Auth::user()->admin() || \Auth::user()->member())
                        <li class="nav-item date-badge">
                            <a href="#"><i class="fa fa-calendar-check-o"></i> Cierre: {{ getFechaCierre() }}</a>
                        </li>
                        <li class="nav-item"><a href="{{ route('employees.index') }}"><i class="fa fa-users"></i> Empleados</a></li>
                        <li class="nav-item"><a href="{{ route('incidencias.index') }}"><i class="fa fa-pencil-square-o"></i> Captura</a></li>
                        <li class="nav-item"><a href="{{ route('biometrico.registros') }}"><i class="fa fa-fingerprint"></i> Biométrico</a></li>
                    @endif

                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                            <i class="fa fa-file-text-o"></i> Reportes <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu reports-menu" role="menu">
                            <li class="dropdown-header">Reportes Generales</li>
                            <li><a href="{{ route('reports.general') }}"><i class="fa fa-file-text"></i> General (RH-5)</a></li>
                            <li><a href="{{ route('reports.diario') }}"><i class="fa fa-calendar-day"></i> Captura Diaria</a></li>
                            <li><a href="{{ route('reports.general.empleado') }}"><i class="fa fa-id-card"></i> Por Empleado (Kardex)</a></li>

                            <li class="divider"></li>
                            <li class="dropdown-header">Reportes Especiales</li>
                            <li><a href="{{ route('reports.vacaciones') }}"><i class="fa fa-umbrella-beach"></i> Vacaciones</a></li>
                            <li><a href="{{ route('reports.sinderecho') }}"><i class="fa fa-exclamation-circle"></i> Sin Derecho a Nota Buena</a></li>
                            <li><a href="{{ route('covid.index') }}"><i class="fa fa-virus"></i> COVID-19</a></li>
                            <li><a href="{{ route('reports.licencias') }}"><i class="fa fa-file-medical"></i> Licencias Médicas</a></li>
                            <li><a href="{{ route('reports.exceso_incapacidades') }}"><i class="fa fa-procedures"></i> Exceso de Incapacidades</a></li>
                            <li><a href="{{ route('reports.ausentismo') }}"><i class="fa fa-user-times"></i> Ausentismo</a></li>

                            <li class="divider"></li>
                            <li class="dropdown-header">Reportes Analíticos</li>
                            <li><a href="{{ route('reports.captura_por_dia') }}"><i class="fa fa-calendar-alt"></i> Captura por Día</a></li>
                            <li><a href="{{ route('reports.estadistica') }}"><i class="fa fa-chart-bar"></i> Estadístico</a></li>
                            <li><a href="{{ route('reports.por_incidencia') }}"><i class="fa fa-list-alt"></i> Por Incidencia</a></li>
                            <li><a href="{{ route('reports.val_aguinaldo') }}"><i class="fa fa-money-bill"></i> Anual de Faltas y Licencias</a></li>
                            <li><a href="{{ route('biometrico.registros') }}"><i class="fa fa-fingerprint"></i> Biométrico</a></li>

                            @if (Auth::user()->username == '376597')
                                <li class="divider"></li>
                                <li><a href="{{ route('gys.index') }}"><i class="fa fa-user-clock"></i> Guardias y Suplencias</a></li>
                            @endif
                        </ul>
                    </li>

                    @if (Auth::guest())
                        <li><a href="{{ url('/login') }}"><i class="fa fa-sign-in"></i> Login</a></li>
                    @else
                        <li class="dropdown user-dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                <span class="avatar-circle"><i class="fa fa-user"></i></span> {{ str_limit(Auth::user()->name, 15) }} <span class="caret"></span>
                            </a>

                            <ul class="dropdown-menu user-menu" role="menu">
                                @if (\Auth::user()->admin())
                                    <li><a href="{{ url('/dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
                                    <li class="divider"></li>
                                @endif
                                <li><a href="{{ url('/logout') }}"><i class="fa fa-sign-out"></i> Cerrar Sesión</a></li>
                            </ul>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>
</header>
