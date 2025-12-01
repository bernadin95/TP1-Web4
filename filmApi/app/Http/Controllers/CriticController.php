<?php

namespace App\Http\Controllers;
use Illuminate\Http\JsonResponse;

use Illuminate\Http\Request;
use App\Models\Critic;

class CriticController extends Controller
{
    public function destroy(Critic $critic): JsonResponse
    {
        $critic->delete();

        return response()->json(null, 204);
    }
}
