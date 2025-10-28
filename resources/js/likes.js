document.addEventListener('DOMContentLoaded', ()=>{
    const likeWrappers = document.querySelectorAll('[data-like-wrapper]');

    likeWrappers.forEach(wrapper => {
        const button = wrapper.querySelector('[data-like-button]');
        const likesCountSpan = wrapper.querySelector('[data-likes-count]');
        const itemUserId = wrapper.dataset.itemUserId;
        let isLiked = wrapper.dataset.isLiked === 'true';
        
        const updateButtonUI = () =>{
            if (isLiked) {
                button.classList.remove('bg-gray-200', 'text-gray-700', 'hover:bg-gray-300'); // Eliminar estilos de no-likeado
                button.classList.add('bg-red-500', 'text-white', 'hover:bg-red-600'); // Añadir estilos de likeado
                button.innerHTML = '<i class="fas fa-heart"></i> <span data-likes-count>' + likesCountSpan.textContent + '</span>'; // Cambiar icono a sólido
            } else {
                button.classList.remove('bg-red-500', 'text-white', 'hover:bg-red-600'); // Eliminar estilos de likeado
                button.classList.add('bg-gray-200', 'text-gray-700', 'hover:bg-gray-300'); // Añadir estilos de no-likeado
                button.innerHTML = '<i class="far fa-heart"></i> <span data-likes-count>' + likesCountSpan.textContent + '</span>'; // Cambiar icono a contorno
            }
        }

        updateButtonUI();

        button.addEventListener('click', async ()=>{
            button.disabled = true;

            const url = `/api/item-users/${itemUserId}/likes`;
            const method = isLiked ? 'DELETE' : 'POST';

            try {
                const response = await fetch(url, {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        // Envía el token CSRF para seguridad API (importante para POST/DELETE)
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                });

                if (!response.ok) {
                    const errorData = await response.json();
                    if (response.status === 401) {
                        alert('Debes iniciar sesión para dar un Like');
                        window.location.href = '/login';

                    }else{
                        throw new Error(`Error ${response.status}: ${errorData.message || response.statusText}`);
                    }
                }

                const data = await response.json();

                likesCountSpan.textContent = data.likes_count;
                isLiked = data.is_liked;

                updateButtonUI();
            } catch (error) {
                console.error('Error al dar/quitar like: ', error);
                alert(`Ocurrió un error al procesar tu Me Gusta: ${error.message}`);
            }finally{
                button.disabled = false;
            }
        });
    });
});