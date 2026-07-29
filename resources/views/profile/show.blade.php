@extends('layouts.app')

@section('title', 'Профиль пользователя ' . $user->name)
@section('main-class', 'max-w-4xl mb-12')

@section('header-actions')
    <a href="{{ route('news.index') }}" class="text-sm border border-blue-400 hover:bg-blue-800 px-3 py-1.5 rounded transition">
        &larr; На главную
    </a>
@endsection

@section('content')
    <div class="bg-white rounded-lg shadow-sm p-6 md:p-8 border border-gray-200">
        
        <!-- Шапка профиля -->
        <div class="flex flex-col sm:flex-row items-center gap-6 mb-8 pb-6 border-b border-gray-200">
            <!-- Большой аватар -->
            @if($user->avatar)
                <img src="{{ asset('storage/' . $user->avatar) }}" 
                     alt="{{ $user->name }}" 
                     class="w-24 h-24 rounded-full object-cover border-2 border-gray-200 shadow-sm">
            @else
                <div class="w-24 h-24 rounded-full bg-blue-900 text-white font-bold flex items-center justify-center text-4xl shadow-sm border-2 border-blue-950 flex-shrink-0">
                    {{ mb_substr($user->name, 0, 1) }}
                </div>
            @endif

            <div class="text-center sm:text-left">
                <h1 class="text-3xl font-extrabold text-gray-900 mb-1">
                    {{ $user->name }}
                </h1>
                <p class="text-gray-500">Зарегистрирован: {{ $user->created_at->format('d.m.Y') }}</p>
                
                @if(auth()->id() === $user->id)
                    <a href="{{ route('profile.edit') }}" class="mt-3 inline-block text-sm bg-gray-100 hover:bg-gray-200 text-gray-700 px-3 py-1 rounded transition">
                        Редактировать мой профиль
                    </a>
                @endif
            </div>
        </div>

        <!-- Учебные данные -->
        <div class="mb-8">
            <h3 class="text-xl font-bold text-gray-800 mb-5">
                Учебная информация
            </h3>

            @if($user->group)
                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                    
                    <!-- Группа -->
                    <div class="bg-blue-50 border border-blue-100 p-5 rounded-xl shadow-inner-sm text-center">
                        <div class="text-xs text-blue-600 uppercase font-semibold tracking-wider mb-1">Группа</div>
                        <div class="text-2xl font-bold text-blue-950">{{ $user->group->name }}</div>
                    </div>

                    <!-- Специальность -->
                    <div class="bg-gray-50 border border-gray-100 p-5 rounded-xl md:col-span-2 shadow-inner-sm">
                        <div class="text-xs text-gray-500 uppercase font-semibold tracking-wider mb-1">Специальность</div>
                        <div class="text-lg font-semibold text-gray-800">{{ $user->group->specialty?->name ?? 'Не указана' }}</div>
                    </div>

                    <!-- Факультет -->
                    <div class="bg-gray-50 border border-gray-100 p-5 rounded-xl md:col-span-3 shadow-inner-sm">
                        <div class="text-xs text-gray-500 uppercase font-semibold tracking-wider mb-1">Факультет</div>
                        <div class="text-lg font-semibold text-gray-800">{{ $user->faculty?->name ?? 'Не указан' }}</div>
                    </div>

                </div>
            @else
                <div class="bg-gray-100 border border-gray-200 text-gray-600 p-6 rounded-lg text-center italic">
                    Учебная информация пока не заполнена.
                </div>
            @endif
        </div>

    </div>
@endsection