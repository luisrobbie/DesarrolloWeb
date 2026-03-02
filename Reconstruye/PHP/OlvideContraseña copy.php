
<?php
require_once("conexion.php");

$mysql = new connection();
$conexion = $mysql->get_connection();

$usuario = $_POST['telefono'];
$id = $_POST['pregunta'];
$respuesta = $_POST['respuesta'];
$contraseña = md5($_POST['contraseña']); // Se mantiene MD5 por fines didácticos
$resultado1=21;
$resultado2="";


try {
    // Llamar al procedimiento almacenado de verificación
    $sql = "CALL SPD_COMPROBAR_PREGUNTA(?, ?, ?, @pcResultado)";
    $stmt = $conexion->prepare($sql);

    if (!$stmt) {
        throw new Exception("Error al preparar la consulta: " . $conexion->error);
    }

    $stmt->bind_param("sis", $usuario, $id, $respuesta);
    $stmt->execute();
    $stmt->close();

    // Obtener el resultado de la verificación
    $resultado_query = $conexion->query("SELECT @pcResultado AS SALIDA");

    if ($resultado_query) {
        $row = $resultado_query->fetch_assoc();
        $resultado1 = intval(trim($row['SALIDA']));
        echo "<p><strong>Valor Retornado Por La Base:</strong> $resultado1</p>";

        // Si los datos son correctos, actualizar la contraseña
        if ($resultado1=== 1) {

            $sql2 = "CALL SPD_ACTUALIZAR_CONTRASEÑA(?, ?, @pcResultado)";
            $stmt2 = $conexion->prepare($sql2);

            if (!$stmt2) {
                throw new Exception("Error al preparar la consulta de actualización: " . $conexion->error);
            }

            $stmt2->bind_param("ss" , $usuario, $contraseña);
            $stmt2->execute();
            $stmt2->close();

            $resultado_update = $conexion->query("SELECT @pcResultado AS SALIDA2");
            if ($resultado_update) {
                $row_update = $resultado_update->fetch_assoc();
                $resultado2= trim($row_update['SALIDA2']);
                echo $resultado2;
                if ($resultado2=== "La contraseña se cambio con éxito") {
                    echo "<script>
                    alert('se Cambio La contraseña Correctamente');
                    window.close();
                    </script>";
                } else {
                    echo "<script>
                    alert('No se pudo cambiar la contraseña, intente de nuevo');
                    </script>";
                    header('Location: ../Html/OlvidoContrasena.html');
                }
            } else {
                throw new Exception("Error al obtener el resultado de actualización: " . $conexion->error);
            }
        } else {
            header('Location: ../Html/OlvidoContrasena.html');
        }
    } else {
        throw new Exception("Error al obtener el resultado de verificación: " . $conexion->error);
    }
} catch (Exception $e) {
    die("Error: " . $e->getMessage());
} finally {
    $conexion->close();
}

?>