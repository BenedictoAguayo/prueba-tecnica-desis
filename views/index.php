<?php
require_once "../models/regions.model.php";
require_once "../models/candidates.model.php";

$candidates = CandidatesModel::getCandidates();
$regions = RegionsModel::getRegions();

?>

<!DOCTYPE html>
<html lang="es">

<head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="views/css/styles.css">
        <title>Sistema de Votación</title>
</head>

<body>
        <section class="container_form">
                <h1>Formulario de Votación</h1>
                <form id="form_voting">
                        <span>
                                <label for="full_name">Nombre completo</label>
                                <input type="text" name="full_name" id="full_name">
                        </span>
                        <span>
                                <label for="alias">Alias</label>
                                <input type="text" name="alias" id="alias">
                        </span>
                        <span>
                                <label for="rut">Rut</label>
                                <input type="text" name="rut" id="rut">
                        </span>
                        <span>
                                <label for="email">Email</label>
                                <input type="text" name="email" id="email">
                        </span>
                        <span>
                                <label for="id_region">Región</label>
                                <select name="id_region" id="id_region">
                                        <option value="0">seleccione</option>
                                        <?php
                                        foreach ($regions as $region) { ?>
                                                <option value='<?= $region["id_region"] ?>'><?= $region['name_region'] ?></option>
                                        <?php      }
                                        ?>
                                </select>
                        </span>
                        <span>
                                <label for="id_comunne">Comuna</label>
                                <select name="id_comunne" id="id_comunne">
                                        <option value="0">seleccione la región</option>
                                </select>
                        </span>
                        <span>
                                <label for="id_cantidate">Candidato</label>
                                <select name="id_cantidate" id="id_cantidate">
                                        <option value="0">seleccione</option>
                                        <?php
                                        foreach ($candidates as $candidate) { ?>
                                                <option value='<?= $candidate["id"] ?>'><?= $candidate['full_name'] ?></option>
                                        <?php } ?>
                                </select>
                        </span>
                        <div>
                                <p>Como se enteró de nosotros</p>
                                <label for="web">
                                        Web
                                        <input type="checkbox" value="Web" name="options[]" id="web">
                                </label>
                                <label for="tv">
                                        TV
                                        <input type="checkbox" value="Tv" name="options[]" id="tv">
                                </label>
                                <label for="redes_sociales">
                                        Redes Sociales
                                        <input type="checkbox" value="Redes Sociales" name="options[]" id="redes_sociales">
                                </label>
                                <label for="amigos">
                                        Amigos
                                        <input type="checkbox" value="Amigos" name="options[]" id="amigos">
                                </label>
                        </div>
                        <span>
                                <button type="submit" value="votar" id="votar">Votar</button>
                        </span>
                </form>
        </section>
        <!-- 
utilizamos todos los CDN de las librerias que utilizamos en el proyecto
aunque no es recomendable utilizar los CDN en un proyecto en produccion ya que si alguno de los CDN falla o se cae el sitio web de la liberia ,no funcionara correctamente 
por eso es mejor descargar los archivos en local y utilizarlos desde el propio servidor

-->

        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.js"></script>
        <script type="module" src="views/js/script.js"></script>
</body>

</html>