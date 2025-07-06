<?php

namespace App\Filament\Resources\PlagiarismHistoryChartResource\Widgets;

use App\Models\PlagiarismHistory;
use App\Models\Subject;
use App\Models\ClassRoom;
use App\Models\Exam;
use App\Models\Student;
use Filament\Forms;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class PlagiarismHistoryChart extends ApexChartWidget
{
    protected static ?string $chartId = 'PlagiarismHistoryChart';

    protected static ?string $heading = 'Plagiarism History Overview';

    public function getColumnSpan(): int|string|array
    {
        return 'full';
    }

    protected function getFormSchema(): array
    {
        return [
            Forms\Components\Select::make('chart_type')
                ->label('Chart Type')
                ->options([
                    'bar' => 'Bar',
                    'area' => 'Area',
                ])
                ->default('bar')
                ->reactive(),
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
                ->visible(fn($get) => $get('filter_type') === 'subject')
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
                ->visible(fn($get) => $get('filter_type') === 'classroom')
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
                ->visible(fn($get) => $get('filter_type') === 'exam')
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
                ->visible(fn($get) => $get('filter_type') === 'student')
                ->reactive(),
        ];
    }

    /**
     * Chart options (series, labels, types, size, animations...)
     * https://apexcharts.com/docs/options
     *
     * @return array
     */
    protected function getOptions(): array
    {
        $filterType = $this->filterFormData['filter_type'] ?? 'subject';
        $selectedYear = now()->year;
        $selectedChartType = $this->filterFormData['chart_type'] ?? 'bar';

        $query = PlagiarismHistory::query();

        if (! auth()->user()->isAdmin()) {
            $classIds = auth()->user()->classrooms()->get()->pluck('id')->toArray();
            $query->whereIn('class_id', $classIds);
        }

        // Apply filter based on filter type
        if ($filterType === 'subject' && !empty($this->filterFormData['subject_id'])) {
            $query->where('subject_id', $this->filterFormData['subject_id']);
        } elseif ($filterType === 'classroom' && !empty($this->filterFormData['class_id'])) {
            $query->where('class_id', $this->filterFormData['class_id']);
        } elseif ($filterType === 'exam' && !empty($this->filterFormData['exam_id'])) {
            $query->where('exam_id', $this->filterFormData['exam_id']);
        } elseif ($filterType === 'student' && !empty($this->filterFormData['student_id'])) {
            // Filter by user_id of the selected student
            $student = Student::find($this->filterFormData['student_id']);
            if ($student && $student->user_id) {
                $query->where('uploaded_by', $student->user_id);
            } else {
                // If student or user_id not found, return empty result
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

        // Always show all 12 months
        $categories = [];
        $seriesData = [];
        $colors = [];
        foreach (range(1, 12) as $month) {
            $categories[] = date('M', strtotime("$selectedYear-$month-01"));
            $value = isset($data[$month]) ? round($data[$month]->avg_plagiarism, 2) : 0;
            $seriesData[] = $value;
            // Color logic
            if ($value >= 70) {
                $colors[] = '#dc2626'; // red
            } elseif ($value >= 40) {
                $colors[] = '#f59e42'; // orange/yellow
            } else {
                $colors[] = '#22c55e'; // green
            }
        }

        // Use selected chart type, default to 'bar'
        $chartType = $selectedChartType ?: 'bar';

        $seriesName = match ($filterType) {
            'subject' => 'Avg plagiarism by Subject',
            'classroom' => 'Avg plagiarism by Classroom',
            'exam' => 'Avg plagiarism by Exam',
            'student' => 'Avg plagiarism by Student',
            default => 'Average plagiarism rate',
        };

        // Only bar and area supported, so always include colors and plotOptions
        return [
            'chart' => [
                'type' => $chartType,
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
            'colors' => $colors,
            'plotOptions' => [
                'bar' => [
                    'borderRadius' => 3,
                    'horizontal' => false,
                    'distributed' => true,
                ],
                'area' => [
                    'distributed' => true,
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
