<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agendar cita</title>
    <link rel="stylesheet" href="../CSS/Menu.css">
    <link rel="stylesheet" href="../CSS/Tabla.css">
    <script src="../javascript/validacion.js"></script>
    <script src="../javascript/ventanas.js"></script>
</head>

<body>

<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>

    <?php session_start() ?>

    <div class="titulo" style="margin:0%;">
        <img src="../img/Calendar_Icon.png" height="35px" style="margin-right:20px;">
        <h2>Administrar citas</h2>
    </div>

    <?php 

    require_once("conexion.php");
    $mysql = new connection();
    $conn = $mysql->get_connection();
    $id_psicologo =$_SESSION['usuario'];  //modificar si la sesión para psicólogo es diferente


    $sql = "CALL SPD_CONSULTA_PACIENTES(?)";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
      throw new Exception("Error al preparar: " . $conn->error);
    }else{
      //echo "Conexion Exitosa --->";
    }
    //echo $id_psicologo;
    $stmt->bind_param("s", $id_psicologo);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
      echo "No hay citas";
    }

    ?>

   <!--  echo '<div class="citas-formato">';-->
    <table class="citas-table">
      <thead>
        <tr>
          <th>Fecha</th>
          <th>Paciente</th>
          <th>Motivo</th>
          <th>Estatus</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
    <?php
    while ($row = $result->fetch_assoc()) {
        $date     = htmlspecialchars($row['FechaConsulta']);
        $paciente = htmlspecialchars($row['NombrePaciente']);
        $motivo   = htmlspecialchars($row['Motivo']);
        $status   = htmlspecialchars($row['Estatus']);
        $estatus = $row['IdEstatus'];

        echo '<tr>';
          echo '<td>' . $date . '</td>';
          echo '<td>' . $paciente . '</td>';
          echo '<td>' . $motivo . '</td>';
          echo '<td>' . $status . '</td>';
          echo '<td>';
          if ($estatus == 1) {   ?>
              <form method="post" action="aceptarCita.php">
                <input type="hidden" name="id_psicologo" value="<?php echo $id_psicologo; ?>">
                <input type="hidden" name="fecha_consulta" value="<?php echo $date; ?>">
                <button type="submit" name="accion" value=1>Aceptar</button>
                <button type="submit" name="accion" value=0>Rechazar</button>
              </form>
          <?php    
          }else {
              echo 'Sin acciones';
          }
          echo '</td>';
        echo '</tr>';
    }
    
    ?>

      </tbody>
    </table>