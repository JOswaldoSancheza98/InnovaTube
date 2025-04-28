// Configuración inicial
document.addEventListener('DOMContentLoaded', function () {
    // Asignar eventos
    document.getElementById('searchButton').addEventListener('click', searchYouTube);
});

// Mostrar carga
function showLoading(title = 'Procesando...') {
    Swal.fire({
        title: title,
        html: 'Por favor espera...',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
}

// Buscar videos en YouTube
async function searchYouTube() {
    const query = document.getElementById('searchQuery').value;
    const videoResults = document.getElementById('videoResults');

    if (!query.trim()) {
        Swal.fire({
            icon: 'warning',
            title: 'Campo vacío',
            text: 'Por favor ingresa un término de búsqueda'
        });
        return;
    }

    showLoading('Buscando videos');

    try {
        const response = await fetch('/buscar-videos', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ query: query })
        });

        const data = await response.json();
        Swal.close();
        renderVideoResults(data);

    } catch (error) {
        handleSearchError(error);
    }
}

// Renderizar resultados de videos
function renderVideoResults(data) {
    const videoResults = document.getElementById('videoResults');
    videoResults.innerHTML = '';

    if (!data.items || data.items.length === 0) {
        videoResults.innerHTML = '<p class="text-muted">No se encontraron videos.</p>';
        Swal.fire({
            icon: 'info',
            title: 'Sin resultados',
            text: 'No se encontraron videos para tu búsqueda'
        });
        return;
    }

    data.items.forEach(item => {
        const videoId = item.id.videoId;
        const title = escapeHtml(item.snippet.title);
        const thumbnail = item.snippet.thumbnails.medium.url;

        const videoCard = createVideoCard(videoId, title, thumbnail);
        videoResults.innerHTML += videoCard;
    });
}

// Crear tarjeta de video
function createVideoCard(videoId, title, thumbnail) {
    return `
    <div class="card mb-3">
        <div class="row g-0">
            <div class="col-md-4">
                <img src="${thumbnail}" class="img-fluid rounded-start" alt="${title}" 
                     style="cursor: pointer;" data-bs-toggle="modal" 
                     data-bs-target="#videoModal" data-video-id="${videoId}">
            </div>
            <div class="col-md-7">
                <div class="card-body">                   
                    <h5 class="card-title">${title}</h5>
                    <button class="btn btn-sm btn-danger" 
                            data-bs-toggle="modal" 
                            data-bs-target="#videoModal"
                            data-video-id="${videoId}">
                        <i class="fab fa-youtube me-1"></i> Ver video
                    </button>
                     <button onclick="agregarAFavoritos('${videoId}', '${title.replace(/'/g, "\\'")}', '${thumbnail}')" 
                        class="btn btn-sm btn-primary">
                    <i class="fas fa-star me-1"></i> Agregar a favoritos
                </button>
                    
                </div>
            </div>
            
        </div>
    </div>`;
}

// Manejar errores de búsqueda
function handleSearchError(error) {
    console.error('Error:', error);
    const videoResults = document.getElementById('videoResults');
    videoResults.innerHTML = '<p class="text-danger">Error al buscar videos.</p>';

    Swal.fire({
        icon: 'error',
        title: 'Error',
        text: 'Ocurrió un error al buscar videos. Por favor intenta nuevamente.'
    });
}

// Escapar HTML
function escapeHtml(unsafe) {
    return unsafe
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");
}

// Agregar a favoritos
window.agregarAFavoritos = function (videoId, title, thumbnail) {
    showLoading('Agregando a favoritos');

    fetch('/añadir-favoritos', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            video_id: videoId,
            title: title,
            thumbnail: thumbnail
        })
    })
        .then(response => response.json())
        .then(data => {
            Swal.fire({
                icon: 'success',
                title: 'Éxito',
                text: data.message || 'Video agregado a favoritos correctamente',
                timer: 3000,
                showConfirmButton: false
            });
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'No se pudo agregar el video a favoritos'
            });
        });
};