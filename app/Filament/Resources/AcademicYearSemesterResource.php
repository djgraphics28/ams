<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AcademicYearSemesterResource\Pages;
use App\Filament\Resources\AcademicYearSemesterResource\RelationManagers;
use App\Models\AcademicYearSemester;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AcademicYearSemesterResource extends Resource
{
    protected static ?string $model = AcademicYearSemester::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Toggle::make('is_active')
                    ->required(),
                // Forms\Components\TextInput::make('year_id')
                //     ->required()
                //     ->numeric(),
                // Forms\Components\TextInput::make('semester_id')
                //     ->required()
                //     ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean(),
                // Tables\Columns\TextColumn::make('year_id')
                //     ->numeric()
                //     ->sortable(),
                // Tables\Columns\TextColumn::make('semester_id')
                //     ->numeric()
                //     ->sortable(),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAcademicYearSemesters::route('/'),
            'create' => Pages\CreateAcademicYearSemester::route('/create'),
            'edit' => Pages\EditAcademicYearSemester::route('/{record}/edit'),
        ];
    }
}
