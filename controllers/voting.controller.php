
<?php
header('content-type: application/json; charset=utf-8');

require_once "../models/voting.model.php";


//funcion para sanitizar y validar los datos tanto de GET como de POST
function validationInputs($data)
{
        $validated_data = array();

        //recorremos el array y sanitizamos y validamos  los datos
        foreach ($data as $key => $value) {

                //si el valor es un numero
                if (is_numeric($value)) {
                        $validated_data[$key] = (int) $value;
                        continue; // Convertir a entero

                        //si el valor es una cadena de texto
                } elseif (is_string($value)) {

                        //si el valor es un email
                        if (filter_var($value, FILTER_VALIDATE_EMAIL)) {
                                $validated_data[$key] = filter_var(trim($value), FILTER_SANITIZE_EMAIL); // Sanitizar el email
                                continue;
                        } else {
                                //si el valor es una cadena de texto
                                $validated_data[$key] = htmlspecialchars(trim($value)); // Sanitizar la cadena de texto
                                continue;
                        }
                } else {
                        //si no es un número ni una cadena de texto, agregar null,para evitar inyecciones de algun tipo de codigo malicioso
                        $validated_data[$key] = null;
                        continue;
                }
        }
        //retornamos el array con los datos sanitizados y validados
        return $validated_data;
}

if ($_SERVER['REQUEST_METHOD'] ===  'POST') {


        //recorremos el array y sanitizamos y validamos  los datos
        $data = validationInputs($_POST);

        //validamos que los datos no esten vacios
        if (empty($data['full_name']) || empty($data['alias']) || empty($data['rut']) || empty($data['email']) || empty($data['id_region']) || empty($data['id_comunne']) || empty($data['id_cantidate'])) {
                echo json_encode(
                        array(
                                'status' => 404,
                                'message' => 'Todos los campos son obligatorios',
                        )
                );
                return;
        }

        //eliminamos puntos y guiones del rut
        $rut = preg_replace("/[-.]/", "", $data['rut']);

        //lo guardamos nuevamente ya formateado
        $data['rut'] = substr($rut, 0, -1) . '-' . substr($rut, -1);

        //validamos que el rut no exista en la base de datos
        $response = VotingModel::findRut($data['rut']);

        //si el rut existe en la base de datos retornamos un mensaje de error
        if (!empty($response)) {
                echo json_encode(
                        array(
                                'status' => 400,
                                'message' => 'el Rut ingresado ya existe',
                        )
                );
                return;
        }

        //boramos elname option del array, ya que lo utilizamos solo para concatenar valores
        //si el rut no existe en la base de datos, guardamos los datos
        $response = VotingModel::postData($data);

        //retornamos el mensaje de error o de exito
        echo json_encode($response);
} else {
        //si no es un metodo post retornamos un mensaje de error
        echo json_encode(array('status' => 404, 'message' => 'Not Found'));
}
