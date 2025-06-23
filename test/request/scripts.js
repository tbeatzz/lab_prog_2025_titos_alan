let formCategoria = document.forms["formCategoria"];

function save(e){
    e.preventDefault();

    data={
        nombre: formCategoria.datoNombre.value,
    }

    console.log("hola");  

    fetch("http://localhost/lab_prog_2025_titos_alan/test/request/index.php",{
        method: "post",
        headers:{"COntnt-Type": "application/json", "Accpet": "application/json"},
        body: JSON.stringify(data)
    })
    .then(response => {
        if(!response.ok){
            throw new Error(response.status);
        }
        return response.json;
    })
    .then (response => {
        console.log(response);
    })
    .catch(error=> {
        console.error('erro de la peticion', error)
    })
}