function mensaje() {
    alert("¡Gracias por tu interés! Te esperamos en Mi Gym.");
}


function calcular() {
    let selectAlimento = document.getElementById("alimento");
    let alimento = selectAlimento ? selectAlimento.value : "";
    let gramosInput = document.getElementById("gramos");
    let gramos = gramosInput ? parseFloat(gramosInput.value) : 0;

    let datos = {
        "Pechuga de pollo": {
            calorias: 165,
            proteinas: 31,
            carbohidratos: 0,
            grasas: 3.6
        },
        "Arroz": {
            calorias: 130,
            proteinas: 2.7,
            carbohidratos: 28,
            grasas: 0.3
        },
        "Avena": {
            calorias: 389,
            proteinas: 17,
            carbohidratos: 66,
            grasas: 7
        },
        "Huevo": {
            calorias: 155,
            proteinas: 13,
            carbohidratos: 1.1,
            grasas: 11
        },
        "Plátano": {
            calorias: 89,
            proteinas: 1.1,
            carbohidratos: 23,
            grasas: 0.3
        }
    };

    if (!alimento || !datos[alimento]) {
        alert("Por favor, selecciona un alimento de la lista.");
        return;
    }

    if (isNaN(gramos) || gramos <= 0) {
        alert("Introduce una cantidad válida en gramos (mayor que 0).");
        return;
    }

    let alimentoSeleccionado = datos[alimento];

    document.getElementById("calorias").textContent =
        (alimentoSeleccionado.calorias * gramos / 100).toFixed(1);

    document.getElementById("proteinas").textContent =
        (alimentoSeleccionado.proteinas * gramos / 100).toFixed(1);

    document.getElementById("carbohidratos").textContent =
        (alimentoSeleccionado.carbohidratos * gramos / 100).toFixed(1);

    document.getElementById("grasas").textContent =
        (alimentoSeleccionado.grasas * gramos / 100).toFixed(1);
}




function guardarEntrenamiento() {

    let ejercicio = document.getElementById("ejercicio").value;
    let series = document.getElementById("series").value;
    let repeticiones = document.getElementById("repeticiones").value;
    let peso = document.getElementById("peso").value;

    if (ejercicio == "" || series == "" || repeticiones == "" || peso == "") {
        alert("Completa todos los campos.");
        return;
    }

    let tabla = document.getElementById("tablaEntrenamientos");

    let fila = tabla.insertRow();

    fila.insertCell(0).innerHTML = ejercicio;
    fila.insertCell(1).innerHTML = series;
    fila.insertCell(2).innerHTML = repeticiones;
    fila.insertCell(3).innerHTML = peso + " kg";

    document.getElementById("ejercicio").value = "";
    document.getElementById("series").value = "";
    document.getElementById("repeticiones").value = "";
    document.getElementById("peso").value = "";
}