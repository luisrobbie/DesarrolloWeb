<?php

class connection{
    private $conn;
    public function __construct(){
        $this->conn =new mysqli("reconstruye.ddns.net","IvanLoco","S0yBienL0c0","DB_RECONSTRUYE",3308);
        if (!$this->conn) {
            //echo "Falló la conexión <br>";
            die("0" . mysqli_connect_error());
        } else {
            //echo "Exito al conectar con la base!!! -->";
        }
    }
    public function get_connection(){
        return $this->conn;
    }
}

?>