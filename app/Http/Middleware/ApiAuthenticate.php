<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate;

// Esse middleware foi necessário de implementar porque ao deletar o cliente, a sessão fecha automaticamente e o Laravel tentava redirecionar para
// a rota de login, isso era automático e não tem nenhuma referência a login no projeto
// então precisei colocar esse middleware para captuar a exception e retornar não autorizado ao invés de redirecionar
class ApiAuthenticate extends Authenticate
{

    public function handle($request, \Closure $next, ...$guards)
    {
        try {
            $this->authenticate($request, $guards);
        } catch (AuthenticationException $e) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        return $next($request);
    }

    protected function redirectTo($request)
    {
        return null;
    }
}
