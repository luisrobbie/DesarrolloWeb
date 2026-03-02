<?php
    session_start();

// Verificar sesión
    if (!isset($_SESSION['usuario'])) {
        header("Location: login.php");
        exit();
    }
// Fecha mínima para el datetime-local
    date_default_timezone_set('America/Mexico_City');
    $ahora = date('Y-m-d\TH:i');

    require_once("conexion.php");
    $mysql = new connection();
    $conn = $mysql->get_connection();
    $usuario = $_SESSION['usuario'];

    $sql = "CALL SPD_CONSULTA_DATOS_USUARIO(?)";
    $stmt = $conn->prepare($sql);

        if ($stmt) {
                $stmt->bind_param("s", $usuario);
                $stmt->execute();
                $result = $stmt->get_result();
                $row = $result->fetch_assoc();
                if ($result && $result->num_rows>0) {
                    $nombre_u = htmlspecialchars($row['NombreUsuario']);
                    $nombres = htmlspecialchars($row['Nombre']);
                    $genero = htmlspecialchars($row['Genero']);
                    $telefono= htmlspecialchars($row['NumeroTelefono']);
                    $telefono2= htmlspecialchars($row['NumeroTelefonoAlternativo']);
                    $correo = htmlspecialchars($row['CorreoElectronico']);
                    $fecha = strtotime($row['FechaNacimiento']);
                } else {
                    echo '<option value="" disabled>HUBO UN PROBLEMA AL RECUPERAR SU INFORMACIÓN</option>';
                    exit;
                }
                    $stmt->close();
                    $conn->close();
        } else {
            echo '<option value="" disabled>Error en la consulta</option>';
        }
?> 
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Perfil</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../CSS/Miperfil.css">
</head>
<body>
    <div class="profile-container">
        <div class="profile-header">
            <h1><img src="../img/User_Icon.png" style="height:2vw; margin-right:1vw;">Mi Perfil</h1>
        </div>
        <div class="profile-body">
            <div class="profile-field">
                <div class="field-label">
                    <i class="fas fa-user"></i> Nombre completo:
                </div>
                <div class="field-value">
                    <?php 
                        echo $nombres;
                    ?>
                </div>
            </div>
            
            <div class="profile-field">
                <div class="field-label">
                    <i class="fas fa-venus-mars"></i> Género:
                </div>
                <div class="field-value">
                    <?php 
                        if ($genero == "Femenino" ) {
                            echo '<i class="fas fa-venus" style="color: #ff6b9d;"></i> Femenino';
                        } elseif ($genero == "Masculino") {
                            echo '<i class="fas fa-mars" style="color: #4d8af0;"></i> Masculino';
                        } else {
                            echo '<i class="fas fa-genderless" style="color: #7e57c2;"></i> Otro';
                        }
                    ?>
                </div>
            </div>
            
            <div class="profile-field">
                <div class="field-label">
                    <i class="fas fa-mobile-alt"></i> Teléfono principal:
                </div>
                <div class="field-value"><?php echo $telefono; ?></div>
            </div>
            
            <div class="profile-field">
                <div class="field-label">
                    <i class="fas fa-phone-alt"></i> Teléfono alternativo:
                </div>
                <div class="field-value">
                    <?php echo $telefono2 ? $telefono2 : '<span class="null-value">No especificado</span>'; ?>
                </div>
            </div>
            
            <div class="profile-field">
                <div class="field-label">
                    <i class="fas fa-envelope"></i> Correo electrónico:
                </div>
                <div class="field-value"><?php echo $correo; ?></div>
            </div>
            
            <div class="profile-field">
                <div class="field-label">
                    <i class="fas fa-birthday-cake"></i> Fecha de nacimiento:
                </div>
                <div class="field-value">
                    <?php 
                        if ($fecha) {
                            $fecha_formateada = date('d/m/Y', $fecha);
                            echo htmlspecialchars($fecha_formateada);
                        } else {
                            echo '<span class="null-value">No especificada</span>';
                        }
                    ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>