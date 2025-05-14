<?php
namespace Laymont\HexagonalArchitecture\Infrastructure\Http\Controllers;

use Laymont\HexagonalArchitecture\Application\UseCases\RegisterUser;
use Laymont\HexagonalArchitecture\Infrastructure\Http\Requests\CreateUserRequest;
use Illuminate\Routing\Controller as BaseController;

class UserController extends BaseController
{
    public function store(CreateUserRequest $request, RegisterUser $registerUser): \Illuminate\Http\JsonResponse
    {
        $user = $registerUser->register($request->validated());

        return response()->json($user->toArray(), 201);
    }
}
