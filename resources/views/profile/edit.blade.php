@extends('layouts.app')

@section('title', 'Личный кабинет студента')
@section('main-class', 'max-w-4xl mb-12')

@section('header-actions')
    <a href="{{ route('news.index') }}" class="text-sm border border-blue-400 hover:bg-blue-800 px-3 py-1.5 rounded transition">
        &larr; На главную
    </a>
@endsection

@section('content')
    <div class="bg-white rounded-lg shadow-sm p-6 md:p-8 border border-gray-200">
        <h1 class="text-3xl font-extrabold text-gray-900 mb-6 pb-4 border-b border-gray-200">
            Личный кабинет студента
        </h1>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PATCH')

            <!-- Основная информация -->
            <div class="mb-8">
                <h3 class="text-xl font-bold text-gray-800 mb-4 pb-2 border-b border-gray-100">
                    Основная информация
                </h3>

                <!-- Блок Аватарки -->
                <div class="mb-6 flex items-center gap-6">
                    <div class="relative">
                        @if($user->avatar)
                            <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}" class="w-20 h-20 rounded-full object-cover border-2 border-blue-600 shadow-sm">
                        @else
                            <div class="w-20 h-20 rounded-full bg-blue-900 text-white flex items-center justify-center font-bold text-2xl border-2 border-blue-600 shadow-sm">
                                {{ mb_substr($user->name, 0, 1) }}
                            </div>
                        @endif
                    </div>

                    <div class="flex-grow">
                        <label for="avatar" class="block text-gray-700 text-sm font-bold mb-1">Аватар профиля:</label>
                        <input type="file" name="avatar" id="avatar" accept="image/*"
                               class="text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                        <p class="text-xs text-gray-500 mt-1">Форматы: JPG, PNG, WEBP (до 2 МБ)</p>
                        @error('avatar') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="mb-4">
                    <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Имя и Фамилия:</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required 
                           class="w-full p-2.5 border border-gray-300 rounded focus:outline-none focus:border-blue-500">
                    @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="mb-4">
                    <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email:</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required 
                           class="w-full p-2.5 border border-gray-300 rounded focus:outline-none focus:border-blue-500">
                    @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- Учебные данные -->
            <div class="mb-8">
                <h3 class="text-xl font-bold text-gray-800 mb-4 pb-2 border-b border-gray-100">
                    Учебные данные
                </h3>

                @if($user->group)
                    <div class="mb-6 p-4 bg-blue-50 border border-blue-100 rounded-lg text-sm text-blue-900">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                            <p><strong>Факультет:</strong> {{ $user->faculty?->name ?? 'Не определен' }}</p>
                            <p><strong>Специальность:</strong> {{ $user->group?->specialty?->name ?? 'Не определена' }}</p>
                        </div>
                    </div>
                @endif

                <div class="mb-4">
                    <label for="group_id" class="block text-gray-700 text-sm font-bold mb-2">Академическая группа:</label>
                    <select name="group_id" id="group_id" class="w-full p-2.5 border border-gray-300 rounded focus:outline-none focus:border-blue-500 bg-white">
                        <option value="">-- Выберите группу --</option>
                        @foreach($groups as $group)
                            <option value="{{ $group->id }}" {{ old('group_id', $user->group_id) == $group->id ? 'selected' : '' }}>
                                {{ $group->name }} ({{ $group->specialty?->faculty?->name ?? 'Без факультета' }})
                            </option>
                        @endforeach
                    </select>
                    @error('group_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- Панель с кнопками -->
            <div class="pt-6 mt-8 border-t border-gray-200 flex flex-col sm:flex-row items-center justify-between gap-4">
                <a href="{{ route('news.index') }}" 
                   class="w-full sm:w-auto text-center bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold px-5 py-2.5 rounded transition">
                    &larr; На главную
                </a>

                <button type="submit" 
                        class="w-full sm:w-auto bg-blue-900 hover:bg-blue-800 text-white font-semibold px-6 py-2.5 rounded shadow transition">
                    Сохранить изменения
                </button>
            </div>
        </form>
    </div>
@endsection