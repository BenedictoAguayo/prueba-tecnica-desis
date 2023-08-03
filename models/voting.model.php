<?php
//requerimos el archivo connection.php
require_once 'connection.php';

class VotingModel
{

        //funcion para buscar el rut en la base de datos
        static function  findRut($rut)
        {
                //guardamos la consulta sql en la varible $sql con un marcador de posicion para evitar inyecciones de codigo malicioso
                $sql = "SELECT * FROM voting WHERE rut = :rut";

                //preparamos la consulta
                $stmt = Connection::connect()->prepare($sql);

                //asignamos el valor al marcador de posicion
                $stmt->bindParam(":rut", $rut, PDO::PARAM_STR);

                //ejecutamos la consulta
                $stmt->execute();
                //retornamos el resultado de la consulta en un array asociativo
                return $stmt->fetch(PDO::FETCH_ASSOC);
        }

        static function postData($data)
        {

                //variables iniciales para guardar los datos separados por comas
                //el siguiente codigo se puede pasar a una funcion y se puede reutilizar en todas las inserciones
                $columns = "";
                $params = "";

                //recorre el array y guardamos los datos en las variables
                foreach ($data as $key => $value) {
                        $columns .= $key . ",";
                        $params .= ":" . $key . ",";
                }

                //eliminamos la ultima coma de cada variable
                $columns = substr($columns, 0, -1);
                $params = substr($params, 0, -1);

                //guardamos la consulta sql en la varible $sql
                $sql = "INSERT INTO voting ($columns) VALUES ($params)";

                //preparamos la consulta
                $link = Connection::connect();
                $stmt = $link->prepare($sql);

                //recorremos el array y guardamos los datos en la consulta
                foreach ($data as $key => $value) {
                        $stmt->bindParam(":" . $key, $data[$key], PDO::PARAM_STR);
                }

                //ejecutamos la consulta
                if ($stmt->execute()) {
                        //retornamos el ultimo id ingresado y un mensaje de exito
                        $response = array(
                                "status" => 201,
                                "lastId" => $link->lastInsertId(),
                                "comment" => "datos ingresados correctamente"
                        );
                        return $response;
                } else {
                        //retornamos un mensaje de error si no se ha guadar los datos
                        return array(
                                'status' => 500,
                                'message' => 'Error al guardar los datos',
                                'error' => $link->errorInfo()
                        );
                        return $link->errorInfo();
                }
        }
}
