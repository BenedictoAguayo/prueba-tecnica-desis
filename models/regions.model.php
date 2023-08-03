<?php

require_once "connection.php";

class RegionsModel
{

        //funcion pora obtener las regiones y retornarlas en un array asociativo
        static function getRegions()
        {
                $sql = "SELECT * FROM regions";
                $stmt = Connection::connect()->prepare($sql);
                $stmt->execute();
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
}
