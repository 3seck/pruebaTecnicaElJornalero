<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\User;

class CharacterController extends Controller
{

    
    public function getCharacters(Request $request)
    {
        // Obtener los filtros desde la solicitud
        $query = [
            'name' => $request->input('name'),
            'status' => $request->input('status'),
            'species' => $request->input('species'),
            'gender' => $request->input('gender'),
            'page' => $request->input('page', 1),
        ];

        // Limpiar la consulta quitando los valores nulos
        $query = array_filter($query);

        try {
            // Hacer la solicitud a la api con los filtros y la paginación
            $response = Http::get('https://rickandmortyapi.com/api/character', $query);

            // Verifica si la respuesta fue correcta
            if ($response->successful()) {
                $data = $response->json();

                // Manejo de casos donde no se encuentran resultados
                if (empty($data['results'])) {
                    return view('characters.characters', [
                        'characters' => [],
                        'info' => null,
                        'request' => $request,
                        'error' => 'No se encontraron personajes con los criterios de búsqueda.',
                    ]);
                }

                $characters = $data['results'];
                $info = $data['info']; // Información de paginación (páginas, next, prev)

                return view('characters.characters', compact('characters', 'info', 'request'));
            } else {
                // Manejo de errores en la respuesta de la API
                return view('characters.characters', [
                    'characters' => [],
                    'info' => null,
                    'request' => $request,
                    'error' => 'Hubo un problema al obtener los datos de la API.',
                ]);
            }
        } catch (\Exception $e) {
            // Manejo de excepciones (por ejemplo, si la API no está disponible)
            return view('characters.characters', [
                'characters' => [],
                'info' => null,
                'request' => $request,
                'error' => 'Error de conexión con la API.',
            ]);
        }
    }


    public function getCharacterById($id)
    {
        try {
            $response = Http::get("https://rickandmortyapi.com/api/character/{$id}");

            if ($response->failed()) {
                throw new \Exception('No se pudo obtener el personaje de la API.');
            }

            $character = $response->json();

            // Verifica si el personaje si etaen los favoritos del usuario
            $isFavorite = null;
            if (auth()->check()) {
                $user = auth()->user();
                $isFavorite = $user->favorites()->where('character_id', $id)->first();
            }

            return view('characters.character', compact('character', 'isFavorite'));
        } catch (\Exception $e) {
            return redirect('/characters')->with('error', 'Hubo un problema al obtener el personaje: ' . $e->getMessage());
        }
    }


    public function saveFavorite(Request $request)
    {
        try {
            $request->validate(['character_id' => 'required|integer']);

            /** @var \App\Models\User $user */
            $user = auth()->user();

            // Verificar si el personaje ya esta en los favoritos del user
            $existingFavorite = Favorite::where('user_id', $user->id)
                ->where('character_id', $request->character_id)
                ->first();

            if ($existingFavorite) {
                return redirect('/characters')->with('warning', 'Este personaje ya está en tus favoritos.');
            }

            // Si no esta en favoritos, crear el nuevo registro
            Favorite::create([
                'user_id' => $user->id,
                'character_id' => $request->character_id,
            ]);

            return redirect('/characters')->with('success', 'Añadido a favoritos');
        } catch (\Exception $e) {
            return redirect('/characters')->with('error', 'Hubo un problema al guardar en favoritos: ' . $e->getMessage());
        }
    }


    public function getFavorites()
    {
        try {
            /** @var \App\Models\User $user */
            $user = auth()->user();

            // Obtener los registros favoritos del usuario
            $favorites = $user->favorites;

            $characters = [];
            foreach ($favorites as $favorite) {
                $response = Http::get("https://rickandmortyapi.com/api/character/{$favorite->character_id}");

                if ($response->failed()) {
                    throw new \Exception("No se pudo obtener detalles del personaje con ID: {$favorite->character_id}");
                }

                $character = $response->json();  
                $character['favorite_id'] = $favorite->id;
                $characters[] = $character;
            }

            return view('favorites.favorites', compact('characters'));
        } catch (\Exception $e) {
            return redirect('/characters')->with('error', 'Hubo un problema al obtener los favoritos: ' . $e->getMessage());
        }
    }


    public function deleteFavorite($id)
    {
        try {
            /** @var \App\Models\User $user */
            $user = auth()->user();

            // Buscar y eliminar el favorito
            $favorite = Favorite::where('id', $id)->where('user_id', $user->id)->firstOrFail();
            $favorite->delete();

            return redirect('/favorites')->with('success', 'Eliminado de favoritos');
        } catch (\Exception $e) {
            return redirect('/favorites')->with('error', 'Hubo un problema al eliminar el favorito: ' . $e->getMessage());
        }
    }
}
