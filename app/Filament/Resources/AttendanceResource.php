<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use App\Models\Attendance;
use Filament\Tables\Table;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Resources\Resource;
use App\Models\AcademicYearSemester;
use Illuminate\Support\Facades\Blade;
use Illuminate\Database\Eloquent\Model;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\AttendanceResource\Pages;
use App\Filament\Resources\AttendanceResource\RelationManagers;
use Malzariey\FilamentDaterangepickerFilter\Filters\DateRangeFilter;

class AttendanceResource extends Resource
{
    protected static ?string $model = Attendance::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\DateTimePicker::make('time_in')
                    ->required(),
                Forms\Components\TextInput::make('scanned_by')
                    ->required()
                    ->numeric(),
                Forms\Components\Select::make('student_id')
                    ->relationship('student', 'first_name')
                    ->required(),
                Forms\Components\Select::make('schedule_id')
                    ->relationship('schedule', 'sched_code')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('time_in')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('student.student_number')
                    ->label('Student ID')
                    ->sortable(),
                Tables\Columns\TextColumn::make('student.full_name')
                    ->label('Student Name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('schedule.instructor.full_name')
                    ->label('Scanned by')
                    ->sortable(),
                Tables\Columns\TextColumn::make('schedule.sched_code')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                DateRangeFilter::make('created_at')->defaultToday(),
                SelectFilter::make('schedule')
                    ->relationship('schedule', 'sched_code'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('pdf')
                ->label('PDF')
                ->color('success')
                ->action(function (Model $record) {
                    return response()->streamDownload(function () use ($record) {
                        echo Pdf::loadHtml(
                            Blade::render('pdf/attendance-pdf', ['record' => $record])
                        )->stream();
                    }, $record->id . '.pdf');
                }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAttendances::route('/'),
            // 'create' => Pages\CreateAttendance::route('/create'),
            'edit' => Pages\EditAttendance::route('/{record}/edit'),
        ];
    }
}
