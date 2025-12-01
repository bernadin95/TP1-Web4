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
    public function index(): JsonResponse
    {
        $films = Film::all();

        return response()->json(FilmResource::collection($films), 200);
    }

    public function actors(Film $film): JsonResponse
    {
        $actors = $film->actors;

        return response()->json(ActorResource::collection($actors), 200);
    }

    public function showWithCritics(Film $film): JsonResponse
    {
        $film->load('critics');

        return response()->json([
            'film'    => new FilmResource($film),
            'critics' => CriticResource::collection($film->critics),
        ], 200);
    }

    public function averageScore(Film $film): JsonResponse
    {
        $average = $film->critics()->avg('score'); 

        return response()->json([
            'film_id'       => $film->id,
            'average_score' => $average,
        ], 200);
    }

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
