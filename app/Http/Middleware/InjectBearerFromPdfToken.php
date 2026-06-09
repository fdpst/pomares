<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * Permite abrir PDFs en una pestaña nueva (window.open) pasando el token Sanctum en query.
 * El visor del navegador puede descargar URLs HTTP reales; los blob: anónimos fallan en Chrome.
 */
class InjectBearerFromPdfToken
{
    public function handle(Request $request, Closure $next)
    {
        if (! $request->bearerToken()) {
            $pdfToken = $request->query('pdf_token');
            if (is_string($pdfToken) && $pdfToken !== '') {
                $request->headers->set('Authorization', 'Bearer ' . $pdfToken);
            }
        }

        return $next($request);
    }
}
