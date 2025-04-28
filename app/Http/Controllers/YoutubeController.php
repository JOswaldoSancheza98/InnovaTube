<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


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

    public function addToFavorites(Request $request)
    {
        // Verificar que el usuario esté autenticado
        if (!Auth::check()) {
            return response()->json(['error' => 'Usuario no autenticado'], 401);
        }

        // Obtener los datos del video de la solicitud
        $userId = Auth::id();  // Obtener el ID del usuario autenticado
        $videoId = $request->input('video_id');
        $title = $request->input('title');
        $thumbnail = $request->input('thumbnail');

        try {
            // Verificar si el video ya está en favoritos
            $existingFavorite = DB::table('favorites')
                ->where('user_id', $userId)
                ->where('video_id', $videoId)
                ->exists();

            if ($existingFavorite) {
                return response()->json(['message' => 'Este video ya está en tus favoritos.']);
            }

            // Insertar el video en la tabla de favoritos
            DB::table('favorites')->insert([
                'user_id' => $userId,
                'video_id' => $videoId,
                'title' => $title,
                'thumbnail' => $thumbnail,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            return response()->json(['message' => 'Video agregado a favoritos']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Ocurrió un error al agregar el video a favoritos', 'details' => $e->getMessage()], 500);
        }
    }

    public function showFavorites($userId)
    {
        // Encontrar al usuario por ID y cargar sus favoritos
        $user = User::with('favorites')->find($userId);

        // Verifica si el usuario existe
        if ($user) {
            return view('user.favorites', compact('user'));
        } else {
            return redirect()->route('home')->with('error', 'Usuario no encontrado.');
        }
    }
}


