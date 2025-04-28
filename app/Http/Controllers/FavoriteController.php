<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Favorite;

class FavoriteController extends Controller
{
    // Método para mostrar los favoritos del usuario logueado
    public function show()
    {
        // Obtener el usuario logueado
        $user = Auth::user();

        // Verificar si hay un usuario logueado
        if ($user) {
            // Cargar sus favoritos ordenando de manera descendente
            $user->load(['favorites' => function($query) {
                $query->orderBy('created_at', 'DESC');
            }]);

            return view('favoritos.index', compact('user'));
        } else {
            return redirect()->route('home')->with('error', 'Debes iniciar sesión.');
        }
    }
    public function destroy(Favorite $favorite)
{
    // Eliminamos el favorito 
    $favorite->delete();
    
    // Redireccionamos con mensaje de éxito
    return back()->with('success', 'Favorito eliminado correctamente');
}
    

}
