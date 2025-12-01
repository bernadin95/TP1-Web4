<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Film;
use App\Http\Resources\FilmResource;
use App\Http\Resources\ActorResource;
use App\Http\Resources\CriticResource;
use Illuminate\Http\JsonResponse;

class FilmController extends Controller
{
        /**
     * @OA\Get(
     *     path="/films",
     *     summary="Retourne la liste de tous les films",
     *     tags={"Films"},
     *     @OA\Response(
     *         response=200,
     *         description="Liste des films",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="title", type="string", example="ACADEMY DINOSAUR"),
     *                 @OA\Property(property="release_year", type="integer", example=2006),
     *                 @OA\Property(property="length", type="integer", example=86),
     *                 @OA\Property(property="description", type="string", example="A Epic Drama of a Feminist..."),
     *                 @OA\Property(property="rating", type="string", example="PG"),
     *                 @OA\Property(property="language_id", type="integer", example=1),
     *                 @OA\Property(property="special_features", type="string", example="Deleted Scenes,Behind the Scenes"),
     *                 @OA\Property(property="image", type="string", nullable=true, example="")
     *             )
     *         )
     *     )
     * )
     */
    public function index(): JsonResponse
    {
        $films = Film::all();

        return response()->json(FilmResource::collection($films), 200);
    }

    
    /**
     * @OA\Get(
     *     path="/films/{id}/actors",
     *     summary="Retourne les acteurs d'un film",
     *     tags={"Films"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID du film",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Liste des acteurs",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="first_name", type="string", example="PENELOPE"),
     *                 @OA\Property(property="last_name", type="string", example="GUINESS")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=404, description="Film introuvable")
     * )
     */
    public function actors(Film $film): JsonResponse
    {
        $actors = $film->actors;

        return response()->json(ActorResource::collection($actors), 200);
    }

            /**
     * @OA\Get(
     *     path="/films/{id}/with-critics",
     *     summary="Retourne un film avec ses critiques",
     *     tags={"Films"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID du film",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Film avec critiques",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="title", type="string", example="ACADEMY DINOSAUR"),
     *             @OA\Property(property="release_year", type="integer", example=2006),
     *             @OA\Property(property="length", type="integer", example=86),
     *             @OA\Property(property="description", type="string", example="A Epic Drama of a Feminist..."),
     *             @OA\Property(property="rating", type="string", example="PG"),
     *             @OA\Property(property="language_id", type="integer", example=1),
     *             @OA\Property(property="special_features", type="string", example="Deleted Scenes,Behind the Scenes"),
     *             @OA\Property(property="image", type="string", nullable=true, example=""),
     *             @OA\Property(
     *                 property="critics",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=10),
     *                     @OA\Property(property="user_id", type="integer", example=5),
     *                     @OA\Property(property="film_id", type="integer", example=1),
     *                     @OA\Property(property="score", type="integer", example=4),
     *                     @OA\Property(property="comment", type="string", example="Très bon film"),
     *                     @OA\Property(property="created_at", type="string", format="date-time")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(response=404, description="Film introuvable")
     * )
     */
    public function showWithCritics(Film $film): JsonResponse
    {
        $film->load('critics');

        return response()->json([
            'film'    => new FilmResource($film),
            'critics' => CriticResource::collection($film->critics),
        ], 200);
    }

        /**
     * @OA\Get(
     *     path="/films/{film}/average-score",
     *     summary="Obtenir la moyenne des scores d'un film",
     *     description="Retourne la moyenne des scores pour un film particulier basé sur ses critiques.",
     *     tags={"Films"},
     *
     *     @OA\Parameter(
     *         name="film",
     *         in="path",
     *         required=true,
     *         description="ID du film"
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Moyenne calculée avec succès",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="film_id", type="integer", example=5),
     *             @OA\Property(property="average_score", type="number", format="float", example=7.5)
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Film introuvable"
     *     )
     * )
     */
    public function averageScore(Film $film): JsonResponse
    {
        $average = $film->critics()->avg('score'); 

        return response()->json([
            'film_id'       => $film->id,
            'average_score' => $average,
        ], 200);
    }

    /**
     * @OA\Get(
     *     path="/films/search",
     *     summary="Recherche de films",
     *     description="Recherche des films par titre, description ou autres critères.",
     *     tags={"Films"},
     *     @OA\Parameter(
     *         name="q",
     *         in="query",
     *         required=false,
     *         description="Terme de recherche",
     *         @OA\Schema(type="string", example="DINOSAUR")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Résultats de recherche paginés",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="title", type="string", example="ACADEMY DINOSAUR"),
     *                     @OA\Property(property="release_year", type="integer", example=2006),
     *                     @OA\Property(property="length", type="integer", example=86),
     *                     @OA\Property(property="description", type="string", example="A Epic Drama of a Feminist..."),
     *                     @OA\Property(property="rating", type="string", example="PG"),
     *                     @OA\Property(property="language_id", type="integer", example=1),
     *                     @OA\Property(property="special_features", type="string", example="Deleted Scenes,Behind the Scenes"),
     *                     @OA\Property(property="image", type="string", nullable=true, example="")
     *                 )
     *             ),
     *             @OA\Property(property="current_page", type="integer", example=1),
     *             @OA\Property(property="last_page", type="integer", example=5),
     *             @OA\Property(property="per_page", type="integer", example=15),
     *             @OA\Property(property="total", type="integer", example=72)
     *         )
     *     )
     * )
     */
    public function search(Request $request): JsonResponse
    {
        $query = Film::query();

        if ($keyword = $request->query('keyword')) {
            $query->where('title', 'like', '%' . $keyword . '%');
        }

        if ($rating = $request->query('rating')) {
            $query->where('rating', 'like', '%' . $rating . '%');
        }

        if ($minLength = $request->query('minLength')) {
            $query->where('length', '>=', (int) $minLength);
        }

        if ($maxLength = $request->query('maxLength')) {
            $query->where('length', '<=', (int) $maxLength);
        }

        $films = $query->paginate(20);

        return FilmResource::collection($films)
            ->response()
            ->setStatusCode(200);
    }

}
