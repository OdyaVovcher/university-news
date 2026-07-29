<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Faculty;
use App\Models\Specialty;
use App\Models\Group;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Создаем тестовый факультет
        $faculty = Faculty::create([
            'name' => 'Факультет информационных технологий',
        ]);

        // 2. Создаем специальность
        $specialty = Specialty::create([
            'faculty_id' => $faculty->id,
            'name' => 'Программная инженерия',
            'code' => '09.03.04',
        ]);

        // 3. Создаем группу
        $group = Group::create([
            'specialty_id' => $specialty->id,
            'name' => 'ПО-21',
            'course' => 2,
        ]);

        // 4. Создаем тестового пользователя/администратора
        User::create([
            'name' => 'Администратор',
            'email' => 'admin@test.com',
            'password' => Hash::make('password'),
            'group_id' => $group->id,
            'is_admin' => true,
        ]);

        // 5. Создаем категории
        $categories = [
            'Программирование',
            'Дизайн',
            'Маркетинг',
        ];

        foreach ($categories as $name) {
            Category::create([
                'name' => $name,
                'slug' => Str::slug($name), // Создаст: 'programmirovanie', 'dizajn', 'marketing'
            ]);
        }
    }
}
