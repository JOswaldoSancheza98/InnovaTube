@extends('layouts.app') {{-- Extiende del layout principal --}}

@section('content') {{-- Sección de contenido --}}
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card"> {{-- Tarjeta de Bootstrap --}}
                <div class="card-header">
                    <h3>{{ __('Buscador de videos') }}</h3>
                </div> {{-- Encabezado del dashboard --}}

                <div class="card-body"> {{-- Cuerpo de la tarjeta --}}
                    @if (session('status'))
                    {{-- Si hay un mensaje de sesión (por ejemplo, "Usuario logueado correctamente") --}}
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }} {{-- Muestra el mensaje --}}
                    </div>
                    @endif
                    <div class="mb-3">
                        <input type="text" id="searchQuery" class="form-control" placeholder="Buscar videos...">
                        {{-- Input para buscar --}}
                    </div>

                    <button class="btn btn-primary" onclick="searchYouTube()">Buscar</button>
                    {{-- Botón que dispara la función de búsqueda --}}
                    <hr> {{-- Línea divisoria --}}


                    <div class="mt-4" id="videoResults"></div>
                    {{-- Contenedor donde se van a mostrar los resultados de los videos --}}
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function searchYouTube() {
    const query = document.getElementById('searchQuery').value; // Obtiene el término a buscar
    const videoResults = document.getElementById('videoResults'); // Div de resultados

    videoResults.innerHTML = 'Buscando...'; // Mensaje de carga

    // Hace un POST al controlador
    fetch('/buscar-videos', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}' // Importante para seguridad en Laravel
            },
            body: JSON.stringify({
                query: query
            })
        })
        .then(response => response.json())
        .then(data => {
            videoResults.innerHTML = ''; // Limpia resultados anteriores

            if (!data.items) { // Si no hay items
                videoResults.innerHTML = 'No se encontraron videos.';
                return;
            }

            data.items.forEach(item => {
                const videoId = item.id.videoId;
                const title = item.snippet.title;
                const thumbnail = item.snippet.thumbnails.medium.url;

                const videoCard = `
                <div class="card mb-3">
                    <div class="row g-0">
                        <div class="col-md-4">
                            <img src="${thumbnail}" name="thumbnail" class="img-fluid rounded-start" alt="${title}">
                        </div>
                        <div class="col-md-7">
                            <div class="card-body">                   
                                    <h5 class="card-title">${title}</h5>
                                    <a href="https://www.youtube.com/watch?v=${videoId}" target="_blank" class="btn btn-sm btn-danger">Ver en YouTube</a>
                            </div>
                        </div>
                        <div class="col-md-1">
                                <button onclick="agregarAFavoritos('${videoId}', '${title}', '${thumbnail}')" class="btn btn-sm btn-primary">Agregar a Favoritos</button>
                        </div>
                        
                    </div>
                </div>
            `;

                videoResults.innerHTML += videoCard;
            });
        })
        .catch(error => {
            console.error('Error:', error);
            videoResults.innerHTML = 'Error al buscar videos.';
        });
}
</script>


@endsection {{-- Fin de la sección de contenido --}}