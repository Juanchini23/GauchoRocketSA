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

function getHoraFinal(cuenta) {
    if (cuenta < 24) {
        return cuenta;
    } else if (cuenta >= 23 && cuenta < 48) {
        return cuenta - 24;
    } else if (cuenta >= 48) {
        return cuenta - 48;
    }
}

$(document).ready(function () {
    $("#destino").change(function (e) {
        let destino = $("#destino").val();
        let horaSalida = $("#horaSalida").html();
        let horaDestino = getHoraLlegada(destino);
        let cuenta = parseInt(horaSalida) + parseInt(horaDestino);
        let calculoHoraLLegada = getHoraFinal(cuenta);
        $("#horaLlegada").empty();
        $("#horaLlegada").text(calculoHoraLLegada);
    })

    $("#form").submit((e)=>{
        let horaLlegada = $("#horaLlegada").html();
        $("#llegadaHora").val=5;
    })
});


