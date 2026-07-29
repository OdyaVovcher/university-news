<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\View\View;

class PublicProfileController extends Controller
{
    /**
     * Показать публичную страницу профиля пользователя.
     */
    public function show(User $user): View
    {
        // Загружаем связи, чтобы показать группу и факультет на странице
        $user->load(['group.specialty.faculty']);

        return view('public-profile.show', compact('user'));
    }
}