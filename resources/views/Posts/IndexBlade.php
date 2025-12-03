@extends('layouts.app')

@section('content')
    <div class="flex justify-between items-end mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Últimas Publicações</h1>
        <span class="text-gray-500 text-sm">Página {{ $posts->currentPage() }}</span>
    </div>

    @include('posts._filters')

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($posts as $post)
            {{-- Substituído o bloco <article> pela chamada do componente PostCard --}}
            <x-post-card :post="$post" />
        @empty
            <div class="col-span-3 text-center py-12 text-gray-500">
                Nenhum post encontrado com estes filtros.
            </div>
        @endforelse
    </div>

    <div class="mt-8">
        {{ $posts->links() }}
    </div>
@endsection