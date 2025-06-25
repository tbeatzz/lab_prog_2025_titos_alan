let formCategoria = document.forms["formCategoria"];

function load(){
    data = {
        id: document.getElementById("datoId").value
    }

    fetch("http://localhost/lab_prog_2025/test/request/requestLoadTest.php",{
        method: "post",
        headers: {"Content-Type": "application/json", "Accept": "application/json"},
        body: JSON.stringify(data)
    })
    .then(response => {
        if(!response.ok){
            throw new Error(response.status);
        }
        return response.json();
    })
    .then(response => {
        console.log(response);
        formCategoria.datoNombre.value = response.result.nombre;
    })
    .catch(error => {
        console.error("Error de petición", error)
    })
}

function save(e){
    e.preventDefault()
    data = {
        nombre: formCategoria.datoNombre.value
    }
    console.log("Guardando datos...")
    fetch("http://localhost/lab_prog_2025/test/request/requestTest.php",{
        method: "post",
        headers: {"Content-Type": "application/json", "Accept": "application/json"},
        body: JSON.stringify(data)
    })
    .then(response => {
        if(!response.ok){
            throw new Error(response.status);
        }
        return response.json();
    })
    .then(response => {
        console.log(response);
    })
    .catch(error => {
        console.error("Error de petición", error)
    })

}