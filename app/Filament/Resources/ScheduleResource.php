<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Schedule;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\ScheduleResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ScheduleResource\RelationManagers;
use HusamTariq\FilamentTimePicker\Forms\Components\TimePickerField;

class ScheduleResource extends Resource
{
    protected static ?string $model = Schedule::class;

    protected static ?string $navigationGroup = 'Registrar Management';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('sched_code')
                    ->required()
                    ->columnSpanFull()
                    ->maxLength(255),
                TimePickerField::make('start')->label('Start Time')->okLabel("Confirm")->cancelLabel("Cancel"),
                TimePickerField::make('end')->label('End Time')->okLabel("Confirm")->cancelLabel("Cancel"),
                Forms\Components\MultiSelect::make('days')
                    ->options([
                        'Mon' => 'Mon',
                        'Tue' => 'Tue',
                        'Wed' => 'Wed',
                        'Thu' => 'Thu',
                        'Fri' => 'Fri',
                        'Sat' => 'Sat',
                        'Sun' => 'Sun',
                    ])
                    ->required(),
                Forms\Components\Select::make('year_id')
                    ->relationship('year', 'name')
                    ->required(),
                Forms\Components\Select::make('semester_id')
                    ->relationship('semester', 'name')
                    ->required(),
                Forms\Components\Select::make('subject_id')
                    ->relationship('subject', 'name')
                    ->required(),
                Forms\Components\Select::make('instructor_id')
                    ->relationship('instructor', 'first_name'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('sched_code')
                    ->searchable(),
                Tables\Columns\TextColumn::make('start'),
                Tables\Columns\TextColumn::make('end'),
                Tables\Columns\TextColumn::make('days'),
                Tables\Columns\TextColumn::make('year.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('semester.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('subject.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('instructor.first_name')
                    ->numeric()
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
                SelectFilter::make('year')
                    ->relationship('year', 'name'),
                SelectFilter::make('semester')
                    ->relationship('semester', 'name'),
                SelectFilter::make('subject')
                    ->relationship('subject', 'name'),
                SelectFilter::make('instructor')
                    ->relationship('instructor', 'first_name')
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
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
            RelationManagers\StudentsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSchedules::route('/'),
            'create' => Pages\CreateSchedule::route('/create'),
            'view' => Pages\ViewSchedule::route('/{record}'),
            'edit' => Pages\EditSchedule::route('/{record}/edit'),
        ];
    }
}
