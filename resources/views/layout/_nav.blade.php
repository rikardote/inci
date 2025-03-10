<header>
    <nav class="navbar navbar-guinda navbar-fixed-top">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-target="#app-navbar-collapse" data-toggle="collapse">
                    <span class="sr-only">Interruptor de Navegación</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="/incidencias">
                    <img src="{{ asset('images/60issste.png') }}" alt="Logo ISSSTE" class="navbar-logo">
                </a>
            </div>

            <div class="collapse navbar-collapse" id="app-navbar-collapse">
                <ul class="nav navbar-nav navbar-right">
                    @if (\Auth::user()->admin() || \Auth::user()->member())
                        <li class="nav-item ">
                            <a href="#"><i class="fas fa-calendar-check"></i> Fecha de Cierre: {{ getFechaCierre() }}</a>
                        </li>
                        <li class="nav-item"><a href="{{ route('employees.index') }}"><i class="fas fa-users"></i> Empleados</a></li>
                        <li class="nav-item"><a href="{{ route('incidencias.index') }}"><i class="fas fa-pen-to-square"></i> Captura</a></li>
                        <li class="nav-item"><a href="{{ route('biometrico.registros') }}"><i class="fas fa-fingerprint"></i> Biométrico</a></li>
                    @endif

                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                            <i class="fas fa-file-lines"></i> Reportes <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu reports-menu" role="menu">
                            <li class="dropdown-header">Reportes Generales</li>
                            <li><a href="{{ route('reports.general') }}"><i class="fas fa-file-lines"></i> RH-5 </a></li>
                            <li><a href="{{ route('reports.diario') }}"><i class="fas fa-calendar"></i> Captura Diaria</a></li>
                            <li><a href="{{ route('reports.general.empleado') }}"><i class="fas fa-user"></i> Por Empleado (Kardex)</a></li>

                            <li class="divider"></li>
                            <li class="dropdown-header">Reportes Especiales</li>
                            <li><a href="{{ route('reports.vacaciones') }}"><i class="fas fa-umbrella-beach"></i> Vacaciones</a></li>
                            <li><a href="{{ route('reports.sinderecho') }}"><i class="fas fa-circle-exclamation"></i> Sin Derecho a Nota Buena</a></li>
                            <li><a href="{{ route('covid.index') }}"><i class="fas fa-kit-medical"></i> COVID-19</a></li>
                            <li><a href="{{ route('reports.licencias') }}"><i class="fas fa-stethoscope"></i> Licencias Médicas</a></li>
                            <li><a href="{{ route('reports.exceso_incapacidades') }}"><i class="fas fa-hospital"></i> Exceso de Incapacidades</a></li>
                            <li><a href="{{ route('reports.ausentismo') }}"><i class="fas fa-user-xmark"></i> Ausentismo</a></li>

                            <li class="divider"></li>
                            <li class="dropdown-header">Reportes Analíticos</li>
                            <li><a href="{{ route('reports.captura_por_dia') }}"><i class="fas fa-calendar"></i> Captura por Día</a></li>
                            <li><a href="{{ route('reports.estadistica') }}"><i class="fas fa-chart-bar"></i> Estadístico</a></li>
                            <li><a href="{{ route('reports.por_incidencia') }}"><i class="fas fa-list"></i> Por Incidencia</a></li>
                            <li><a href="{{ route('reports.val_aguinaldo') }}"><i class="fas fa-file-lines"></i> Anual de Faltas y Licencias</a></li>
                          <!--  <li><a href="{{ route('reports.cambio_de_guardia') }}"><i class="fas fa-light fa-table"></i></i> Cambio de Guardia (TXT)</a></li> -->
                            <li><a href="{{ route('biometrico.registros') }}"><i class="fas fa-fingerprint"></i> Biométrico</a></li>

                            @if (Auth::user()->username == '376597')
                                <li class="divider"></li>
                                <li><a href="{{ route('gys.index') }}"><i class="fas fa-clock"></i> Guardias y Suplencias</a></li>
                            @endif
                        </ul>
                    </li>

                    @if (Auth::guest())
                        <li><a href="{{ url('/login') }}"><i class="fas fa-right-to-bracket"></i> Login</a></li>
                    @else
                        <li class="dropdown user-dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                <span class="avatar-circle">
                                    <img src="{{ asset('images/logo_horizontal_ISSSTE.png') }}" class="avatar-img" alt="Avatar">
                                </span>
                                {{ Auth::user()->name }} <span class="caret"></span>
                            </a>

                            <ul class="dropdown-menu user-menu" role="menu">
                                @if (\Auth::user()->admin())
                                    <li><a href="{{ url('/dashboard') }}"><i class="fas fa-gauge"></i> Dashboard</a></li>
                                    <li class="divider"></li>
                                @endif
                                <li><a href="{{ url('/logout') }}"><i class="fas fa-right-from-bracket"></i> Cerrar Sesión</a></li>
                            </ul>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>
</header>
