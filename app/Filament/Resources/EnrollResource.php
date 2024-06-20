<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Enroll;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Blade;
use Illuminate\Database\Eloquent\Model;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\EnrollResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\EnrollResource\RelationManagers;
use App\Filament\Resources\EnrollResource\RelationManagers\EnrolledSubjectsRelationManager;

class EnrollResource extends Resource
{
    protected static ?string $model = Enroll::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Enrolled Students';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('student_id')
                    ->relationship('student', 'first_name') // Assuming 'full_name' is a concatenation of first and last name
                    ->searchable() // Enable search functionality for the student dropdown
                    ->required(),
                Forms\Components\Select::make('course_id')
                    ->relationship('course', 'name')
                    ->required(),
                Forms\Components\Select::make('year_level')
                    ->options([
                        '1' => 'First Year',
                        '2' => 'Second Year',
                        '3' => 'Third Year',
                        '4' => 'Fourth Year',
                    ])
                    ->required(),
                Forms\Components\Select::make('block')
                    ->options([
                        'A' => 'Block A',
                        'B' => 'Block B',
                        'C' => 'Block C',
                        'D' => 'Block D',
                        'E' => 'Block E',
                        'F' => 'Block F',
                        'G' => 'Block G',
                        'H' => 'Block H',
                        'I' => 'Block I',
                        'J' => 'Block J',
                    ])
                    ->required(),
                Forms\Components\Select::make('year_id')
                    ->relationship('year', 'name')
                    ->required(),
                Forms\Components\Select::make('semester_id')
                    ->relationship('semester', 'name')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('student.student_number')
                    ->label('Student ID')
                    ->sortable(),
                Tables\Columns\TextColumn::make('student.full_name')
                    ->label('Student Name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('course.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('level')
                    ->label('Year Level')
                    ->sortable(),
                Tables\Columns\TextColumn::make('section')
                    ->label('Section')
                    ->sortable(),
                Tables\Columns\TextColumn::make('year.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('semester.name')
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
                SelectFilter::make('year_level')
                    ->options([
                        '1' => 'First Year',
                        '2' => 'Second Year',
                        '3' => 'Third Year',
                        '4' => 'Fourth Year',
                    ]),
                SelectFilter::make('block')
                    ->options([
                        'A' => 'Block A',
                        'B' => 'Block B',
                        'C' => 'Block C',
                        'D' => 'Block D',
                        'E' => 'Block E',
                        'F' => 'Block F',
                        'G' => 'Block G',
                        'H' => 'Block H',
                        'I' => 'Block I',
                        'J' => 'Block J',
                    ])
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('pdf')
                    ->label('PDF')
                    ->color('success')
                    ->icon('heroicon-o-rectangle-stack')
                    ->action(function (Model $record) {
                        return response()->streamDownload(function () use ($record) {
                            echo Pdf::loadHtml(
                                Blade::render('pdf', ['record' => $record])
                            )->stream();
                        }, $record->number . '.pdf');
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
            RelationManagers\EnrolledSubjectsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEnrolls::route('/'),
            'create' => Pages\CreateEnroll::route('/create'),
            'view' => Pages\ViewEnroll::route('/{record}'),
            'edit' => Pages\EditEnroll::route('/{record}/edit'),
        ];
    }
}
