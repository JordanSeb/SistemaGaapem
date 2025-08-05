var modal = document.getElementById("myModal");
var span = document.getElementsByClassName("close")[0];
var confirmBtn = document.getElementById("confirmBtn");
var cancelBtn = document.getElementById("cancelBtn");

function confirmDelete(alumno, id) {
    document.getElementById("modalText").innerHTML = "¿Estás seguro de que quieres eliminar a " + alumno + "?";
    modal.style.display = "block";

    confirmBtn.onclick = function() {
        modal.style.display = "none";
        deleteAlumno(id); // Llamar a la función deleteAlumno cuando se confirma
    }
}

function deleteAlumno(id) {
    fetch('/SistemaGaapem/api-rest/alumnos/eliminar_alumno.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ id: id })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            location.reload();
        } else {
            alert("Error: " + data.error);
        }
    })
    .catch(error => {
        console.error('Error al eliminar alumno:', error);
        alert("Error al conectar con el servidor");
    });
}


cancelBtn.onclick = function() {
    modal.style.display = "none";
}

window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}

