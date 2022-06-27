let circuitoUnoBA = {"Tierra": 0, "EEI": 4, "HotelOrbital": 8, "Luna": 16, "Marte": 26};
let circuitoUnoAA = {"Tierra": 0, "EEI": 3, "HotelOrbital": 6, "Luna": 9, "Marte": 22};
let circuitoDosBA = {"Tierra": 0, "EEI": 4, "Luna": 14, "Marte": 26, "Ganimedes": 48, "Europa": 50, "Io": 51, "Encedalo": 70, "Titan": 77};
let circuitoDosAA = {"Tierra": 0, "EEI": 3, "Luna": 10, "Marte": 22, "Ganimedes": 32, "Europa": 33, "Io": 35, "Encedalo": 50, "Titan": 52};

function getHora(tipoCircuito, tipoAceleracion, destino) {
    let calculo = 0;
    if (tipoCircuito == 'EntreDestinosUno') {
        if (tipoAceleracion == 'BA') {
            for (let value in circuitoUnoBA) {
                if (value == destino) {
                    return calculo = circuitoUnoBA[value]
                }
            }
            return undefined;
        } else if (tipoAceleracion == 'AA') {
            for (let value in circuitoUnoAA) {
                if (value == destino) {
                    return calculo = circuitoUnoAA[value]
                }
            }
            return undefined;
        }
    } else if (tipoCircuito == 'EntreDestinosDos') {
        if (tipoAceleracion == 'BA') {
            for (let value in circuitoDosBA) {
                if (value == destino) {
                    return calculo = circuitoDosBA[value]
                }
            }
            return undefined;
        } else if (tipoAceleracion == 'AA') {
            for (let value in circuitoDosAA) {
                if (value == destino) {
                    return calculo = circuitoDosAA[value]
                }
            }
            return undefined;
        }
    }
}

function getHoraLlegada(cuenta) {
    if (cuenta < 24) {
        return cuenta;
    } else if (cuenta >= 23 && cuenta < 48) {
        return cuenta - 24;
    } else if (cuenta >= 48) {
        return cuenta - 48;
    }
}

function getDiaLLegada(cuenta, diaSalida) {
    if (cuenta < 24) {
        return diaSalida;
    } else if (cuenta >= 23 && cuenta < 48) {
        switch (diaSalida) {
            case "Lunes":
                return "Martes";
            case "Martes":
                return "Miercoles";
            case "Miercoles":
                return "Jueves";
            case "Jueves":
                return "Viernes";
            case "Viernes":
                return "Sabado";
            case "Sabado":
                return "Domingo";
            case "Domindo":
                return "Lunes";
        }
    } else if (cuenta >= 48) {
        switch (diaSalida) {
            case "Lunes":
                return "Miercoles";
            case "Martes":
                return "Jueves";
            case "Miercoles":
                return "Viernes";
            case "Jueves":
                return "Sabado";
            case "Viernes":
                return "Domingo";
            case "Sabado":
                return "Lunes";
            case "Domindo":
                return "Martes";
        }
    }
}

function setHoraLlegada(calculoHoraLLegada) {
    $("#horaLlegada").empty();
    $("#horaLlegada").text(calculoHoraLLegada);
    $("#llegadaHora").empty();
    $("#llegadaHora").append(`<input type="number" name="llegadaHora" value="${calculoHoraLLegada}">`);
}

function setDiaLLegada(diaLlegada, diaSalida) {
    $("#diaLlegada").empty();
    $("#diaLlegada").text(diaLlegada);
}

function setMensajeError() {
    $("#diaLlegada").empty();
    $("#diaLlegada").text('Destino incorrecto');
    $("#horaLlegada").empty();
    $("#horaLlegada").text('-');
}


    let tipoCircuito = $("#tipoVuelo").html();
    console.log(tipoCircuito);
    let tipoAceleracion = $("#tipoAceleracion").html();
    $("#destino").change(function (e) {
        let diaSalida = $("#diaSalida").html();
        let destino = $("#destino").val();
        let horaSalida = $("#horaSalida").html();
        let horaDestino = getHora(tipoCircuito, tipoAceleracion, destino);
        if(horaDestino != undefined){
            let cuenta = parseInt(horaSalida) + parseInt(horaDestino);
            let calculoHoraLLegada = getHoraLlegada(cuenta);
            let diaLlegada = getDiaLLegada(cuenta, diaSalida);
            setHoraLlegada(calculoHoraLLegada);
            setDiaLLegada(diaLlegada, diaSalida);
        } else setMensajeError();
    })
