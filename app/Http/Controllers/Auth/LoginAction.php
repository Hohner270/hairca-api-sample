<?php

namespace App\Http\Controllers\Users;

use Illuminate\Auth\AuthManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Tymon\JWTAuth\JWTGuard;

use App\Http\Controllers\Controller;
use App\Http\Responders\TokenResponder;

class LoginAction extends Controller
{
    /**
     * @var AuthManager
     */
    private $authManager;

    /**
     * @param AuthManager
     */
    public function __construct(AuthManager $authManager)
    {
        $this->authManager = $authManager;
    }

    /**
     * @param Request
     * @param TokenResponder
     * @return JsonResponse
     */
    public function __invoke(Request $request, TokenResponder $responder): JsonResponse
    {
        $guard = $this->authManager->guard('api');
        
        $token = $guard->attempt([
            'email'    => $request->email,
            'password' => $request->password,
        ]);

        return $responder(
            $token,
            $guard->factory()->getTTL() * 60
        );
    }
}