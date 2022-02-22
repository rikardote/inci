<nav class="navbar navbar-default navbar-fixed-top">
  <div class="container-fluid">
        <div class="container">
            <div class="navbar-header">

                <!-- Collapsed Hamburger -->
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                    <span class="sr-only">Toggle Navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>

                <!-- Branding Image -->
                
                 <a class="brand" style="margin: 0; float: none; text-align:center" href="/incidencias"><img src="{{ asset('images/logo_trans2.png') }}"style="width: 60px; height: 60px;"></a>  
            </div>

            <div class="collapse navbar-collapse" id="app-navbar-collapse">
                <!-- Left Side Of Navbar -->
                <ul class="nav navbar-nav navbar-right">
                    @if (\Auth::user()->admin() || \Auth::user()->member()) 
                        <li><a href="">Fecha de Cierre: {{ getFechaCierre() }}</a></li>
                        <li class="{{ Request::segment(1) === 'employees' ? 'active' : null  }}"><a href="{{route('employees.index')}}"><i class="fa fa-users fa-3x"></i>EMPLEADOS</a></li>
                        <li class="{{ Request::segment(1) === 'incidencias' ? 'active' : null  }}"><a href="{{route('incidencias.index')}}"><i class="fa fa-pencil-square-o fa-3x"></i>CAPTURA DE INCIDENCIAS</a></li>
                    @endif
                    <li class="dropdown {{ Request::segment(1) === 'reporte' ? 'active' : null  }}">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                <i class="fa fa-file-pdf-o fa-3x"></i> REPORTES<span class="caret"></span>
                            </a>

                            <ul class="dropdown-menu" role="menu">
                                <li><a href="{{ route('reports.general') }}"></i> GENERAL (RH-5)</a></li>
                                <li role="separator" class="divider"></li>
                                 <li><a href="{{ route('reports.diario') }}"></i> MOSTRAR CAPTURA DE INCIDENCIAS DIARIA</a></li>
                                <li role="separator" class="divider"></li>
                                <li><a href="{{ route('reports.general.empleado') }}"></i> REPORTE POR EMPLEADO (KARDEX)</a></li>
                                <!-- 
                                    <li role="separator" class="divider"></li>
                                    <li><a href="{{ route('reports.prima_dominical') }}"></i> REPORTE PRIMA DOMINICAL</a></li> 
                                -->
                                <li role="separator" class="divider"></li>
                                <li><a href="{{ route('reports.vacaciones') }}"></i> VACACIONES (REVISION RAPIDA)</a></li>
                                <li role="separator" class="divider"></li>
                                <li><a href="{{ route('reports.sinderecho') }}"></i> REPORTE SIN DERECHO A NOTA BUENA</a></li>
                                <li role="separator" class="divider"></li>
                                <li><a href="{{ route('covid.index') }}"></i> REPORTE COVID-19 POR UNIDAD</a></li>

                                <li role="separator" class="divider"></li>
                                <li><a href="{{ route('reports.licencias') }}"></i> REPORTES LIC. MEDICAS EXPEDIDAS</a></li>
                                <li><a href="{{ route('reports.exceso_incapacidades') }}"></i> REPORTES EXCESO DE INCAPACIDADES</a></li>
                                <li role="separator" class="divider"></li>
                                <li><a href="{{ route('reports.ausentismo') }}"></i> REPORTES DE AUSENTISMO</a></li>
                                <li role="separator" class="divider"></li>
                                <!--
                                    <li><a href="{{ route('reports.pendientes') }}"></i> Pendientes</a></li>
                                    <li role="separator" class="divider"></li>
                                -->
                                <li><a href="{{ route('reports.estadistica') }}"></i> REPORTES ESTADISTICO</a></li>
                                <li role="separator" class="divider"></li>
                                <li><a href="{{ route('reports.por_incidencia') }}"></i> REPORTE POR INCIDENCIA</a></li>
                                <li><a href="{{ route('reports.val_aguinaldo') }}"></i> REPORTE ANUAL DE FALTAS Y LICENCIAS S/G</a></li>
                                <li><a href="{{ route('biometrico_getchecadas') }}"></i> REPORTE BIOMETRICO</a></li>
                            </ul>
                        </li>
                    

                <!-- Right Side Of Navbar -->
               
                    <!-- Authentication Links -->
                    @if (Auth::guest())
                        <li><a href="{{ url('/login') }}">LOGIN</a></li>
                        <li><a href="{{ url('/register') }}">REGISTER</a></li>
                    @else
                        <li class="dropdown {{ Request::segment(1) === 'dashboard' ? 'active' : null  }}">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                <i class=""></i> {{ Auth::user()->name }} <span class="caret"></span>
                            </a>
                            
                            <ul class="dropdown-menu" role="menu">
                                @if (\Auth::user()->admin()) 
                                    <li><a href="{{ url('/dashboard') }}"><i class="fa fa-cog fa-spin"></i> DASHBOARD</a></li>
                       
                                @endif

                                <li><a href="{{ url('/logout') }}"><i class="fa fa-btn fa-sign-out"></i> SALIR</a></li>
                            </ul>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
        </div><!-- /.container-fluid -->
    </nav>
        
        
     