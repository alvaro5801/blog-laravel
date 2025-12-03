<?php

namespace App\Services;

use App\Models\Post;
use App\Models\Comment;
use Illuminate\Support\Facades\Session;

class ReactionService
{
    // --- LÓGICA PARA POSTS ---

    /**
     * Alterna o estado de 'like' em um Post, tratando o 'dislike' se estiver ativo.
     * Retorna os novos contadores para a resposta JSON.
     */
    public function togglePostLike(Post $post): array
    {
        $id = $post->id;
        $likedPosts = Session::get('liked_posts', []);
        $dislikedPosts = Session::get('disliked_posts', []);
        $isLiked = false;

        // 1. Se estava com dislike, remove o dislike
        if (in_array($id, $dislikedPosts)) {
            $post->decrement('reactions_dislikes');
            $dislikedPosts = array_diff($dislikedPosts, [$id]);
            Session::put('disliked_posts', $dislikedPosts);
        }

        // 2. Alterna o estado do like
        if (in_array($id, $likedPosts)) {
            $post->decrement('reactions_likes');
            $likedPosts = array_diff($likedPosts, [$id]);
            $isLiked = false;
        } else {
            $post->increment('reactions_likes');
            $likedPosts[] = $id;
            $isLiked = true;
        }
        Session::put('liked_posts', $likedPosts);
        
        $post->refresh();

        return [
            'success' => true,
            'liked' => $isLiked, 
            'disliked' => false,
            'likes_count' => $post->reactions_likes,
            'dislikes_count' => $post->reactions_dislikes
        ];
    }
    
    /**
     * Alterna o estado de 'dislike' em um Post, tratando o 'like' se estiver ativo.
     * Retorna os novos contadores para a resposta JSON.
     */
    public function togglePostDislike(Post $post): array
    {
        $id = $post->id;
        $likedPosts = Session::get('liked_posts', []);
        $dislikedPosts = Session::get('disliked_posts', []);
        $isDisliked = false;

        // 1. Se estava com like, remove o like
        if (in_array($id, $likedPosts)) {
            $post->decrement('reactions_likes');
            $likedPosts = array_diff($likedPosts, [$id]);
            Session::put('liked_posts', $likedPosts);
        }

        // 2. Alterna o estado do dislike
        if (in_array($id, $dislikedPosts)) {
            $post->decrement('reactions_dislikes');
            $dislikedPosts = array_diff($dislikedPosts, [$id]);
            $isDisliked = false;
        } else {
            $post->increment('reactions_dislikes');
            $dislikedPosts[] = $id;
            $isDisliked = true;
        }
        Session::put('disliked_posts', $dislikedPosts);
        
        $post->refresh();

        return [
            'success' => true, 
            'liked' => false, 
            'disliked' => $isDisliked,
            'likes_count' => $post->reactions_likes, 
            'dislikes_count' => $post->reactions_dislikes
        ];
    }
    
    // --- LÓGICA PARA COMENTÁRIOS ---

    /**
     * Alterna o estado de 'like' em um Comment, tratando o 'dislike' se estiver ativo.
     */
    public function toggleCommentLike(Comment $comment): array
    {
        $id = $comment->id;
        $liked = Session::get('liked_comments', []);
        $disliked = Session::get('disliked_comments', []);
        $isLiked = false;

        // 1. Se estava com dislike, remove o dislike
        if (in_array($id, $disliked)) {
            $comment->decrement('dislikes');
            $disliked = array_diff($disliked, [$id]);
            Session::put('disliked_comments', $disliked);
        }

        // 2. Alterna o estado do like
        if (in_array($id, $liked)) {
            $comment->decrement('likes');
            $liked = array_diff($liked, [$id]);
            $isLiked = false;
        } else {
            $comment->increment('likes');
            $liked[] = $id;
            $isLiked = true;
        }
        Session::put('liked_comments', $liked);

        $comment->refresh();
        
        return [
            'success' => true, 
            'liked' => $isLiked, 
            'disliked' => false,
            'likes_count' => $comment->likes, 
            'dislikes_count' => $comment->dislikes
        ];
    }
    
    /**
     * Alterna o estado de 'dislike' em um Comment, tratando o 'like' se estiver ativo.
     */
    public function toggleCommentDislike(Comment $comment): array
    {
        $id = $comment->id;
        $liked = Session::get('liked_comments', []);
        $disliked = Session::get('disliked_comments', []);
        $isDisliked = false;

        // 1. Se estava com like, remove o like
        if (in_array($id, $liked)) {
            $comment->decrement('likes');
            $liked = array_diff($liked, [$id]);
            Session::put('liked_comments', $liked);
        }

        // 2. Alterna o estado do dislike
        if (in_array($id, $disliked)) {
            $comment->decrement('dislikes');
            $disliked = array_diff($disliked, [$id]);
            $isDisliked = false;
        } else {
            $comment->increment('dislikes');
            $disliked[] = $id;
            $isDisliked = true;
        }
        Session::put('disliked_comments', $disliked);

        $comment->refresh();
        
        return [
            'success' => true, 
            'liked' => false, 
            'disliked' => $isDisliked,
            'likes_count' => $comment->likes, 
            'dislikes_count' => $comment->dislikes
        ];
    }
}