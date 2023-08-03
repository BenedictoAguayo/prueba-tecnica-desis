//constantes de la url de la api
export const URL_API = 'controllers/';

//funcion para validar que el rut sea correcto(Chile)
export const validatorRut = (value) => {

        let rutV = value.substring(0, value.length - 1).replace(/\D/g, "");
        let digitoV = value.substring(value.length - 1);
        let arrayRut = rutV.split("").reverse();
        let acum = 0;
        let mult = 2;
        for (let num of arrayRut) {
                acum += parseInt(num) * mult;
                mult++;

                if (mult == 8) {
                        mult = 2;
                }
        }
        let dv = 11 - (acum % 11);

        if (dv == 11) {
                dv = "0";
        }
        if (dv == 10) {
                dv = "k";
        }

        let rutFormat = (Intl.NumberFormat("es-CL").format(rutV) + '-' + digitoV) === '0-' ? '' : Intl.NumberFormat("es-CL").format(rutV) + '-' + digitoV
        let rut = {
                format: rutFormat,
                isValid: digitoV.toLowerCase() == dv
        }

        //retornamos el rut ya formateado y si es valido o no en un objeto para manipular los datos de manera mas eficaz
        return rut;
}

//funcion para formatear el rut a medida que se escribe/no es validacion
export const autoFormatRut = (event) => {
        let value = event.target.value;

        let newText = value.replace(/[.-]/g, "");
        let lastDigit = newText.substring(newText.length - 1);
        let number = newText.substring(0, newText.length - 1);
        if (number.length > 0) {
                // Formatear el número como miles en el formato de Chile
                const formattedValue = parseInt(number).toLocaleString("es-CL");

                // Verificar si el guión al final del valor del campo de entrada existe
                let finalValue = formattedValue + lastDigit;
                if (finalValue.slice(-2, -1) !== "-") {
                        finalValue = finalValue.slice(0, -1) + "-" + finalValue.slice(-1);
                }

                // Actualizar el valor del campo de entrada
                event.target.value = finalValue;
        } else {
                // Si el valor actual no tiene números, actualizar el valor del campo de entrada con el último carácter ingresado
                event.target.value = lastDigit;
        }
}
