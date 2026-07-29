@extends('layouts.app')

@section('title', 'Новости Университета')

@section('content')
    <!-- Категории -->
    <div class="flex flex-wrap gap-2 mb-8">
        <a href="{{ route('news.index') }}" class="px-4 py-2 bg-blue-900 text-white rounded-full text-sm font-medium">Все</a>
        @foreach($categories as $cat)
            <a href="{{ route('news.category', $cat->slug) }}" class="px-4 py-2 bg-white hover:bg-gray-200 text-gray-700 rounded-full text-sm font-medium border border-gray-300 transition">
                {{ $cat->name }}
            </a>
        @endforeach
    </div>

    <h1 class="text-3xl font-bold mb-6 text-gray-900">Последние новости</h1>

    <!-- Сетка новостей -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($posts as $post)
            <article class="bg-white rounded-lg shadow-sm overflow-hidden flex flex-col hover:shadow-md transition">
                @if($post->image)
                    <img src="{{ asset('storage/' . $post->image) }}" alt="{{ $post->title }}" class="h-48 w-full object-cover">
                @else
                    <div class="h-48 w-full bg-gray-200 flex items-center justify-center text-gray-400">Нет обложки</div>
                @endif
                
                <div class="p-5 flex flex-col flex-grow">
                    <div class="text-xs text-blue-600 font-semibold uppercase tracking-wider mb-1">
                        {{ $post->category->name }}
                    </div>
                    <h2 class="text-xl font-bold mb-2 text-gray-900">
                        <a href="{{ route('news.show', $post->slug) }}" class="hover:text-blue-700 transition">
                            {{ $post->title }}
                        </a>
                    </h2>
                    <div class="text-gray-600 text-sm mb-4 line-clamp-3">
                        {!! Str::limit(strip_tags($post->content), 120) !!}
                    </div>
                    <div class="mt-auto flex justify-between items-center text-xs text-gray-500 border-t pt-3">
                        <span>{{ $post->created_at->format('d.m.Y H:i') }}</span>
                        <a href="{{ route('news.show', $post->slug) }}" class="text-blue-600 font-semibold hover:underline">Читать далее &rarr;</a>
                    </div>
                </div>
            </article>
        @empty
            <p class="text-gray-500 col-span-3">Пока нет опубликованных новостей.</p>
        @endforelse
    </div>

    <div class="mt-8">
        {{ $posts->links() }}
    </div>
@endsection