@extends('layouts.app')

@section('title', $post->title)
@section('main-class', 'max-w-4xl')

@section('header-actions')
    <a href="{{ route('news.index') }}" class="text-sm border border-blue-400 hover:bg-blue-800 px-3 py-1.5 rounded transition">
        &larr; На главную
    </a>
@endsection

@section('content')
    <article class="bg-white rounded-lg shadow-sm p-6 md:p-8">
        <div class="text-sm text-blue-600 font-semibold uppercase tracking-wider mb-2">
            {{ $post->category->name ?? $post->category->title }}
        </div>
        
        <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 mb-4">{{ $post->title }}</h1>
        
        <div class="text-xs text-gray-500 mb-6">
            Опубликовано: {{ $post->created_at->format('d.m.Y в H:i') }}
        </div>

        @if($post->image)
            <img src="{{ asset('storage/' . $post->image) }}" alt="{{ $post->title }}" class="w-full max-h-[450px] object-cover rounded-lg mb-6">
        @endif

        <div class="prose max-w-none text-gray-700 leading-relaxed text-lg">
            {!! $post->content !!}
        </div>
    </article>

    <!-- Вынесенный блок комментариев -->
    <x-comments-section :post="$post" />
@endsection

@push('scripts')
    <script>
        function toggleReplyForm(commentId) {
            const form = document.getElementById(`reply-form-${commentId}`);
            if (form) {
                form.classList.toggle('hidden');
            }
        }
    </script>
@endpush