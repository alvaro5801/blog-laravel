<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use App\Services\ReactionService;

class PostController extends Controller
{
    protected $reactionService;

    // Construtor: Injeta o serviço e o armazena na propriedade
    public function __construct(ReactionService $reactionService)
    {
        $this->reactionService = $reactionService;
    }

    // --- FUNÇÕES AUXILIARES (REMOVIDA a applyFilters) ---
    // A função applyFilters foi movida para o Post Model como Query Scopes.

    // --- PÁGINAS PRINCIPAIS ---
    public function index(Request $request)
    {
        // APLICANDO OS SCOPES DIRETAMENTE NA QUERY
        $posts = Post::with('author')
            ->search($request->search)
            ->tag($request->tag)
            ->sort($request->sort)
            ->paginate(30)
            ->withQueryString();
            
        return view('posts.index', compact('posts')); 
    }

    public function show($id)
    {
        $post = Post::with(['author', 'comments.user'])->findOrFail($id);

        // Lógica de navegação mantida (Smart Back Button)
        $previous = url()->previous();
        $isHome = ($previous == route('home')) || ($previous == route('home') . '/');
        $isUserList = str_contains($previous, '/posts') && !str_contains($previous, '/post/');

        if ($isHome || $isUserList) {
            session()->put('back_url', $previous);
        }
        if (!session()->has('back_url')) {
            session()->put('back_url', route('home'));
        }

        return view('posts.show', compact('post'));
    }

    public function postsByUser(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        // APLICANDO OS SCOPES DIRETAMENTE NA QUERY
        $posts = $user->posts()
            ->with('author')
            ->search($request->search)
            ->tag($request->tag)
            ->sort($request->sort)
            ->paginate(30)
            ->withQueryString();
            
        return view('posts.user_posts', compact('user', 'posts')); 
    }

    // --- INTERAÇÕES POSTS (LIKES/DISLIKES - sem alteração) ---
    public function like($id)
    {
        $post = Post::findOrFail($id);
        $data = $this->reactionService->togglePostLike($post);

        if (request()->wantsJson()) {
            return response()->json($data);
        }
        return back();
    }

    public function dislike($id)
    {
        $post = Post::findOrFail($id);
        $data = $this->reactionService->togglePostDislike($post);

        if (request()->wantsJson()) {
            return response()->json($data);
        }
        return back();
    }
}