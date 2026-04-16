<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class EnsureFarmaceuticoAtivo
{
    public function handle(Request $request, Closure $next): Response
    {
        $isFarmaceuticoAtivo = DB::table('farmaceuticos')
            ->where('id_usuario_pf', $request->user()->id)
            ->where('status_profissional', 'Ativo')
            ->exists();

        if (!$isFarmaceuticoAtivo) {
            abort(403, 'Acesso restrito a farmaceuticos ativos.');
        }

        return $next($request);
    }
}
