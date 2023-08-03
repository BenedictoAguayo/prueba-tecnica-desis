
<?php
class Connection
{

        //funcion para obtener los datos de la base de datos
        static function informationDataBase()
        {

                $infoDB = array(
                        "database" => "db_voting",
                        "user" => "root",
                        "pass" => "12345",
                );
                return $infoDB;
        }

        //funcion para conectarnos a la base de datos mediando PDO
        static function connect()
        {
                try {
                        $link = new PDO(
                                "mysql:host=localhost;dbname=" . Connection::informationDataBase()["database"],
                                Connection::informationDataBase()["user"],
                                Connection::informationDataBase()["pass"]
                        );
                        //$link->exec("set names uft8mb4");
                } catch (PDOException $e) {
                        die("Error: " . $e->getMessage());
                }
                return $link;
        }
}
