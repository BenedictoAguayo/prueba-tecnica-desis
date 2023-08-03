<?php
require_once "connection.php";

class CommunesModel
{

        //funcion pora obtener las comunas que recibe el id de la region y retornarlas en un array asociativo
        static function findCommunes($id_region)
        {

                $sql = "SELECT * FROM communes WHERE id_region = :id_region";
                $stmt = Connection::connect()->prepare($sql);
                $stmt->bindParam(":id_region", $id_region, PDO::PARAM_INT);
                $stmt->execute();
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
}
