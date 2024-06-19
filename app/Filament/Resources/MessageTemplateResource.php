<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MessageTemplateResource\Pages;
use App\Filament\Resources\MessageTemplateResource\RelationManagers;
use App\Models\MessageTemplate;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MessageTemplateResource extends Resource
{
    protected static ?string $model = MessageTemplate::class;

    protected static ?string $navigationGroup = 'Settings Management';

    protected static ?string $navigationIcon = 'heroicon-o-envelope';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('type')
                    ->options([
                        'sms' => 'SMS',
                        'email' => 'EMAIL',
                    ])
                    ->required(),
                Forms\Components\TextInput::make('title')->required(),
                Forms\Components\Select::make('level_of_offense')
                    ->options([
                        1 => '1st Offense',
                        2 => '2nd Offense',
                        3 => '3rd Offense',
                    ])->required(),
                Forms\Components\RichEditor::make('message')->columnSpanFull()->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('type')
                    ->searchable(),
                Tables\Columns\TextColumn::make('level_of_offense')
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
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageMessageTemplates::route('/'),
        ];
    }
}
