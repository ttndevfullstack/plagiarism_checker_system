<?php

namespace Tests\Unit\Models;

use App\Models\Subject;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class SubjectTest extends TestCase
{
    use DatabaseTransactions;

    public function test_subject_can_be_created(): void
    {
        $subject = Subject::factory()->create([
            'name' => 'Test Subject',
            'description' => 'Test Description',
        ]);

        $this->assertInstanceOf(Subject::class, $subject);
        $this->assertEquals('Test Subject', $subject->name);
        $this->assertEquals('Test Description', $subject->description);
    }

    public function test_subject_has_required_attributes(): void
    {
        $subject = new Subject();
        
        $fillable = $subject->getFillable();
        
        $this->assertContains('name', $fillable);
        $this->assertContains('description', $fillable);
    }

    public function test_subject_attributes_are_mass_assignable(): void
    {
        $attributes = [
            'name' => 'Test Subject',
            'description' => 'Test Description',
        ];

        $subject = Subject::create($attributes);

        $this->assertEquals($attributes['name'], $subject->name);
        $this->assertEquals($attributes['description'], $subject->description);
    }
}
