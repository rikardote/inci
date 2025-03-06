<header class=" navbar navbar-inverse">
    <nav class="navbar navbar-inverse navbar-fixed-top">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-target="#navbarMainCollapse"
                    data-toggle="collapse">
                    <span class="sr-only">Interruptor de Navegación</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="brand" style="margin: 0; float: none; text-align:center" href="/incidencias"><img
                        src="{{ asset('images/avatar.png') }}"style="width: 50px; height: 50px;"></a>
            </div>
            <div class="collapse navbar-collapse" id="app-navbar-collapse">
                <!-- Left Side Of Navbar -->
                <ul class="nav navbar-nav navbar-right">
                    @if (\Auth::user()->admin() || \Auth::user()->member())
                        <li><a href="#">Fecha de Cierre: {{ getFechaCierre() }}</a></li>
                        <li><a href="{{ route('employees.index') }}">Empleados</a></li>
                        <li><a href="{{ route('incidencias.index') }}">Captura de Incidencias</a></li>
                        <li><a href="{{ route('biometrico.get_checadas') }}"> Biometrico</a></li>
                    @endif


                    <li>
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                            aria-expanded="false">
                            Reportes<span class="caret"></span>
                        </a>

                        <ul class="dropdown-menu" role="menu">
                            <li><a href="{{ route('reports.general') }}"></i> GENERAL (RH-5)</a></li>
                            <li role="separator" class="divider"></li>
                            <li><a href="{{ route('reports.diario') }}"></i> MOSTRAR CAPTURA DE INCIDENCIAS DIARIA</a>
                            </li>
                            <li role="separator" class="divider"></li>
                            <li><a href="{{ route('reports.general.empleado') }}"></i> REPORTE POR EMPLEADO (KARDEX)</a>
                            </li>
                            <!--
                                <li role="separator" class="divider"></li>
                                <li><a href="{{ route('reports.prima_dominical') }}"></i> REPORTE PRIMA DOMINICAL</a></li>
                            -->
                            <li role="separator" class="divider"></li>
                            <li><a href="{{ route('reports.vacaciones') }}"></i> VACACIONES (REVISION RAPIDA)</a></li>
                            <li role="separator" class="divider"></li>
                            <li><a href="{{ route('reports.sinderecho') }}"></i> REPORTE SIN DERECHO A NOTA BUENA</a>
                            </li>
                            <li role="separator" class="divider"></li>
                            <li><a href="{{ route('covid.index') }}"></i> REPORTE COVID-19</a></li>

                            <li role="separator" class="divider"></li>
                            <li><a href="{{ route('reports.licencias') }}"></i> REPORTES LIC. MEDICAS EXPEDIDAS</a>
                            </li>
                            <li><a href="{{ route('reports.exceso_incapacidades') }}"></i> REPORTES EXCESO DE
                                    INCAPACIDADES</a></li>
                            <li role="separator" class="divider"></li>
                            <li><a href="{{ route('reports.ausentismo') }}"></i> REPORTES DE AUSENTISMO</a></li>
                            <li role="separator" class="divider"></li>
                            <!--
                                <li><a href="{{ route('reports.pendientes') }}"></i> Pendientes</a></li>
                                <li role="separator" class="divider"></li>
                            -->
                            <li><a href="{{ route('reports.captura_por_dia') }}"></i> REPORTE CAPTURA DE INCIDENCIAS
                                    POR DIA</a></li>
                            <li role="separator" class="divider"></li>
                            <li><a href="{{ route('reports.estadistica') }}"></i> REPORTES ESTADISTICO</a></li>
                            <li role="separator" class="divider"></li>
                            <li><a href="{{ route('reports.por_incidencia') }}"></i> REPORTE POR INCIDENCIA</a></li>
                            <li><a href="{{ route('reports.val_aguinaldo') }}"></i> REPORTE ANUAL DE FALTAS Y LICENCIAS
                                    S/G</a></li>
                            <li><a href="{{ route('biometrico.get_checadas') }}"></i> REPORTE BIOMETRICO</a></li>

                            @if (Auth::user()->username == '376597')
                                <li role="separator" class="divider"></li>
                                <li><a href="{{ route('gys.index') }}"></i> REPORTE GUARDIAS Y SUPLENCIAS</a></li>
                            @endif

                        </ul>
                    </li>


                    <!-- Right Side Of Navbar -->

                    <!-- Authentication Links -->
                    @if (Auth::guest())
                        <li><a href="{{ url('/login') }}">LOGIN</a></li>
                        <li><a href="{{ url('/register') }}">REGISTER</a></li>
                    @else
                        <li>
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                                aria-expanded="false">
                                <i class=""></i> {{ Auth::user()->name }} <span class="caret"></span>
                            </a>

                            <ul class="dropdown-menu" role="menu">
                                @if (\Auth::user()->admin())
                                    <li><a href="{{ url('/dashboard') }}"><i class="fa fa-cog fa-spin"></i>
                                            DASHBOARD</a></li>
                                @endif

                                <li><a href="{{ url('/logout') }}"><i class="fa fa-btn fa-sign-out"></i> SALIR</a></li>
                            </ul>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>



    </nav>



</header>
