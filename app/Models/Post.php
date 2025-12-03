<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'id', 'userId', 'title', 'body', 'tags', 'reactions_likes', 'reactions_dislikes', 'views', 'created_at', 'updated_at'
    ];

    protected $casts = [
        'tags' => 'array',
        // Garante que as reações são tratadas como inteiros
        'reactions_likes' => 'integer',
        'reactions_dislikes' => 'integer',
    ];

    // --- RELACIONAMENTOS ---
    public function author()
    {
        return $this->belongsTo(User::class, 'userId');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'postId');
    }

    // --- QUERY SCOPES (Filtros) ---

    /**
     * Aplica filtro de busca por título.
     */
    public function scopeSearch(Builder $query, ?string $search)
    {
        if ($search) {
            $query->where('title', 'like', '%' . $search . '%');
        }
        return $query;
    }

    /**
     * Aplica filtro por tag (usando whereJsonContains).
     */
    public function scopeTag(Builder $query, ?string $tag)
    {
        if ($tag) {
            $query->whereJsonContains('tags', $tag);
        }
        return $query;
    }

    /**
     * Aplica ordenação (likes, views, oldest, default).
     */
    public function scopeSort(Builder $query, ?string $sort)
    {
        switch ($sort) {
            case 'likes': $query->orderBy('reactions_likes', 'desc'); break;
            case 'views': $query->orderBy('views', 'desc'); break;
            case 'oldest': $query->orderBy('created_at', 'asc'); break;
            default: $query->orderBy('created_at', 'desc'); break;
        }
        return $query;
    }
}