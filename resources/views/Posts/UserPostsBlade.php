@extends('layouts.app')

@section('content')
    <div class="mb-8 border-b pb-4">
        
        <div class="flex items-center gap-6 mb-6 text-sm font-medium">
            <button onclick="history.back()" class="flex items-center gap-2 text-gray-500 hover:text-blue-600 transition">
                <span>&larr;</span> Voltar
            </button>
            <span class="text-gray-300">|</span>
            <a href="{{ route('home') }}" class="flex items-center gap-2 text-gray-500 hover:text-blue-600 transition">
                <span>🏠</span> Ir para o Início
            </a>
        </div>

        <div class="flex items-center justify-between flex-wrap gap-4 mb-2">
            <h1 class="text-3xl font-bold text-gray-800">
                Publicações de <span class="text-blue-600">{{ $user->firstName }} {{ $user->lastName }}</span>
            </h1>

            <a href="{{ route('users.show', $user->id) }}" class="text-sm bg-gray-100 text-gray-700 px-4 py-2 rounded hover:bg-gray-200 transition border shadow-sm">
                Ver Perfil Completo
            </a>
        </div>

        <p class="text-gray-600 mt-2">Total de {{ $posts->total() }} posts encontrados.</p>
    </div>

    @include('posts._filters')

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        {{-- Substituindo o bloco HTML duplicado pela chamada do componente --}}
        @forelse($posts as $post)
            <x-post-card :post="$post" />
        @empty
            <div class="col-span-3 text-center py-12 text-gray-500">
                Nenhum post encontrado para este usuário com estes filtros.
            </div>
        @endforelse
    </div>

    <div class="mt-8">
        {{ $posts->links() }}
    </div>
@endsection