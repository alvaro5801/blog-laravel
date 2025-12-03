<?php

namespace App\View\Components;

use Illuminate\View\Component;
use App\Models\Post; // Certifique-se de importar o modelo Post

class PostCard extends Component
{
    public $post;

    /**
     * Cria uma nova instância do componente.
     */
    public function __construct(Post $post)
    {
        $this->post = $post;
    }

    /**
     * Obtém a view que representa o componente.
     */
    public function render()
    {
        return view('components.post-card');
    }
}