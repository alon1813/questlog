// Importa axios si no está globalmente en window.axios
// import axios from 'axios'; 

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-like-wrapper]').forEach(wrapper => {
        const button = wrapper.querySelector('[data-like-button]');
        if (!button) return; // Si no hay botón, no hacer nada

        const likesCountSpan = wrapper.querySelector('[data-likes-count]');
        const itemUserId = wrapper.dataset.itemUserId;
        let isLiked = wrapper.dataset.isLiked === 'true';
        
        const updateButtonUI = () => {
            if (!likesCountSpan) return; // Seguridad
            
            if (isLiked) {
                button.classList.remove('bg-gray-200', 'text-gray-700', 'hover:bg-gray-300');
                button.classList.add('bg-red-500', 'text-white', 'hover:bg-red-600');
                button.innerHTML = `<i class="fas fa-heart"></i> <span data-likes-count>${likesCountSpan.textContent}</span>`;
            } else {
                button.classList.remove('bg-red-500', 'text-white', 'hover:bg-red-600');
                button.classList.add('bg-gray-200', 'text-gray-700', 'hover:bg-gray-300');
                button.innerHTML = `<i class="far fa-heart"></i> <span data-likes-count>${likesCountSpan.textContent}</span>`;
            }
        };

        updateButtonUI(); // Llama al cargar

        button.addEventListener('click', async () => {
            button.disabled = true;
            const url = `/api/item-users/${itemUserId}/likes`;
            const method = isLiked ? 'delete' : 'post'; // Axios usa 'delete', no 'DELETE'

            try {
                const response = await axios({
                    method: method,
                    url: url,
                    headers: { 'Accept': 'application/json' },
                    // No es necesario X-XSRF-TOKEN aquí si está en bootstrap.js
                });

                const data = response.data;
                likesCountSpan.textContent = data.likes_count;
                isLiked = data.is_liked;
                updateButtonUI();

            } catch (error) {
                console.error('[Likes.js] Error en la petición de like:', error);
                if (error.response) { // ESTO ES CLAVE: ENTRA AQUÍ SOLO SI HAY error.response
                    console.error('[Likes.js] Respuesta de error de la API:', error.response.status, error.response.data);
                    if (error.response.status === 401) { // Y LUEGO AQUÍ SI EL STATUS ES 401
                        alert('Debes iniciar sesión para dar "Me Gusta".');
                        window.location.href = '/login';
                    } else if (error.response.data && error.response.data.message) {
                        alert(error.response.data.message);
                    } else {
                        alert('Ocurrió un error inesperado al procesar tu "Me Gusta".');
                    }
                } else {
                    alert('No se pudo conectar con el servidor.');
                }
            } finally {
                button.disabled = false;
            }
        });
    });
});