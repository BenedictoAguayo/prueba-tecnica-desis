//importamos varibles y funciones que vamos a utilizar(modulos)
import { URL_API, autoFormatRut, validatorRut } from "./utilities.js";

//seleccionamos los elementos del DOM que vamos a requerir
const formSubmit = document.getElementById("form_voting");
const btnSubmit = document.getElementById("votar");

//desectructuramos los elementos [name] del formulario
const { full_name, alias, rut, email, id_region, id_comunne, id_cantidate } = formSubmit.elements;

//evento para ir formateando el rut a medida que se escribe
rut.addEventListener("keyup", (e) => {
        autoFormatRut(e);
});

//generamos la lista de comunas en base a la region seleccionada
const htmlCommunes = (data) => {
        let html = "";
        data.forEach((commune) => {
                html += `<option value="${commune.id_commune}">${commune.name_commune}</option>`;
        });
        id_comunne.innerHTML = html;
}

//funcion para extraer las comunas desde la BD y generar el html
const findCommunes = (idRegion) => {

        fetch(`${URL_API}communes.controller.php?id_region=${idRegion}`)
                .then((response) => response.json())
                .then(response => {
                        if (response.status == 200) {
                                htmlCommunes(response.results);
                        } else {
                                console.log(response);
                        }
                })
                .catch((error) => console.log(error))
                .finally(() => console.log("fetch finalizado"));
}

//evento para ir detectando el cambio de region y generar las comunas
id_region.addEventListener("change", (e) => {
        let id = e.target.value;

        if (id == 0) {
                id_comunne.innerHTML = `<option value="0">Seleccione una Región</option>`;
                return;
        }
        findCommunes(id);
});

//evento de jqueryValidate para validar que se seleccione una region
$.validator.addMethod("validator_region", function (value, element) {
        return value != 0;
}, "Por favor seleccione una región")

//evento de jqueryValidate para validar que se seleccione una comuna
$.validator.addMethod("validator_commune", function (value, element) {
        return value != 0;
}, "Por favor seleccione una comuna")

//evento de jqueryValidate para validar que se seleccione un candidato
$.validator.addMethod("validator_candidate", function (value, element) {
        return value != 0;
}, "Por favor seleccione un candidato");

//evento de jqueryValidate para validar que el ALIAS tenga numeros y letras
$.validator.addMethod('alphanumeric', function (value, element) {

        //valimos con regex
        return this.optional(element) || /^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]+$/.test(value);
}, 'La contraseña debe contener al menos una letra y un número.');

$.validator.addMethod("rut", function (value, element) {
        let rut = validatorRut(value);
        return rut.isValid;
}, "El rut ingresado no es correcto");


//evento de jqueryValidate para validar que se seleccione al menos 2 opciones del checkbox
$.validator.addMethod('min_checked', function (value, element) {
        return $(element).closest('form').find('input[name="options[]"]:checked').length > 1;
}, 'Selecciona al menos 2 opciones.');

//evento de jqueryValidate para hacer las validaciones de los campos del formulario
$().ready(function () {
        $('#form_voting').validate({

                //reglas de validacion para cada campo
                rules: {
                        full_name: {
                                required: true,
                        },
                        alias: {
                                required: true,
                                minlength: 5,
                                alphanumeric: true
                        },
                        rut: {
                                required: true,
                                minlength: 3,
                                maxlength: 12,
                                rut: true
                        },
                        email: {
                                required: true,
                                email: true

                        },
                        id_region: {
                                required: true,
                                validator_region: true
                        },
                        id_comunne: {
                                required: true,
                                validator_commune: true
                        },
                        id_cantidate: {
                                required: true,
                                validator_candidate: true
                        },
                        'options[]': {
                                min_checked: true
                        }
                },

                //mensajes de error cuando no se cumple la validacion
                messages: {
                        full_name: {
                                required: "Por favor ingrese su nombre completo",
                        },
                        alias: {
                                required: "Por favor ingrese su alias",
                                minlength: "El alias debe tener al menos 3 caracteres",
                                pattern: "El alias debe tener al menos 1 número y 1 letra"
                        },
                        rut: {
                                required: "Por favor ingrese su rut",
                                minlength: "El rut debe tener al menos 3 caracteres",
                                maxlength: "El rut ingresado no es correcto"
                        },
                        email: {
                                required: "Por favor ingrese su email",
                                email: "El email ingresado no es correcto"
                        },
                        id_region: {
                                validator_region: "Por favor seleccione una región"
                        },
                        id_comunne: {
                                validator_commune: "Por favor seleccione una comuna"
                        },
                        id_cantidate: {
                                validator_candidate: "Por favor seleccione un candidato"
                        },
                        'options[]': {
                                min_checked: "Por favor seleccione al menos 2 opciones"
                        }

                },
                //evento de submit para enviar los datos del formulario a la API y guardarlos en la BD
                submitHandler: function (form) {

                        //desabilitamos el boton de submit para evitar que se envie mas de una vez
                        btnSubmit.disabled = true;
                        btnSubmit.style.fontSize = ".9em";
                        //creamos un loader mientras carga la peticion
                        btnSubmit.innerHTML = `Guardando.... <div class="loader"></div>`;

                        //creamos un objeto FormData para enviar los datos del formulario
                        let data = new FormData(form);
                        const valuesChecked = $("input[name='options[]']:checked").map(function () {
                                return this.value;
                        }).get().join(",");

                        data.append("found_out_by", valuesChecked);
                        data.delete("options[]");
                        fetch(`${URL_API}voting.controller.php`, {
                                method: 'POST',
                                body: data
                        }).then(response => response.json())
                                .then(response => {
                                        //si la peticion es correcta mostramos un mensaje de exito o error

                                        if (response.status == 201) {
                                                Swal.fire({
                                                        position: 'top-end',
                                                        icon: 'success',
                                                        title: 'Voto registrado correctamente',
                                                        showConfirmButton: false,
                                                        timer: 1500
                                                })
                                                //reseteamos el formulario
                                                form.reset();
                                        } else if (response.status == 400) {
                                                Swal.fire({
                                                        icon: 'error',
                                                        title: 'Rut ya registrado',
                                                        text: response.message,
                                                })
                                                return;
                                        } else {
                                                Swal.fire({
                                                        icon: 'error',
                                                        title: 'Oops...',
                                                        text: 'Algo salio mal!, intenta nuevamente',
                                                })
                                        }
                                }).finally(() => {

                                        //habilitamos el boton de submit, y cambiamos el texto
                                        btnSubmit.style.fontSize = "1.1em";
                                        btnSubmit.disabled = false;
                                        btnSubmit.innerHTML = `Votar`;

                                        console.log("fetch finalizado")
                                })

                }
        });

});
