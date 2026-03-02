<?php
session_start();
require_once("conexion.php");

$mysql = new connection();
$conexion = $mysql->get_connection();

$usuario = $_POST['telefono'];
$contraseña = md5($_POST['contraseña']); 
//$contraseña =$_POST['contraseña'];

try {
    // Llamar al procedimiento almacenado con parámetros de entrada y salida
    $sql = "CALL SPD_VALIDA_INICIAR_SESION(?, ?, @pnIdRol, @pcCadenaPermiso, @pcResultado)";
    $stmt = $conexion->prepare($sql);

    if (!$stmt) {
        throw new Exception("Error al preparar la consulta: " . $conexion->error);
    }

    $stmt->bind_param("ss", $usuario, $contraseña);
    $stmt->execute();
    $stmt->close();


    $resultado_query = $conexion->query("SELECT @pnIdRol AS IdRol, @pcCadenaPermiso AS Permiso, @pcResultado AS Resultado");

    if ($resultado_query) {
        $row = $resultado_query->fetch_assoc();

        $idRol = $row['IdRol'];
        $permiso = $row['Permiso'];
        $respuesta = trim($row['Resultado']);

        // Manejar la respuesta
        if ($respuesta === "Inicio de sesión exitoso") {

            $_SESSION['usuario']=$usuario;
            $_SESSION['IdRol']=$idRol;
            $_SESSION['CadenaPermisos']=$permiso;
            header('Location: ../PHP/Interfaz.php');
        } else {
                session_unset();
                session_destroy();
            echo "<script>
                    alert('Error en el inicio de sesión');
                    window.close();
                </script>";

        }

    } else {
        throw new Exception("Error al obtener el resultado: " . $conexion->error);
    }

} catch (Exception $e) {
    die("Error: " . $e->getMessage());
} finally {
    $conexion->close();
}
?>
