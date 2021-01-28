<?php
include("con_db.php");

if(isset($_POST['enviar'])){
    if (strlen ($_POST['nombre']) >= 1 && strlen($_POST['email']) >= 1) {
        $nombre = trim($_POST['nombre']);
        $email = trim($_POST['email']);
        $fechareg=date("d/m/y"); 
        $consulta = "INSERT INTO datos(nombre, email, fecha_reg) VALUES ('$nombre','$email','$fechareg')";
        $resultado = mysqli_query($conex,$consulta);
        if($resultado){
            ?>
            <h3 class="ok">Te has inscrito correctamente</h3>
            <?php
        }else {
            ?>
            <h3 class="bad">Ups, ha ocurrido un error</h3>
            <?php
        }

    }else{
        ?>
        <h3 class="bad">Por favor complete los campos</h3>
        <?php
    }
}



?>