<?php

namespace Database\Seeders;

use App\Models\Faculty;
use App\Models\Specialty;
use App\Models\Group;
use Illuminate\Database\Seeder;

class FacultyStructureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Структура факультетов, специальностей и групп
        $structure = [
            'Факультет информационных технологий' => [
                'Программная инженерия' => ['ПИ-201', 'ПИ-202', 'ПИ-301'],
                'Информационная безопасность' => ['ИБ-101', 'ИБ-201'],
                'Компьютерные науки' => ['КН-101', 'КН-201'],
            ],
            'Инженерно-экономический факультет' => [
                'Экономика предприятия' => ['ЭК-101', 'ЭК-201'],
                'Менеджмент и маркетинг' => ['МН-101', 'МН-301'],
                'Финансы и кредит' => ['ФК-201', 'ФК-401'],
            ],
            'Гуманитарный факультет' => [
                'Журналистика и медиа' => ['ЖУР-101', 'ЖУР-201'],
                'Иностранная филология' => ['ФИЛ-101', 'ФИЛ-301'],
            ],
        ];

        foreach ($structure as $facultyName => $specialties) {
            // Создаем или находим факультет
            $faculty = Faculty::firstOrCreate(['name' => $facultyName]);

            foreach ($specialties as $specialtyName => $groups) {
                // Создаем специальность, привязанную к факультету
                $specialty = Specialty::firstOrCreate([
                    'faculty_id' => $faculty->id,
                    'name' => $specialtyName,
                ]);

                foreach ($groups as $groupName) {
                    // Создаем группу, привязанную к специальности
                    Group::firstOrCreate([
                        'specialty_id' => $specialty->id,
                        'name' => $groupName,
                    ]);
                }
            }
        }
    }
}