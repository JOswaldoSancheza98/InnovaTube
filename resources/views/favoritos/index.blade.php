@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0">{{ __('Mis Favoritos') }}</h3>
                </div>

                <div class="card-body" style="height: 700px; overflow-y: auto;">
                    @if($user->favorites->isEmpty())
                    <div class="alert alert-info">
                        {{ __('Aún no tienes videos favoritos.') }}
                    </div>
                    @else
                    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-3">
                        @foreach($user->favorites as $favorite)
                        <div class="col">
                            <div class="card h-100">
                                <img src="{{ $favorite->thumbnail }}" class="card-img-top img-fluid"
                                    alt="{{ $favorite->title }}" style="cursor: pointer;" data-bs-toggle="modal"
                                    data-bs-target="#videoModal" data-video-id="{{ $favorite->video_id }}">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $favorite->title }}</h5>
                                </div>
                                <div class="card-footer bg-transparent border-0 d-flex justify-content-between">
                                    <button class="btn btn-sm btn-danger play-video" data-bs-toggle="modal"
                                        data-bs-target="#videoModal" data-video-id="{{ $favorite->video_id }}">
                                        <i class="fab fa-youtube me-1"></i> Ver
                                    </button>
                                    <form action="{{ route('favorites.destroy', $favorite->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger delete-favorite">
                                            <i class="fas fa-trash-alt"></i> Eliminar
                                        </button>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>



@include('partials.youtube-modal')
<script src="{{ asset( 'js/confirmDelation.js') }}"></script>

@endsection