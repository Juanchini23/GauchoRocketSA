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

function getHoraFinal(horaSalida, horaDestino) {
    if (((horaSalida + horaDestino) >= 23) && ((horaSalida + horaDestino) < 48)) {
        console.log("entre 1")
        return horaLlegadaFinal = (horaSalida + horaDestino) - 24;
    }
    if ((horaSalida + horaDestino) >= 48) {
        console.log("entre 2")
        return horaLlegadaFinal = (horaSalida + horaDestino) - 48;
    }
    if ((horaSalida + horaDestino) < 24) {
        console.log("entre 3")
        return (horaSalida + horaDestino);
    }

}

$(document).ready(function () {
    $("#destino").change(function (e) {
        let destino = $("#destino").val();
        let horaSalida = $("#horaSalida").html();
        let horaDestino = getHoraLlegada(destino);
        console.log(horaDestino);
        let calculoHoraLLegada = getHoraFinal(horaSalida, horaDestino);
        console.log(calculoHoraLLegada);


    })
});