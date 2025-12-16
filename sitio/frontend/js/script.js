document.addEventListener("DOMContentLoaded", function() {
    
    // 1. Verifica que esta ruta sea correcta según tus carpetas
    fetch('../backend/check_role.php') 
        .then(response => {
             // Es bueno agregar esto para detectar si la ruta está mal
             if (!response.ok) {
                 throw new Error("No se encontró el archivo check_role.php");
             }
             return response.json();
        })
        .then(data => {
            console.log("Respuesta del servidor:", data); // Esto te ayudará a depurar en la consola (F12)

            if (data.rol === 'admin') {
                const menu = document.getElementById('menu-navegacion');
                
                const li = document.createElement('li');
                li.className = 'nav-item';
                
                const a = document.createElement('a');
                a.className = 'nav-link bg-danger text-white'; 
                // Asegúrate de que este archivo exista, en tus archivos subidos se llama 'dashboard.php'
                a.href = 'usuario/dashboard.php'; 
                a.textContent = 'Controles de Admin';
                
                // --- AQUÍ ESTABA EL ERROR ---
                // Metemos el enlace (a) dentro del elemento de lista (li)
                li.appendChild(a); 
                // ----------------------------

                // Ahora sí insertamos el li (que ya trae el enlace adentro) en el menú
                menu.insertBefore(li, menu.lastElementChild);
            }
        })
        .catch(error => console.error('Error verificando sesión:', error));
});