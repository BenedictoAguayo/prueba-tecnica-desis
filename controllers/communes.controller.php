<?php
header('content-type: application/json; charset=utf-8');

require_once "../models/communes.model.php";


//verificamos que el metodo de la peticion sea GET de lo contrario retornamos un error
if ($_SERVER['REQUEST_METHOD'] ===  'GET') {

        //verificamos que el id de la region exista y sea un numero
        if (isset($_GET['id_region']) && is_numeric($_GET['id_region'])) {

                $id_region = trim(filter_var($_GET['id_region'], FILTER_SANITIZE_NUMBER_INT));
                
                //llamamos a la funcion findCommunes y le pasamos el id de la region
                $response = CommunesModel::findCommunes($id_region);

                //si el response retorna vacio retornamos un error
                if (empty($response)) {
                        echo json_encode(array('status' => 404, 'results' => 'Comunas no encontradas'));
                        return;
                }

                //retornamos el resultado de la consulta
                echo json_encode(
                        array(
                                'status' => 200,
                                'results' => $response
                        )
                );
                return;
        } else {
                echo json_encode(array('status' => 404, 'message' => 'Not Found'));
        }
} else {
        echo json_encode(array('status' => 404, 'message' => 'Not Found'));
}
