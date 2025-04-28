@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card h-100">
                <div class="card-header">
                    <h3>{{ __('Buscador de videos') }}</h3>
                </div>

                <div class="card-body" style="height: 700px; overflow-y: auto;">
                    @if (session('status'))
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            Swal.fire({
                                icon: 'success',
                                title: 'Éxito',
                                text: '{{ session('status') }}',
                                timer: 3000,
                                showConfirmButton: false
                            });
                        });
                    </script>
                    @endif
                    <div class="mb-3">
                        <input type="text" id="searchQuery" class="form-control" placeholder="Buscar videos...">
                    </div>

                    <button class="btn btn-primary" id="searchButton">Buscar</button>
                    <hr>

                    <div class="mt-4" id="videoResults">
                        
                        <!-- aqui se motrara la lista de resultados -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('partials.youtube-modal')

    <script src="{{ asset('js/buscador.js') }}"></script>

@endsection