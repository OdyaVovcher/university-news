<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Faculty;
use App\Models\Group;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Показать публичный профиль пользователя.
     */
    public function show(User $user): View
    {
        // Загружаем связи группы, специальности и факультета
        $user->load('group.specialty.faculty');

        return view('profile.show', [
            'user' => $user,
        ]);
    }

    /**
     * Показать форму редактирования профиля.
     */
    public function edit(Request $request): View
    {
        // Загружаем текущего пользователя со всеми связями
        $user = $request->user();
        $user->load('group.specialty.faculty');

        // Загружаем список всех групп для выпадающего списка
        $groups = Group::with('specialty.faculty')->orderBy('name')->get();

        return view('profile.edit', [
            'user' => $user,
            'groups' => $groups,
        ]);
    }

    /**
     * Обновить данные профиля.
     */
    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        // Валидация входных данных (включая аватар)
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'group_id' => ['nullable', 'exists:groups,id'],
            'avatar' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'], // до 2МБ
        ]);

        // Заполнение текстовых полей
        $user->fill([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'group_id' => $validated['group_id'] ?? null,
        ]);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        // Обработка загрузки нового аватара
        if ($request->hasFile('avatar')) {
            // Удаляем старый файл, если он существует
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }

            // Сохраняем новый аватар в storage/app/public/avatars
            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $path;
        }

        $user->save();

        return redirect()->route('profile.edit')->with('success', 'Профиль успешно обновлен!');
    }

}