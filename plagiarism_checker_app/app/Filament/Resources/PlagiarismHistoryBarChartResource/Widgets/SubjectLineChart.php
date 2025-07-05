<?php

namespace App\Filament\Resources\PlagiarismHistoryChartResource\Widgets;

use App\Models\ClassRoom;
use App\Models\Exam;
use App\Models\PlagiarismHistory;
use App\Models\Student;
use App\Models\Subject;
use Filament\Forms;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class PlagiarismHistoryLineChart extends ApexChartWidget
{
    protected static ?string $chartId = 'PlagiarismHistoryLineChart';

    protected static ?string $heading = 'Subject Plagiarism History (Line Chart)';

    public function getColumnSpan(): int|string|array
    {
        return 'full';
    }

    protected function getFormSchema(): array
    {
        return [
            Forms\Components\Select::make('filter_type')
                ->label('Filter By')
                ->options([
                    'subject' => 'Subject',
                    'classroom' => 'Classroom',
                    'exam' => 'Exam',
                    'student' => 'Student',
                ])
                ->default('subject')
                ->reactive(),

            Forms\Components\Select::make('subject_id')
                ->label('Subject')
                ->options(
                    Subject::all()->mapWithKeys(function ($subject) {
                        return [$subject->id => $subject->name ?? 'Subject #' . $subject->id];
                    })
                )
                ->searchable()
                ->nullable()
                ->visible(fn ($get) => $get('filter_type') === 'subject')
                ->reactive(),

            Forms\Components\Select::make('class_id')
                ->label('Classroom')
                ->options(
                    ClassRoom::all()->mapWithKeys(function ($classroom) {
                        return [$classroom->id => $classroom->name ?? 'Classroom #' . $classroom->id];
                    })
                )
                ->searchable()
                ->nullable()
                ->visible(fn ($get) => $get('filter_type') === 'classroom')
                ->reactive(),

            Forms\Components\Select::make('exam_id')
                ->label('Exam')
                ->options(
                    Exam::all()->mapWithKeys(function ($exam) {
                        return [$exam->id => $exam->title ?? 'Exam #' . $exam->id];
                    })
                )
                ->searchable()
                ->nullable()
                ->visible(fn ($get) => $get('filter_type') === 'exam')
                ->reactive(),

            Forms\Components\Select::make('student_id')
                ->label('Student')
                ->options(
                    Student::with('user')->get()->mapWithKeys(function ($student) {
                        $name = $student->user && $student->user->full_name ? $student->user->full_name : 'Student #' . $student->id;
                        return [$student->id => $name];
                    })
                )
                ->searchable()
                ->nullable()
                ->visible(fn ($get) => $get('filter_type') === 'student')
                ->reactive(),
        ];
    }

    protected function getOptions(): array
    {
        $selectedYear = now()->year;
        $filterType = $this->filterFormData['filter_type'] ?? 'subject';

        $query = PlagiarismHistory::query();

        if ($filterType === 'subject' && !empty($this->filterFormData['subject_id'])) {
            $query->where('subject_id', $this->filterFormData['subject_id']);
        } elseif ($filterType === 'classroom' && !empty($this->filterFormData['class_id'])) {
            $query->where('class_id', $this->filterFormData['class_id']);
        } elseif ($filterType === 'exam' && !empty($this->filterFormData['exam_id'])) {
            $query->where('exam_id', $this->filterFormData['exam_id']);
        } elseif ($filterType === 'student' && !empty($this->filterFormData['student_id'])) {
            $student = Student::find($this->filterFormData['student_id']);
            if ($student && $student->user_id) {
                $query->where('uploaded_by', $student->user_id);
            } else {
                $query->whereRaw('1=0');
            }
        }

        $data = $query
            ->whereYear('created_at', $selectedYear)
            ->selectRaw("MONTH(created_at) as month, AVG(similarity_score) as avg_plagiarism")
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->keyBy('month');

        $categories = [];
        $seriesData = [];
        foreach (range(1, 12) as $month) {
            $categories[] = date('M', strtotime("$selectedYear-$month-01"));
            $seriesData[] = isset($data[$month]) ? round($data[$month]->avg_plagiarism, 2) : 0;
        }

        $seriesName = match ($filterType) {
            'subject' => 'Avg plagiarism by Subject',
            'classroom' => 'Avg plagiarism by Classroom',
            'exam' => 'Avg plagiarism by Exam',
            'student' => 'Avg plagiarism by Student',
            default => 'Average plagiarism rate',
        };

        return [
            'chart' => [
                'type' => 'line',
                'height' => 300,
            ],
            'series' => [
                [
                    'name' => $seriesName,
                    'data' => $seriesData,
                ],
            ],
            'xaxis' => [
                'categories' => $categories,
                'labels' => [
                    'style' => [
                        'fontFamily' => 'inherit',
                    ],
                ],
            ],
            'yaxis' => [
                'min' => 0,
                'max' => 100,
                'labels' => [
                    'style' => [
                        'fontFamily' => 'inherit',
                    ],
                ],
                'title' => [
                    'text' => 'Average plagiarism rate',
                ],
            ],
            'dataLabels' => [
                'enabled' => true,
                'style' => [
                    'colors' => ['#000000'],
                ],
            ],
        ];
    }
}
