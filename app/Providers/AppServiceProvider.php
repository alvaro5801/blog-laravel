<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate; 
use App\Models\Comment; 
use App\Models\User; 

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // DEFINIÇÃO DO GATE: 'modify-comment'
        // O Laravel passará o usuário autenticado (que será null, já que não há login) 
        // e o objeto Comment.
        Gate::define('modify-comment', function (?User $user, Comment $comment) {
            
            // Regra de Negócio: Somente o usuário com ID 1 (o usuário de teste)
            // ou o autor original do comentário pode modificá-lo.
            $SIMULATED_USER_ID = 1; 

            // Retorna true se o ID do autor do comentário for o ID simulado (1)
            // Na ausência de autenticação, o user que está tentando é considerado o 1.
            return $comment->userId === $SIMULATED_USER_ID;
        });
    }
}