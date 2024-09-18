document.addEventListener("DOMContentLoaded", function() {
    const btnMenu = document.getElementById('btn-menu');
    const menuLateral = document.getElementById('menu-lateral');

    btnMenu.addEventListener('click', function() {
        if (menuLateral.classList.contains('mostrar')) {
            menuLateral.classList.remove('mostrar');
        } else {
            menuLateral.classList.add('mostrar');
        }
    });
});
