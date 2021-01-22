<?php
//se prepara variable para guardar posibles mensajes de respuesta
$msg="";

//se crean las variables para guardar datos del usuario a crear.
//estas variables también servirán para repoblar los formularios.
$email="";
$password="";
$repite_password="";


if( isset($_POST['email']) && isset($_POST['password']) && isset($_POST['repite_password']) && isset($_POST['de_acuerdo']) ) {

  if ($_POST['email']==""){
    $msg.= "Debe ingresar un email <br>";
  }

  if ($_POST['password'] ==""){
    $msg.="Debe ingresar una clave <br>";
  }

  if ($_POST['repite_password'] ==""){
    $msg.="Debe repetir la clave <br>";
  }

  $email = strip_tags($_POST['email']);
  $password = strip_tags($_POST['password']);
  $repite_password = strip_tags($_POST['repite_password']);

  if ($password != $repite_password){
    $msg.="Las claves no coinciden <br>";
  }else if (strlen($password)<8){
    $msg.="La clave debe tener al menos 8 caracteres <br>";
  }else{
    //momento de conectarnos a db
    $mysqli = mysqli_connect("localhost","root","","phptube");

    if ($mysqli==false){
      echo "Hubo un problema al conectarse a María DB";
      die();
    }

    $ip = $_SERVER['REMOTE_ADDR'];

    //aquí como todo estuvo OK, resta controlar que no exista previamente el mail ingresado en la tabla users.
    $resultado = $mysqli->query("SELECT * FROM `usuarios` WHERE `usuarios_email` = '".$email."' ");
    $usuarios = $resultado->fetch_all(MYSQLI_ASSOC);

    //cuento cuantos elementos tiene $tabla,
    $cantidad = count($usuarios);

    //solo si no hay un usuario con mismo mail, procedemos a insertar fila con nuevo usuario
    if ($cantidad == 0){
      $password = sha1($password); //encriptar clave con sha1
      $mysqli->query("INSERT INTO `usuarios` (`usuarios_email`, `usuarios_password`, `usuarios_ip`) VALUES ('".$email."', '".$password."', '".$ip."');");
      $msg.="Usuario creado correctamente, ingrese haciendo  <a href='login.php'>clic aquí</a> <br>";
    }else{
      $msg.="El mail ingresado ya existe <br>";
    }

  }


}

?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>AdminLTE 2 | Registration Page</title>
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
<body class="hold-transition register-page">
<div class="register-box">
  <div class="login-logo">
    <img style="width:200px" src="imagenes/phptuber.png" alt="">
  </div>

  <div class="register-box-body">
    <p class="login-box-msg">Unete a Fisinova</p>

    <form action="register.php" method="post">
      <div class="form-group has-feedback">
        <input name="email" type="email" class="form-control" placeholder="Email" value="<?php echo $email; ?>">
        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
      </div>

      <div class="form-group has-feedback">
        <input name="password" type="password" class="form-control" placeholder="Password" >
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>

      <div class="form-group has-feedback">
        <input name="repite_password" type="password" class="form-control" placeholder="Retype password">
        <span class="glyphicon glyphicon-log-in form-control-feedback"></span>
      </div>

      <div class="row">
        <div class="col-xs-8">
          <div class="checkbox icheck">
            <label>
              <input name="de_acuerdo" type="checkbox" required> Acepto <a href="https://google.com">términos y condiciones</a>
            </label>
          </div>
        </div>
        <!-- /.col -->
        <div class="col-xs-4">
          <button type="submit" class="btn btn-primary btn-block btn-flat">Registrar</button>
        </div>
        <!-- /.col -->
      </div>
      <div style="color:red">
        <?php echo $msg; ?>
      </div>

    </form>



    <a href="login.php" class="text-center">Ya tengo cuenta...</a>
  </div>
  <!-- /.form-box -->
</div>
<!-- /.register-box -->

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
