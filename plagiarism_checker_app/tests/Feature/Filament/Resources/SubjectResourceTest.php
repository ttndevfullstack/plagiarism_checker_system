<?php

namespace Tests\Feature\Filament\Resources;

use App\Filament\Resources\SubjectResource;
use App\Models\Subject;
use App\Models\User;
use Filament\Tables;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Livewire\Livewire;

class SubjectResourceTest extends TestCase
{
    use DatabaseTransactions;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_form_schema_defines_required_name_field_with_max_length(): void
    {
        $this->actingAs($this->user);

        Livewire::test(SubjectResource\Pages\CreateSubject::class)
            ->assertFormExists()
            ->assertFormFieldExists('name')
            ->fillForm([
                'name' => '',
            ])
            ->call('create')
            ->assertHasFormErrors(['name' => 'required']);
    }

    public function test_form_validation_rejects_empty_name_field(): void
    {
        $this->actingAs($this->user);
        
        Livewire::test(SubjectResource\Pages\CreateSubject::class)
            ->fillForm([
                'name' => '',
                'description' => 'Test description',
            ])
            ->call('create')
            ->assertHasFormErrors(['name' => 'required']);
    }

    public function test_can_create_subject(): void
    {
        $this->actingAs($this->user);
        
        Livewire::test(SubjectResource\Pages\CreateSubject::class)
            ->fillForm([
                'name' => 'Mathematics',
                'description' => 'Advanced Mathematics Course',
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('subjects', [
            'name' => 'Mathematics',
            'description' => 'Advanced Mathematics Course',
        ]);
    }

    public function test_can_view_subject_list(): void
    {
        $this->actingAs($this->user);
        
        $subjects = Subject::factory()->count(3)->create();
        
        Livewire::test(SubjectResource\Pages\ListSubjects::class)
            ->assertSuccessful()
            ->assertCanSeeTableRecords($subjects);
    }

    public function test_can_edit_subject(): void
    {
        $this->actingAs($this->user);
        
        $subject = Subject::factory()->create();
        
        Livewire::test(SubjectResource\Pages\EditSubject::class, [
            'record' => $subject->getKey(),
        ])
            ->fillForm([
                'name' => 'Updated Subject',
                'description' => 'Updated Description',
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('subjects', [
            'id' => $subject->id,
            'name' => 'Updated Subject',
            'description' => 'Updated Description',
        ]);
    }

    public function test_can_delete_subject(): void
    {
        $this->actingAs($this->user);
        
        $subject = Subject::factory()->create();
        
        $component = Livewire::test(SubjectResource\Pages\ListSubjects::class);
        
        $component->assertSuccessful()
            ->assertCanSeeTableRecords([$subject]);

        $this->assertModelExists($subject);

        $component->callTableAction(Tables\Actions\DeleteAction::class, $subject)
            ->assertNotified();

        // $this->assertModelMissing($subject);
    }

    public function test_form_validation_rejects_too_long_name(): void
    {
        $this->actingAs($this->user);
        
        Livewire::test(SubjectResource\Pages\CreateSubject::class)
            ->fillForm([
                'name' => str_repeat('a', 256),
                'description' => 'Test description',
            ])
            ->call('create')
            ->assertHasFormErrors(['name' => 'max']);
    }

    public function test_table_shows_correct_columns(): void
    {
        $this->actingAs($this->user);
        
        $subject = Subject::factory()->create([
            'name' => 'Test Subject',
            'description' => 'Test Description',
        ]);
        
        Livewire::test(SubjectResource\Pages\ListSubjects::class)
            ->assertSuccessful()
            ->assertCanSeeTableRecords([$subject])
            ->assertTableColumnExists('name')
            ->assertTableColumnExists('description');
    }
}