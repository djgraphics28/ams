<?php

namespace App\Filament\Resources\ScheduleResource\RelationManagers;

use Illuminate\Support\Facades\DB;
use App\Models\EnrollSubject;
use App\Models\Student;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class StudentsRelationManager extends RelationManager
{
    protected static string $relationship = 'students';

    public function form(Form $form): Form
    {
        $scheduleId = $this->ownerRecord->id; // Assuming you have access to the owner record which is the Schedule

        return $form
            ->schema([
                Forms\Components\Select::make('student_id')
                    ->label('Student')
                    ->options($this->getFilteredStudents($scheduleId))
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('first_name')
            ->columns([
                Tables\Columns\ImageColumn::make('image'),
                Tables\Columns\TextColumn::make('student_number'),
                Tables\Columns\TextColumn::make('course.name'),
                Tables\Columns\TextColumn::make('first_name'),
                Tables\Columns\TextColumn::make('last_name'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->form($this->getFormSchema()) // Provide the form schema directly
                    ->mutateFormDataUsing(function (array $data) {
                        // Adding the schedule_id to the data array
                        $data['schedule_id'] = $this->ownerRecord->id;
                        return $data;
                    })
                    ->action(function (array $data) {
                        // Creating a new entry in the student_schedule table using query builder
                        DB::table('student_schedule')->insert($data);
                    }),
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
                Tables\Actions\DetachAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    private function getFilteredStudents($scheduleId)
    {
        // Get the subject ID from the schedule
        $subjectId = $this->ownerRecord->subject_id;

        // Query students enrolled in the specific subject
        return Student::whereHas('enrolls.enrolled_subjects', function ($query) use ($subjectId) {
            $query->where('subject_id', $subjectId);
        })->pluck('first_name', 'id');
    }

    protected function getFormSchema(): array
    {
        $scheduleId = $this->ownerRecord->id; // Assuming you have access to the owner record which is the Schedule

        return [
            Forms\Components\Select::make('student_id')
                ->label('Student')
                ->options($this->getFilteredStudents($scheduleId))
                ->required(),
        ];
    }
}
