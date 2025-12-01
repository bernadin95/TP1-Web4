<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Language;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\UserResource;
use App\Http\Resources\LanguageResource;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;

class UserController extends Controller
{
     /**
 * @OA\Post(
 *     path="/users",
 *     summary="Créer un utilisateur",
 *     tags={"Users"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="login", type="string", example="bdje"),
 *             @OA\Property(property="email", type="string", example="bdje@example.com"),
 *             @OA\Property(property="password", type="string", example="Secret123!"),
 *             @OA\Property(property="first_name", type="string", example="Bernadin"),
 *             @OA\Property(property="last_name", type="string", example="Dje")
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Utilisateur créé avec succès",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="id", type="integer", example=1),
 *             @OA\Property(property="login", type="string", example="bdje"),
 *             @OA\Property(property="email", type="string", example="bdje@example.com"),
 *             @OA\Property(property="first_name", type="string", example="Bernadin"),
 *             @OA\Property(property="last_name", type="string", example="Dje")
 *         )
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Erreur de validation"
 *     )
 * )
 */
     public function store(StoreUserRequest $request): JsonResponse
    {
        $user = User::create($request->validated());

        return (new UserResource($user))
            ->response()
            ->setStatusCode(201);
    }

    /**
 * @OA\Put(
 *     path="/users/{id}",
 *     summary="Mettre à jour un utilisateur",
 *     tags={"Users"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID de l'utilisateur"
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="login", type="string", example="btra"),
 *             @OA\Property(property="email", type="string", example="btra@example.com"),
 *             @OA\Property(property="password", type="string", example="NewSecret123!"),
 *             @OA\Property(property="first_name", type="string", example="Bi"),
 *             @OA\Property(property="last_name", type="string", example="Tra")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Utilisateur mis à jour",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="id", type="integer", example=1),
 *             @OA\Property(property="login", type="string", example="btra"),
 *             @OA\Property(property="email", type="string", example="btra@example.com"),
 *             @OA\Property(property="first_name", type="string", example="Bi"),
 *             @OA\Property(property="last_name", type="string", example="Tra")
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Utilisateur introuvable"
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Erreur de validation"
 *     )
 * )
 */
   public function update(UpdateUserRequest $request, User $user): JsonResponse
    {
        $data = $request->validated();

        $user->update([
            'login'      => $data['login'],
            'password'   => Hash::make($data['password']),
            'email'      => $data['email'],
            'last_name'  => $data['last_name'],
            'first_name' => $data['first_name'],
        ]);

        return (new UserResource($user))
            ->response()
            ->setStatusCode(200);
    }

        /**
     * @OA\Get(
     *     path="/users/{id}/preferred-language",
     *     summary="Recevoir le langage préféré d’un utilisateur",
     *     description="Analyse les critiques de l'utilisateur pour déterminer son langage le plus utilisé.",
     *     tags={"Users"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de l'utilisateur",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Langage préféré",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="language", type="string", example="fr")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Utilisateur introuvable"
     *     )
     * )
     */
    public function preferredLanguage(User $user): JsonResponse
    {
        $row = $user->critics()
            ->selectRaw('films.language_id, COUNT(*) as total')
            ->join('films', 'critics.film_id', '=', 'films.id')
            ->groupBy('films.language_id')
            ->orderByDesc('total')
            ->orderBy('films.language_id')
            ->first();

        if (! $row) {
            return response()->json([
                'message' => 'Aucune critique trouvée pour cet utilisateur.',
            ], 404);
        }

        $language = Language::find($row->language_id);

        if (! $language) {
            return response()->json([
                'message' => 'Langage introuvable.',
            ], 404);
        }

        return (new LanguageResource($language))
            ->response()
            ->setStatusCode(200);
    }
}
