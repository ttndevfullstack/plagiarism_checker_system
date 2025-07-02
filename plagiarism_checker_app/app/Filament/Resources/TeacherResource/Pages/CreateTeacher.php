<?php

namespace App\Filament\Resources\TeacherResource\Pages;

use App\Filament\Resources\TeacherResource;
use App\Models\Teacher;
use App\Models\User;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CreateTeacher extends CreateRecord
{
    protected static string $resource = TeacherResource::class;

    protected function handleRecordCreation(array $data): Teacher
    {
        // Split user and student data
        $userData = [
            'first_name' => $data['first_name'] ?? null,
            'last_name' => $data['last_name'] ?? null,
            'email' => $data['email'] ?? null,
            'dob' => $data['dob'] ?? null,
            'phone' => $data['phone'] ?? null,
            'address' => $data['address'] ?? null,
            'password' => Hash::make('password'),
        ];

        $teacherData = [
            'joined_date' => $data['joined_date'] ?? now(),
        ];

        return DB::transaction(function () use ($userData, $teacherData) {
            $user = User::create($userData);
            $user->assignRole(User::STUDENT_ROLE);

            $teacher = new Teacher($teacherData);
            $teacher->user()->associate($user);
            $teacher->save();

            return $teacher;
        });
    }
}
