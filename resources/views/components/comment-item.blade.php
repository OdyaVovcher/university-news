@props(['comment'])

<div class="bg-gray-50 p-4 rounded-lg border border-gray-100 mb-3">
    <!-- Шапка комментария: Аватарка + Имя со ссылкой + Дата -->
    <div class="flex items-center justify-between mb-3">
        <div class="flex items-center gap-3">
            <!-- Аватарка (Фото или Синий кружок с первой буквой) -->
            @if(optional($comment->user)->avatar)
                <img src="{{ asset('storage/' . $comment->user->avatar) }}" 
                     alt="{{ $comment->user->name }}" 
                     class="w-9 h-9 rounded-full object-cover border border-gray-300">
            @else
                <div class="w-9 h-9 rounded-full bg-blue-900 text-white font-bold flex items-center justify-center text-sm shadow-sm flex-shrink-0">
                    {{ mb_substr($comment->user->name ?? $comment->user_name ?? 'А', 0, 1) }}
                </div>
            @endif

            <div>
                <!-- Имя пользователя со ссылкой (свой профиль / публичный профиль) -->
                @if($comment->user)
                    <a href="{{ auth()->check() && auth()->id() === $comment->user_id ? route('profile.edit') : route('profile.show', $comment->user) }}" 
                       class="font-semibold text-gray-900 hover:text-blue-600 transition">
                        {{ $comment->user->name }}
                        @if(auth()->check() && auth()->id() === $comment->user_id)
                            <span class="text-xs text-blue-600 font-normal ml-1">(Вы)</span>
                        @endif
                    </a>
                @else
                    <span class="font-semibold text-gray-900">
                        {{ $comment->user_name ?? 'Гость' }}
                    </span>
                @endif

                <!-- Название академической группы -->
                @if(optional($comment->user)->group)
                    <span class="text-xs text-gray-500 block">
                        Группа: {{ $comment->user->group->name }}
                    </span>
                @endif
            </div>
        </div>

        <!-- Дата создания -->
        <span class="text-xs text-gray-400">
            {{ $comment->created_at->diffForHumans() }}
        </span>
    </div>

    <!-- Текст комментария -->
    <div class="text-gray-700 text-sm mb-3 pl-12">
        {{ $comment->body }}
    </div>

    <!-- Кнопки Ответить / Удалить -->
    <div class="flex items-center gap-4 text-xs font-semibold pl-12">
        <button type="button" 
                onclick="toggleReplyForm({{ $comment->id }})" 
                class="text-blue-600 hover:underline">
            Ответить
        </button>

        @can('delete', $comment)
            <form action="{{ route('comments.destroy', $comment->id) }}" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" 
                        onclick="return confirm('Удалить комментарий?')" 
                        class="text-red-500 hover:underline">
                    Удалить
                </button>
            </form>
        @endcan
    </div>

    <!-- Форма ответа на комментарий (скрытая) -->
    <div id="reply-form-{{ $comment->id }}" class="hidden mt-4 pl-12">
        <form action="{{ route('comments.store', $comment->post_id) }}" method="POST" class="bg-white p-3 rounded border border-gray-200 shadow-sm">
            @csrf
            <input type="hidden" name="parent_id" value="{{ $comment->id }}">
            
            <textarea name="body" rows="2" required 
                      class="w-full p-2 text-sm border border-gray-300 rounded focus:outline-none focus:border-blue-500 mb-2" 
                      placeholder="Ваш ответ..."></textarea>
            
            <button type="submit" class="bg-blue-600 text-white text-xs px-3 py-1.5 rounded hover:bg-blue-700 font-semibold transition">
                Отправить ответ
            </button>
        </form>
    </div>

    <!-- Рекурсивные ответы (Вложенные комментарии) -->
    @if($comment->replies && $comment->replies->count() > 0)
        <div class="mt-4 pl-6 border-l-2 border-gray-200 space-y-3">
            @foreach($comment->replies as $reply)
                <x-comment-item :comment="$reply" />
            @endforeach
        </div>
    @endif
</div>