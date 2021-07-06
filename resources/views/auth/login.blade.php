<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ asset('plugins/bootstrap/css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/font-awesome/css/font-awesome.min.css') }}">

</head>
<body>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ asset('plugins/bootstrap/css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/font-awesome/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/login_style.css') }}"

</head>
<body>
    <div class="login-form">
      <img src="images/logo_trans.png" alt="">
      <form  role="form" method="POST" action="{{ url('/login') }}">
      <div class="form-group ">
       {!! csrf_field() !!}
       <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
        <input type="text" class="form-control" placeholder="Usuario: " name="username" value="{{ old('username') }}">
       </div>
       <i class="fa fa-user"></i>
      </div>
      <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }} log-status">
      
       <input type="password" class="form-control" placeholder="Contraseña" name="password">
       <i class="fa fa-lock"></i>

      
        @if ($errors->has('password'))
          <span class="help-block">
           <span class="alert"> <strong>{{ $errors->first('password') }}</strong> </span>

          </span>
        @endif
        @if ($errors->has('email'))
          <span class="help-block">
            <span class="alert"> <strong>{{ $errors->first('email') }}</strong> </span>
            
          </span>
        @endif
      
      
      </div>
      <button type="submit" class="log-btn">
        <i class="fa fa-btn fa-sign-in"></i> Acceder
      </button>
   </form>    
   <br> 
    <div align="center">
    <h2>SISTEMA PARA EL REGISTRO Y CONTROL DE ASISTENCIA</h2>
    </div>
   </div>

    <script src="{{ asset('plugins/jquery/js/jquery.js') }}"></script>
    <script src="{{ asset('js/shake.js') }}"></script>
  </body>  
  
  
</html>