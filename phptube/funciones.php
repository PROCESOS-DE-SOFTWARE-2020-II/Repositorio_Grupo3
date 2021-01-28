<?php

$mysqli = mysqli_connect("localhost","root","","phptube");

if ($mysqli==false){
  echo "Hubo un problema al conectarse a María DB";
  die();
}

function obtiene_videos(){
  $mysqli = $GLOBALS['mysqli'];

  $resultado = $mysqli->query("SELECT * FROM `usuarios_y_videos` WHERE 1 ORDER BY `videos_id` DESC");
  $videos = $resultado->fetch_all(MYSQLI_ASSOC);

  //echo "<pre>";
  //print_r($videos);
  //die();

  return $videos;

}

function graba_video($archivo){
  $mysqli = $GLOBALS['mysqli'];
  $msg = "";

  $target_dir = "archivos/";
  $target_file = $target_dir . basename($archivo["archivo"]["name"]);
  $uploadOk = 1;
  $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));


  // ya hay una archivo que existe con ese nombre?
  if (file_exists($target_file)) {
    $msg .= "El video ya existe. <br>";
    $uploadOk = 1;
  }

  // Tamaño máximo de la imagen
  if ($archivo["archivo"]["size"] > 500000000) {
    $msg .= "Lo siento, el archivo es muy grande.<br>";
    $uploadOk = 0;
  }

  // Formatos autorizados
  if($imageFileType != "mp4" ) {
    $msg .= "Lo siento solo mp4 está permitido. <br>";
    $uploadOk = 0;
  }

  // Si upload ok es 0 entonces hubo un error
  if ($uploadOk == 0) {
    $msg .= "Lo siento el video no puedo subirse.<br>";
    // si todo está bien guardamos la imagen
  } else {
    if (move_uploaded_file($archivo["archivo"]["tmp_name"], $target_file)) {
      $msg .= "El video  ". basename($archivo["archivo"]["name"]). " ha sido subido.";
      $mysqli->query("INSERT INTO `videos` (`videos_url`, `videos_usuario_id`) VALUES ('".$target_file."', '".$_SESSION['usuarios_id']."');");
    } else {
      $msg .= "Lo siento, hubo un error a la hora de grabar en disco el video.<br>";
    }
  }

  return $msg;

}



function graba_imagen($archivo){
  $mysqli = $GLOBALS['mysqli'];

  $msg = "";

  $target_dir = "archivos/";
  $target_file = $target_dir . basename($archivo["archivo"]["name"]);
  $uploadOk = 1;
  $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));


  // ya hay una archivo que existe con ese nombre?
  if (file_exists($target_file)) {
    $msg .= "La imagen ya existe. <br>";
    $uploadOk = 1;
  }

  // Tamaño máximo de la imagen
  if ($archivo["archivo"]["size"] > 500000) {
    $msg .= "Lo siento, el archivo es muy grande.<br>";
    $uploadOk = 0;
  }

  // Formatos autorizados
  if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
  && $imageFileType != "gif" ) {
    $msg .= "Lo siento solo JPG, JPEG, PNG & GIF está permitido. <br>";
    $uploadOk = 0;
  }

  // Si upload ok es 0 entonces hubo un error
  if ($uploadOk == 0) {
    $msg .= "Lo siento la imagen no puedo subirse.<br>";
    // si todo está bien guardamos la imagen
  } else {
    if (move_uploaded_file($archivo["archivo"]["tmp_name"], $target_file)) {
      $msg .= "La imagen  ". basename($archivo["archivo"]["name"]). " ha sido subida.";
      $mysqli->query("UPDATE `usuarios` SET `usuarios_imagen`= '".$target_file."' WHERE `usuarios_id` = '".$_SESSION['usuarios_id']."' ");
    } else {
      $msg .= "Lo siento, hubo un error a la hora de grabar en disco la imagen.<br>";
    }
  }

  return $msg;

}



function obtener_imagen_usuario(){
  //traemos la conexión (global) a un ambito local (dentro de la función);
  $mysqli = $GLOBALS['mysqli'];

  // consulta para traer los promedios peso maximo y la cuenta
  $consulta = "SELECT `usuarios_imagen` FROM `usuarios` WHERE `usuarios_id` = '".$_SESSION['usuarios_id']."'";
  $resultado = $mysqli->query($consulta);
  $fila = $resultado->fetch_assoc();

  $ruta = $fila['usuarios_imagen'];
  return $ruta;
}


/* para crear un usuario nuevo con clave para podernos conectar desde el exterior
GRANT ALL PRIVILEGES ON *.* TO 'USERNAME'@'%' IDENTIFIED BY 'PASSWORD' WITH GRANT OPTION;
*/

?>
