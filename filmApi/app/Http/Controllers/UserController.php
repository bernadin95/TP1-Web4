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
     public function store(StoreUserRequest $request): JsonResponse
    {
        $user = User::create($request->validated());

        return (new UserResource($user))
            ->response()
            ->setStatusCode(201);
    }

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
