<?php
session_start();

$Cadena= $_SESSION['CadenaPermisos'];

$permisos = str_split($Cadena); 
$opciones = [
    "Mi Perfil",                                        //0
    "Agendar",                                        //1
    "Agendar",                                          //2
    "Reagendar citas",                                        //3
    "Ver citas",                                        //4
    "Citas",                                       //5
    "Pacientes",                                        //6
    "Aceptar o rechazar",                                  //7
    "Ver citas",                                                 //8
    "Administrar",                                       //9
    "Registrar Psicólogo",                              //9.1 (10)
    "Eliminar Psicólogo"                                 //9.2 (10)
];
$enlaces = [
    "MiPerfil.php",                                     //0
    "",//No lleva link pues solo es la opcion CONSULTAS  //1
    "Agendar.php",                                       //2
    "Reagendar.php",                                     //3
    "VerCitas.php",                                      //4
    "", //No lleva link pues solo es la opcion Psicologos//5
    "Pacientes.php",                                     //6
    "CitasPsicologo.php",                                //7
    "VerCitas.php",                                     //8
    "",//No lleva link pues solo es la opcion   Admin    //9
        "RegistroPsicologo.php",                        //9.1 (10)
        "Eliminar Psicólogo"                            //9.2 (10)
];
?>

<!DOCTYPE html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="viewport" content="initial-scale=1, maximum-scale=1">
    <title>Reconstruye </title> 
    <meta name="description" content="En Reconstruye, consultorio de atención psicológica, ofrecemos un espacio seguro y 
    confidencial para acompañarte en tu proceso de crecimiento personal y emocional. Contamos con especialistas altamente capacitados que te ayudarán 
    a afrontar desafíos como ansiedad, depresión, estrés, y conflictos personales o familiares. Nuestro enfoque es personalizado, combinando técnicas 
    terapéuticas efectivas para ayudarte a encontrar equilibrio, bienestar y una mejor calidad de vida. Estamos aquí para escucharte y apoyarte en cada
    paso del camino hacia tu salud mental">
    <link rel="icon" href="../img/Icono.png" type="image/gif"/>
    <meta name="author" content="Reconstruye">
    <script>
                window.moveTo(0, 0);
                window.resizeTo(screen.width, screen.height);
    </script>
    <script src="../javascript/MenuDinamico.js"></script>
    <script src="../javascript/Frames.js"></script>
    <link rel="stylesheet" href="../CSS/Interfaz.css">
</head>

<body>
    <!--Aqui Inicia el encabezado de la página-->
        <header>
            <div class="titulo" id="paginaPrincipal">
                <div class="Enc">
                    <img src="../img/Icono.png">
                <div class="Logo"></div>
                </div>
            </div>
            <div class="Barra">
                <ul>
                    <li><img src='../img/Home_icon.png'><a href="../Html/index2.html" >Página Principal</a></li>
                    <?php
                        if ($permisos[0] == "1") {
                                echo "<li onclick=\"abrirEnIframe_Ocultar('$enlaces[0]')\">
                                <img src='../img/Perfil_Icon.png'>$opciones[0]
                                </li>";                               
                            }
                        if ($permisos[1] == "1") {
                                echo "<li onclick=\"MenuConsultas('Consultas','Psicologos')\">
                                <img src='../img/info_icon.png'>$opciones[1]
                                </li>";
                            }
                            if($permisos[5]=='1'){   
                                echo "<li onclick=\"MenuPsicologos('Consultas','Psicologos')\">
                                <img src='../img/Lines_Icon.png'>$opciones[5]
                                </li>";
                            }
                            if($permisos[6]=='1'){   
                                echo "<li onclick=\"abrirEnIframe_Ocultar('$enlaces[6]')\">
                                <img src='../img/Perfil_Icon.png'>$opciones[6]
                                </li>";  
                            }
                            if($permisos[9]=='1'){
                                echo "<li onclick=\"MenuAdmin('Consultas','Psicologos','$enlaces[9]')\">
                                <img src='../img/Admin_Icon.png'>$opciones[9]
                                </li>";
                            }
                            
                    ?>
                    <li><img src="../img/Salir.png"><a href="CerrarSesion.php">Salir</a></li>
                </ul>
            </div>
        </header>
    <!--Aqui termina el encabezado de la página-->
        <main>
            
            <div class="Container" id="Container">
            <div class="Menu" id="Menu">
                <ul id="ListaMenu"style="display: none;">
                    <div class="Consultas" id="Consultas">
                        <?php
                        if($permisos[2]=='1' && $permisos[8]!='1'){
                                echo "<li onclick=\"abrirEnIframe('$enlaces[2]')\">
                                <img src='../img/Calendar2_icon.png'>$opciones[2]
                                </li>";
                            }
                            if($permisos[3]=='1' && $permisos[8]!='1'){
                                echo "<li onclick=\"abrirEnIframe('$enlaces[3]')\">
                                <img src='../img/Calendar_Icon.png'>$opciones[3]
                                </li>";
                            }
                            if($permisos[4]=='1' &&  $permisos[8]=='1'){
                                echo "<li onclick=\"abrirEnIframe('AgendarPaciente.php')\">
                                <img src='../img/Calendar_Icon.png'>Agendar Pacientes
                                </li>";
                            }
                            if($permisos[4]=='1'  && $permisos[8]!='1' ){
                                echo "<li onclick=\"abrirEnIframe('$enlaces[4]')\">
                                <img src='../img/Ojos_Icon.png'>$opciones[4]
                                </li>";
                                echo "<li onclick=\"abrirEnIframe('VerCitasCalendario.php')\">
                                <img src='../img/Calendar_Icon.png'> Calendario de citas
                                </li>";
                            }

                            ?> 
                    </div>
                    <div class="Psicologos" id="Psicologos">
                        <?php
                            if($permisos[7]=='1'){
                                echo "<li onclick=\"abrirEnIframe('$enlaces[7]')\">
                                <img src='../img/Datos_Icon.png'>$opciones[7]
                                </li>";
                            }
                            if($permisos[8]=='1'){
                                echo "<li onclick=\"abrirEnIframe('$enlaces[8]')\">
                                <img src='../img/Ojos_Icon.png'>$opciones[8]
                                </li>";

                                echo "<li onclick=\"abrirEnIframe('VerCitasCalendario.php')\">
                                <img src='../img/Calendar_Icon.png'> Calendario de citas
                                </li>";
                            }
                        ?>
                    </div>
                    <div class="Admin" id="Admin">
                        <?php
                        if($permisos[9]=='1'){
                                echo "<li onclick=\"abrirEnIframe('$enlaces[10]')\">
                                <img src='../img/add_Icon.png'>$opciones[10]
                                </li>";
                            }
                        ?>
                    </div>
                </ul>
            </div>
            <div class="Info">
                <iframe src="../Html/Principal.html" id="Frame"></iframe>
            </div>
        </div>
    


        </main>
    <footer>
        <div class="InfoExtra" >
            <p>Horarios: 9:00 A.M - 11:00 P.M</p>
        </div>
        <div class="Pie" id="Contacto">
            <ul>
                <li><img src="../img/Phone_Icon.png" > Tel:+1 775 268 2367</li>
                <li><img src="../img/mail.png"><a href="mailto:reconstruyesss@gmail.com">Correo: reconstruyesss@gmail.com</a></li>
                <li><img src="../img/Facebook_con.png"><a href="https://www.facebook.com/profile.php?id=100087628274036&sk=about">Facebook</a></li>
                <li><img src="../img/Locate_Icon.png" ><a href="https://maps.app.goo.gl/PiQ1fWPPXtciJ6DE8">Pte., #409, Int.3, Tulancingo, Mexico</a> </li>
            </ul>
        </div>
        
    </footer>
    
</body>
