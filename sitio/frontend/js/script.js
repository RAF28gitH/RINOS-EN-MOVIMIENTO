document.addEventListener("DOMContentLoaded", function() {
    fetch('../backend/check_role.php') 
        .then(response => {
             if (!response.ok) {
                 throw new Error("No se encontró el archivo check_role.php");
             }
             return response.json();
        })
        .then(data => {
            console.log("Respuesta del servidor:", data);

            if (data.rol === 'admin') {
                const menu = document.getElementById('menu-navegacion');
                
                const li = document.createElement('li');
                li.className = 'nav-item';
                
                const a = document.createElement('a');
                a.className = 'nav-link bg-danger text-white';
                a.href = 'usuario/dashboard.php'; 
                a.textContent = 'Controles de Admin';
                li.appendChild(a);
                menu.insertBefore(li, menu.lastElementChild);
            }
        })
        .catch(error => console.error('Error verificando sesión:', error));
});