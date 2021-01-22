<?php
session_start();
$_SESSION['autorizado'] = false;


$msg="";
$email="";

if(isset($_POST['email']) && isset($_POST['password'])) {

  if ($_POST['email']==""){
    $msg.="Debe ingresar un email <br>";
  }else if ($_POST['password']=="") {
    $msg.="Debe ingresar la clave <br>";
  }else {
    $email = strip_tags($_POST['email']);
    $password= sha1(strip_tags($_POST['password']));

    $mysqli = mysqli_connect("localhost","root","","phptube");

    if ($mysqli==false){
      echo "Hubo un problema al conectarse a María DB";
      die();
    }

    $resultado = $mysqli->query("SELECT * FROM `usuarios` WHERE `usuarios_email` = '".$email."' AND  `usuarios_password` = '".$password."' ");
    $usuarios = $resultado->fetch_all(MYSQLI_ASSOC);


    //cargo datos del usuario en variables de sesión
    $_SESSION['usuarios_id'] = $usuarios[0]['usuarios_id'];
    $_SESSION['usuarios_email'] = $usuarios[0]['usuarios_email'];
    $_SESSION['usuarios_ultimo_login'] = $usuarios[0]['usuarios_ultimo_login'];

    //cuento cuantos elementos tiene $tabla,
    $cantidad = count($usuarios);

    if ($cantidad == 1){
      $hoy = date ( "Y-m-d H:i:s" );
      $resultado = $mysqli->query("UPDATE `usuarios` SET `usuarios_ultimo_login` = '".$hoy."' WHERE `usuarios_email` =  '".$email."' ");
      $msg .= "Exito!!!";
      $_SESSION['autorizado'] = true;
      echo '<meta http-equiv="refresh" content="2; url=principal.php">';
    }else{
      $msg .= "Acceso denegado!!!";
      $_SESSION['autorizado'] = false;
    }
  }
}

?>


<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>AdminLTE 2 | Log in</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="bower_components/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="bower_components/Ionicons/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="plugins/iCheck/square/blue.css">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <img style="width:200px" src="imagenes/phptuber.png" alt="">
  </div>
  <!-- /.login-logo -->
  <div class="login-box-body">
    <p class="login-box-msg">Inicia sesión</p>

    <form action="login.php" method="post">
      <div class="form-group has-feedback">
        <input name="email" type="email" class="form-control" placeholder="Email" value="<?php echo $email; ?>">
        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <input name="password" type="password" class="form-control" placeholder="Password">
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>

      <div class="row">
        <div class="col-xs-8">
          <div class="checkbox icheck">
            <label>

            </label>
          </div>
        </div>
        <!-- /.col -->
        <div class="col-xs-4">
          <button type="submit" class="btn btn-primary btn-block btn-flat">Ingresar</button>
        </div>
        <!-- /.col -->
      </div>
      <div style="color:red">
        <?php echo $msg; ?>
      </div>
    </form>

    <a href="#">Olvidé mi clave</a><br>
    <a href="register.php" class="text-center">Registrar una cuenta</a>

  </div>
  <!-- /.login-box-body -->
</div>
<!-- /.login-box -->

<!-- jQuery 3 -->
<script src="bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- iCheck -->
<script src="plugins/iCheck/icheck.min.js"></script>
<script>
  $(function () {
    $('input').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue',
      increaseArea: '20%' /* optional */
    });
  });
</script>
</body>
</html>
