<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateGenreRequest;
use App\Http\Resources\GameResource;
use App\Models\Developer;
use App\Models\Game;
use App\Models\Genre;
use App\Models\Platform;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class GamesController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function getExternalData()
    {

        $client = new Client();
        try {
            DB::table('genres')->truncate();
            DB::table('platforms')->truncate();
            DB::table('developers')->truncate();
            DB::table('games')->truncate();

            $this->getExternalGenres($client);
            $this->getExternalPlatforms($client);
            $this->getExternalDevelopers($client);
            $this->getExternalGames($client);
            
        } catch (\Exception $e) {
            Log::error('Error fetching genres from RAWG API: ' . $e->getMessage());
            throw $e; // Relanza la excepción para que la transacción haga rollback
        }

        return response()->json([
            'genres' => Genre::all(),
            'platforms' => Platform::all(),
            'developers' => Developer::all(),
            'games' => Game::all(),
            // Incluye otros tipos de datos en la respuesta si es necesario
        ], 201);
    }

    public function getExternalGenres(Client $client)
    {
        $apiKey = config('services.rawg.key');
        $url = "https://api.rawg.io/api/genres?key={$apiKey}";

        do {
            try {
                $response = $client->get($url);
                $datos = json_decode($response->getBody(), true);

                foreach ($datos['results'] as $genre) {
                    Genre::create([
                        'id' => $genre['id'],
                        'name' => $genre['name'],
                        'slug' => $genre['slug'],
                        'image_background' => $genre['image_background'] ? $genre['image_background'] : ''
                    ]);
                }

                $url = $datos['next']; // Actualizar la URL para la próxima petición

            } catch (\Exception $e) {
                Log::error('Error fetching genres from RAWG API: ' . $e->getMessage());
                throw $e; // Relanza la excepción para que la transacción haga rollback
            }
        } while ($url);
    }

    public function getExternalPlatforms(Client $client)
    {
        $apiKey = config('services.rawg.key');
        $url = "https://api.rawg.io/api/platforms?key={$apiKey}";

        do {
            try {
                $response = $client->get($url);
                $datos = json_decode($response->getBody(), true);

                foreach ($datos['results'] as $platform) {
                    Platform::create([
                        'id' => $platform['id'],
                        'name' => $platform['name'],
                        'slug' => $platform['slug'],
                        'image_background' => $platform['image_background'] ? $platform['image_background'] : ''
                    ]);
                }

                $url = $datos['next']; // Actualizar la URL para la próxima petición

            } catch (\Exception $e) {
                Log::error('Error fetching platforms from RAWG API: ' . $e->getMessage());
                throw $e; // Relanza la excepción para que la transacción haga rollback
            }
        } while ($url);
    }

    public function getExternalDevelopers(Client $client)
    {
        $apiKey = config('services.rawg.key');
        $url = "https://api.rawg.io/api/developers?key={$apiKey}&page_size=40";

        do {
            try {
                $response = $client->get($url);
                $datos = json_decode($response->getBody(), true);

                foreach ($datos['results'] as $developer) {
                    Developer::create([
                        'id' => $developer['id'],
                        'name' => $developer['name'],
                        'slug' => $developer['slug'],
                        'image_background' => $developer['image_background'] ? $developer['image_background'] : ''
                    ]);
                }
                $url = $datos['next']; // Actualizar la URL para la próxima petición

                if ($url) {
                    $url_components = parse_url($url);

                    // Obtener los parámetros de la consulta
                    parse_str($url_components['query'], $query_params);

                    // Obtener el número de página
                    $num_page = (int)$query_params['page'];

                    if ($num_page > 50) {
                        $url = null;
                    }
                }
            } catch (\Exception $e) {
                Log::error('Error fetching developers from RAWG API: ' . $e->getMessage());
                throw $e; // Relanza la excepción para que la transacción haga rollback
            }
        } while ($url);
    }

    public function getExternalGames(Client $client)
    {
        $apiKey = config('services.rawg.key');
        $url = "https://api.rawg.io/api/games?key={$apiKey}&page_size=40";

        do {
            try {
                $response = $client->get($url);
                $datos = json_decode($response->getBody(), true);

                foreach ($datos['results'] as $game) {
                    Game::create([
                        'id' => $game['id'],
                        'name' => $game['name'],
                        'slug' => $game['slug'],
                        'released' => $game['released'],
                        'metacritic' => $game['metacritic'],
                        'playtime' => $game['playtime'],
                        'image_background' => $game['background_image'] ? $game['background_image'] : ''
                    ]);
                }
                $url = $datos['next']; // Actualizar la URL para la próxima petición

                if ($url) {
                    $url_components = parse_url($url);

                    // Obtener los parámetros de la consulta
                    parse_str($url_components['query'], $query_params);

                    // Obtener el número de página
                    $num_page = (int)$query_params['page'];

                    if ($num_page > 50) {
                        $url = null;
                    }
                }
            } catch (\Exception $e) {
                Log::error('Error fetching developers from RAWG API: ' . $e->getMessage());
                throw $e; // Relanza la excepción para que la transacción haga rollback
            }
        } while ($url);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroyAll()
    {
        try {
            DB::table('genres')->truncate();
        } catch (Exception $e) {
            return response($e, 409);
        }

        return response('', 204);
    }
}
