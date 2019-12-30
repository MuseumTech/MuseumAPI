<?php

namespace App\Http\Middleware;

use Closure;
use Auth0\SDK\JWTVerifier;

class Auth0Middleware
{
    /**
     * Run the request filter.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next, $scopeRequired = null)
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json('No token provided', 401);
        }

        $this->validateAndDecode($token);
        $decodedToken = $this->validateAndDecode($token);

        if ($scopeRequired && !$this->tokenHasScope($decodedToken, $scopeRequired)) {
            return response()->json(['message' => 'Insufficient scope'], 403);
        }
        return $next($request);
    }

    public function validateAndDecode($token)
    {
        try {
            $verifier = new JWTVerifier([
                'supported_algs' => ['RS256'],
                'valid_audiences' => [env('AUTH0_AUDIENCE')],
                'authorized_iss' => [env('AUTH0_ISS')]
            ]);

            return $verifier->verifyAndDecode($token);
        } catch (\Auth0\SDK\Exception\CoreException $e) {
            throw $e;
        };
    }

    /**
     * Check if a token has a specific scope.
     *
     * @param \stdClass $token - JWT access token to check.
     * @param string $scopeRequired - Scope to check for.
     *
     * @return bool
     */
    protected function tokenHasScope($token, $scopeRequired)
    {
        if (empty($token->scope)) {
            return false;
        }

        $tokenScopes = explode(' ', $token->scope);
        return in_array($scopeRequired, $tokenScopes);
    }

}