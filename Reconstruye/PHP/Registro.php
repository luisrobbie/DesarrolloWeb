<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Cuenta</title>
    <link rel="stylesheet" href="../CSS/Forms.css">
    <style>
        .Formulario {
            transition: opacity 0.5s ease-in-out;
            width: 100%;
        }
        .hidden {
            display: none;
        }
    </style>
    <script src="../javascript/ValidacionLogin.js"></script>
    
    <script src="../javascript/FormularioDinamico.js"></script>
</head>

<body>
    <div class="titulo" style="height:30vw;">
        <img src="../img/newUser_Icon.png" height="35px" style="margin-right:20px;">
        <h2>¡Regístrate!</h2>
    </div>
    <div class="Formulario" style="min-height: 17vw;">

<div class="mensaje">
<?php
session_start();

if (isset($_SESSION['Registro_Respuesta']) && $_SESSION['Registro_Respuesta'] != "") {
    $msj = $_SESSION['Registro_Respuesta'];

    echo "<script>
        alert('" . htmlspecialchars($msj) . "');
    </script>";

    if ($msj == "Se ha insertado al usuario exitosamente") {
        echo "<script>window.close();</script>";
    }

    $_SESSION['Registro_Respuesta'] = "";
}
?>
</div>
        
        <form id="Form" name="Registro" method="post" action="CrearUsuario.php">
            <div class="1raParte" id="1raParte">
            <div class="item">
                <label for="nombre">Nombre(s):</label>
                <input type="text" id="Nombre" name="Nombres" required placeholder="Escriba su nombre" minlength="3" maxlength="50" >
            </div>
            <div class="item">
                <label for="apellidoP">Primer Apellido:</label>
                <input type="text" id="Primer_Apellido" name="Primer_Apellido" required placeholder="Escriba su apellido paterno" minlength="3" maxlength="50">
            </div>
            <div class="item">
                <label for="apellidoM">Segundo Apellido:</label>
                <input type="text" id="Segundo_Apellido" name="Segundo_Apellido" placeholder="Escriba su apellido materno" minlength="3" maxlength="50">
            </div>
            <div class="item">
                <label for="Genero">Genero:</label>
                <select id="genero"  name="genero" class="selector"  required>
                    <option value="N" disabled selected>Seleccione una opción</option>
                    <option value="masculino">Masculino</option>
                    <option value="femenino">Femenino</option>
                    <option value="Otro">Otro</option>
                </select>
            </div>
            <div class="item">
                <label for="fecha">Fecha de nacimiento:</label>
                <input type="date" id="fecha" name="fecha" required placeholder="Escriba su fecha de nacimiento" style="color: rgb(116, 116, 116);">
            </div> 
            <div class="item">
                <label for="correo">Correo:</label>
                <input type="email" id="correo" name="correo" required placeholder="Escriba su correo">
            </div>
            <div class="item">
                <label for="telefono">Teléfono:</label>
                <input type="tel" id="telefono" name="telefono" required placeholder="Escriba su teléfono celular" pattern="[0-9]{10}">
            </div>
            <div class="item">
                <label for="telefono2">Teléfono alternativo(Opcional):</label>
                <input type="tel" id="telefono2" name="telefono2"  placeholder="Escriba su  segundo teléfono" pattern="[0-9]{10}">
            </div>
            <div class="botones">
                <button type="button" onclick="cambiarFormulario('2daParte','1raParte')" class="boton">siguiente</button>
            </div>
        </div>
        <div class="2daParte hidden" id="2daParte">
            <div class="item">
                <div class="item">
                    <label for="contraseña">Escriba su contraseña:</label>
                    <input type="password" id="contraseña" name="contraseña"  required placeholder="contraseña" minlength="10" maxlength="50">
                </div>

                <p>Si pierde la contraseña, se le presentara una pregunta de seguridad para recuperarla.</p>
                <br>
                <label for="pregunta">Seleccione una pregunta de seguridad:</label>
                <select id="pregunta"  name="pregunta" class="selector"  required>
                    <option value="0" disabled selected>Seleccione una opción</option>
                    <option value="1">¿Cúal es el nombre de tu mascota favorita?</option>
                    <option value="2">¿Cuál es el nombre de tu mejor amigo?</option>
                    <option value="3">¿En que ciudad nacio tu madre?</option>
                    <option value="4">¿Cúal es el nombre de tu primer escuela?</option>
                    <option value="5">¿Cúal es el nombre de tu abuelo materno?</option>
                </select>
                <div class="item">
                    <label for="respuesta">Escriba su respuesta:</label>
                    <input type="text" id="respuesta" name="respuesta"  required placeholder="respuesta" minlength="3" maxlength="50">
                </div>
            </div>
            <div class="botones">
                <button type="button" onclick="cambiarFormulario('1raParte','2daParte')" class="boton">Volver</button>
                <input type="submit" value="Enviar" class="boton">
            </div>
        </div> 
        </form>

    </div>
</body>

</html>
