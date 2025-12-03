@props(['post' => $post])

<article class="bg-white rounded-lg shadow-md hover:shadow-xl transition duration-300 flex flex-col overflow-hidden border border-gray-100">
    <div class="p-6 flex-1 flex flex-col">
        
        <div class="flex flex-wrap gap-2 mb-3">
            @if($post->tags)
                @foreach($post->tags as $tag)
                    <a href="{{ request()->fullUrlWithQuery(['tag' => $tag]) }}" class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full font-semibold hover:bg-blue-200 transition">
                        #{{ $tag }}
                    </a>
                @endforeach
            @endif
        </div>

        <h2 class="text-xl font-bold mb-2 text-gray-900 leading-tight">
            <a href="{{ route('posts.show', $post->id) }}" class="hover:text-blue-600 hover:underline">
                {{ $post->title }}
            </a>
        </h2>

        <p class="text-gray-600 mb-4 text-sm line-clamp-3">
            {{ Str::limit($post->body, 120) }}
        </p>

        <div class="mt-auto border-t pt-4 flex items-center justify-between text-sm text-gray-500">
            
            {{-- Detalhes do Autor --}}
            <a href="{{ route('users.posts', $post->author->id) }}" class="flex items-center gap-2 group hover:bg-gray-50 p-1 -ml-1 rounded transition" title="Ver posts de {{ $post->author->firstName }}">
                 <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center overflow-hidden border border-transparent group-hover:border-blue-400 group-hover:scale-105 transition">
                    @if($post->author && $post->author->image)
                        <img src="{{ $post->author->image }}" alt="User" class="w-full h-full object-cover">
                    @else
                        <span>{{ substr($post->author->firstName ?? 'U', 0, 1) }}</span>
                    @endif
                 </div>
                 <span class="font-medium truncate max-w-[100px] group-hover:text-blue-600 transition">{{ $post->author->firstName ?? 'Anônimo' }}</span>
            </a>

            {{-- Interações --}}
            <div class="flex items-center gap-4">
                
                <form action="{{ route('posts.like', $post->id) }}" method="POST" class="inline like-form">
                    @csrf
                    @php $userHasLiked = in_array($post->id, session('liked_posts', [])); @endphp
                    <button type="submit" class="flex items-center gap-1 transition {{ $userHasLiked ? 'text-green-600 font-bold' : 'text-gray-500 hover:text-green-600' }}" title="Curtir">
                        <span class="text-lg">👍</span> 
                        <span class="count-display">{{ $post->reactions_likes }}</span>
                    </button>
                </form>

                <form action="{{ route('posts.dislike', $post->id) }}" method="POST" class="inline dislike-form">
                    @csrf
                    @php $userHasDisliked = in_array($post->id, session('disliked_posts', [])); @endphp
                    <button type="submit" class="flex items-center gap-1 transition {{ $userHasDisliked ? 'text-red-500 font-bold' : 'text-gray-500 hover:text-red-500' }}" title="Não curtir">
                        <span class="text-lg">👎</span> 
                        <span class="count-display">{{ $post->reactions_dislikes }}</span>
                    </button>
                </form>

                <a href="{{ route('posts.show', $post->id) }}#comments-section" class="flex items-center gap-1 text-gray-500 hover:text-blue-600 transition ml-1 group" title="Ver comentários">
                    <span class="text-lg group-hover:scale-110 transition-transform">💬</span> 
                    {{ $post->comments->count() }}
                </a>
            </div>
        </div>
    </div>
</article>