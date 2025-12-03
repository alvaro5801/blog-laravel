<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Auth\Access\AuthorizationException;
use App\Services\ReactionService;
use Illuminate\Support\Facades\Gate; // Importe a classe Gate

class CommentController extends Controller
{
    protected $reactionService;

    public function __construct(ReactionService $reactionService)
    {
        $this->reactionService = $reactionService;
    }

    public function store(Request $request, $postId)
    {
        $request->validate([
            'body' => 'required|min:2|max:500'
        ]);

        Comment::create([
            'body' => $request->body,
            'postId' => $postId,
            'userId' => 1,
            'likes' => 0,
            'dislikes' => 0
        ]);
        
        return redirect()->route('posts.show', $postId)->withFragment('comments-section')->with('success', 'Comentário enviado!');
    }

    public function edit($id)
    {
        $comment = Comment::findOrFail($id);
        
        
        Gate::authorize('modify-comment', $comment);

        
        return view('comments.edit', compact('comment')); 
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'body' => 'required|min:2|max:500'
        ]);
        
        $comment = Comment::findOrFail($id);
        
        
        Gate::authorize('modify-comment', $comment);
        
        $comment->update(['body' => $request->body]);
        
        return redirect()->route('posts.show', $comment->postId)->withFragment('comments-section')->with('success', 'Atualizado!');
    }

    public function destroy($id)
    {
        $comment = Comment::findOrFail($id);
        
        
        Gate::authorize('modify-comment', $comment);
        
        $comment->delete();
        
        return back()->with('success', 'Removido.');
    }
    
    public function like($id)
    {
        $comment = Comment::findOrFail($id);
        $data = $this->reactionService->toggleCommentLike($comment); 

        if (request()->wantsJson()) {
            return response()->json($data);
        }
        return back();
    }

    public function dislike($id)
    {
        $comment = Comment::findOrFail($id);
        $data = $this->reactionService->toggleCommentDislike($comment); 

        if (request()->wantsJson()) {
            return response()->json($data);
        }
        return back();
    }
}