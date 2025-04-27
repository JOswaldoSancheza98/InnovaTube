<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class YoutubeController extends Controller
{
    public function buscar(Request $request){

    
    $request->validate([
        'query'=> 'required|string|max:255'
    ]);

     // Obtener API Key del .env
     $apiKey = env('YOUTUBE_API_KEY');

      // Obtener el texto que escribió el usuario
      $query = $request->input('query');

      // Llamar a la API de YouTube usando Laravel Http Client
      $response = Http::get('https://www.googleapis.com/youtube/v3/search', [
          'part' => 'snippet',
          'q' => $query,
          'key' => $apiKey,
          'maxResults' => 10,
          'type' => 'video',
      ]);
       // Retorno de respuesta JSON de YouTube
       return response()->json($response->json());
    }
}

