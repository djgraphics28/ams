<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Enroll;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\EnrollResource;
use App\Filament\Resources\EnrollResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\EnrollResource\RelationManagers;

class EnrollResource extends Resource
{
    protected static ?string $model = Enroll::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('student_id')
                    ->relationship('student', 'id')
                    ->required(),
                Forms\Components\Select::make('course_id')
                    ->relationship('course', 'name')
                    ->required(),
                Forms\Components\Select::make('academic_year_semester_id')
                    ->relationship('academic_year_semester', 'name')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('student.id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('course.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('academic_year_semester.name')
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
                //
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
            // EnrollResource::getRelations()
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
