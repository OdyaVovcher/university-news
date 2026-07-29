<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Университетские Новости')</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800 flex flex-col min-h-screen">

    <!-- Общая Шапка -->
    <header class="bg-blue-900 text-white shadow-md">
        <div class="container mx-auto px-4 py-4 flex justify-between items-center">
            <a href="{{ route('news.index') }}" class="text-2xl font-bold tracking-wide">Университетские Новости</a>
            
            <div class="flex items-center space-x-4">
                @auth
                    <!-- 1. Личный кабинет -->
                    <a href="{{ route('profile.edit') }}" class="text-sm border border-blue-400 hover:bg-blue-800 px-3 py-1.5 rounded transition font-medium">
                        Личный кабинет
                    </a>

                    <!-- 2. Админ-панель (если админ) -->
                    @if(auth()->user()->is_admin)
                        <a href="/admin" class="text-sm bg-blue-600 hover:bg-blue-500 text-white px-3 py-1.5 rounded transition font-medium">
                            Админ-панель
                        </a>
                    @endif

                    <!-- 3. Дополнительные действия со страниц (например, «На главную») -->
                    @yield('header-actions')

                    <!-- 4. Кнопка «Выйти» (всегда в самом конце) -->
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="text-sm border border-red-400/50 bg-red-500/10 hover:bg-red-600 hover:border-red-600 text-white px-3 py-1.5 rounded font-medium transition">
                            Выйти
                        </button>
                    </form>
                @else
                    <!-- 3. Дополнительные действия для гостей (если есть) -->
                    @yield('header-actions')

                    <a href="{{ route('login') }}" class="text-sm hover:underline font-medium">Войти</a>
                    <a href="{{ route('register') }}" class="text-sm bg-blue-700 hover:bg-blue-800 px-3 py-1.5 rounded transition font-medium">
                        Регистрация
                    </a>
                @endauth
            </div>
        </div>
    </header>

    <!-- Уникальное содержимое страниц -->
    <main class="container mx-auto px-4 py-8 flex-grow @yield('main-class')">
        @yield('content')
    </main>

    <!-- Общий Подвал -->
    <footer class="bg-gray-900 text-gray-400 text-center py-4 mt-auto">
        <p>&copy; {{ date('Y') }} Университетский Новостной Портал</p>
    </footer>

    @stack('scripts')
</body>
</html>