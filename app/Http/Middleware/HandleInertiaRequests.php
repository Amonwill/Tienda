<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;
use Illuminate\Support\Facades\DB; // IMPORTANTE: Para interactuar con SQL Server

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        // 1. Ejecutamos el Procedimiento Almacenado de alertas antes de compartir datos.
        // Esto asegura que la tabla Bandeja_Alertas esté siempre al día.
        try {
            DB::statement("EXEC sp_generar_alertas");
        } catch (\Exception $e) {
            // Loguear error si es necesario, pero permitir que el sistema siga operando
        }

        return [
            ...parent::share($request),
            'auth' => [
                'user' => $request->user(),
            ],
            
            // 2. Compartimos las alertas globalmente con Vue
            // Accederás a ellas como $page.props.alertas
            'alertas' => DB::table('Bandeja_Alertas')
                ->orderBy('Fecha_Generada', 'DESC')
                ->get(),
        ];
    }
}