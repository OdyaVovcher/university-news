@props(['post'])

<section id="comments-section" class="mt-10 pt-8 border-t border-gray-200">
    <h3 class="text-2xl font-bold mb-6">Комментарии ({{ $post->comments()->where('is_approved', true)->count() }})</h3>

    <!-- Дерево комментариев с загрузкой 'user' и 'user.group' -->
    <div class="space-y-4 mb-8">
        @forelse($post->comments()->with(['user.group', 'replies.user.group'])->where('is_approved', true)->whereNull('parent_id')->latest()->get() as $comment)
            <x-comment-item :comment="$comment" />
        @empty
            <p class="text-gray-500 italic">Комментариев пока нет. Будьте первым!</p>
        @endforelse
    </div>

    <!-- Форма отправки главного комментария -->
    <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
        <h4 class="text-lg font-semibold mb-4">Оставить комментарий</h4>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('comments.store', $post->id) }}" method="POST">
            @csrf

            @auth
                <!-- Отображение текущего профиля (аватарка + имя со ссылкой) -->
                <div class="flex items-center gap-3 mb-4">
                    @if(auth()->user()->avatar)
                        <img src="{{ asset('storage/' . auth()->user()->avatar) }}" 
                             alt="{{ auth()->user()->name }}" 
                             class="w-9 h-9 rounded-full object-cover border border-gray-300">
                    @else
                        <div class="w-9 h-9 rounded-full bg-blue-900 text-white font-bold flex items-center justify-center text-sm shadow-sm flex-shrink-0">
                            {{ mb_substr(auth()->user()->name, 0, 1) }}
                        </div>
                    @endif

                    <div class="flex flex-col">
                        <span class="text-sm font-medium text-gray-700">
                            Вы комментируете как 
                            <a href="{{ route('profile.edit') }}" class="text-blue-600 hover:underline font-semibold">
                                {{ auth()->user()->name }}
                            </a>
                        </span>
                        @if(auth()->user()->group)
                            <span class="text-xs text-gray-500">
                                Группа: {{ auth()->user()->group->name }}
                            </span>
                        @endif
                    </div>
                </div>
            @else
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Ваше имя:</label>
                    <input type="text" name="user_name" required class="w-full p-2.5 border border-gray-300 rounded focus:outline-none focus:border-blue-500">
                </div>
            @endauth

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Комментарий:</label>
                <textarea name="body" rows="3" required class="w-full p-2.5 border border-gray-300 rounded focus:outline-none focus:border-blue-500" placeholder="Напишите ваш комментарий..."></textarea>
            </div>

            <button type="submit" class="bg-blue-600 text-white px-5 py-2 rounded hover:bg-blue-700 font-semibold transition shadow-sm">
                Отправить
            </button>
        </form>
    </div>
</section>