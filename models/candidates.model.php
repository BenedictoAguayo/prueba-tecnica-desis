<?php

require_once 'connection.php';

class CandidatesModel
{

        //funcion para buscar todos los candidatos
        static function getCandidates()
        {
                $sql = "SELECT * FROM candidates";
                $stmt = Connection::connect()->prepare($sql);
                $stmt->execute();
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
}
