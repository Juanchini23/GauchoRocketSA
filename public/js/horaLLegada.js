let circuitoUnoBA = {"Tierra": 0, "EEI": 4, "HotelOrbital": 8, "Luna": 16, "Marte": 26};
let cuitoUnoAA = {"Tierra": 0, "EEI": 3, "HotelOrbital": 6, "Luna": 9, "Marte": 22};

function getHoraLlegada(destino) {
    let calculo = 0;
    switch (destino) {
        case "Tierra":
            calculo = 0;
            calculo = circuitoUnoBA.Tierra;
            break
        case "EEI":
            calculo = 0;
            calculo = circuitoUnoBA.EEI;
            break
        case "HotelOrbital":
            calculo = 0;
            calculo = circuitoUnoBA.HotelOrbital;
            break
        case "Luna":
            calculo = 0;
            calculo = circuitoUnoBA.Luna;
            break
        case "Marte":
            calculo = 0;
            calculo = circuitoUnoBA.Marte;
            break
    }
    return calculo
}

function getHoraFinal(cuenta, diaSalida, diaLlegada) {
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

$(document).ready(function () {
    $("#destino").change(function (e) {
        let diaSalida = $("#diaSalida").html();
        let destino = $("#destino").val();
        let horaSalida = $("#horaSalida").html();
        let horaDestino = getHoraLlegada(destino);
        let cuenta = parseInt(horaSalida) + parseInt(horaDestino);
        let calculoHoraLLegada = getHoraFinal(cuenta);
        let diaLlegada = getDiaLLegada(cuenta, diaSalida);

        $("#horaLlegada").empty();
        $("#horaLlegada").text(calculoHoraLLegada);
        $("#llegadaHora").empty();
        $("#llegadaHora").append(`<input type="number" name="llegadaHora" value="${calculoHoraLLegada}">`);

        $("#diaLlegada").empty();
        $("#diaLlegada").text(diaLlegada);
        console.log(diaLlegada);
        console.log(diaSalida);
    })


});


