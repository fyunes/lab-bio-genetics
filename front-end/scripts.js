document.addEventListener('DOMContentLoaded', function () {
    const userIcon = document.querySelector('.user-icon');
    const userMenu = document.querySelector('.user-menu');

    // Abrir y cerrar el menú al hacer clic en el ícono de usuario
    userIcon.addEventListener('click', function () {
        userMenu.classList.toggle('active');
    });

    // Cerrar el menú al hacer clic fuera de él
    window.addEventListener('click', function (event) {
        if (!userMenu.contains(event.target)) {
            userMenu.classList.remove('active');
        }
    });
});

document.getElementById('upload-btn').addEventListener('click', function() {
    document.getElementById('orden-medica').click();
});

document.getElementById('appointment-form').addEventListener('submit', function(event) {
    event.preventDefault();
    alert('Turno agendado con éxito!');
});

function redirectTo(page) {
    window.location.href = page;
}