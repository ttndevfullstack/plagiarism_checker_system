<?php

namespace App\Filament\Resources\StudentResource\Pages;

use App\Filament\Resources\StudentResource;
use Filament\Resources\Pages\CreateRecord;
use App\Models\User;
use App\Models\Student;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CreateStudent extends CreateRecord
{
    protected static string $resource = StudentResource::class;

    protected function handleRecordCreation(array $data): Student
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

        $studentData = [
            'enrollment_date' => $data['enrollment_date'] ?? now(),
        ];

        return DB::transaction(function () use ($userData, $studentData) {
            $user = User::create($userData);
            $user->assignRole(User::STUDENT_ROLE);

            $student = new Student($studentData);
            $student->user()->associate($user);
            $student->save();

            return $student;
        });
    }
}
