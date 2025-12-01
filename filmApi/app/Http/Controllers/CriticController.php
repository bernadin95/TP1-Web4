<?php

namespace App\Http\Controllers;
use Illuminate\Http\JsonResponse;

use Illuminate\Http\Request;
use App\Models\Critic;

class CriticController extends Controller
{
        /**
     * @OA\Delete(
     *     path="/critics/{id}",
     *     summary="Supprimer une critique",
     *     tags={"Critics"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la critique",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Critique supprimée avec succès",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Critique supprimée avec succès")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Critique introuvable")
     * )
     */
    public function destroy(Critic $critic): JsonResponse
    {
        $critic->delete();

        return response()->json(null, 204);
    }
}
