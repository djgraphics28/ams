<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Ramsey\Uuid\Uuid;
use App\Models\Student;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Forms\Components\Password;
use Filament\Resources\Resource;
use Filament\Forms\Components\ViewField;
use Illuminate\Database\Eloquent\Builder;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Filament\Resources\StudentResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\StudentResource\RelationManagers;

class StudentResource extends Resource
{
    protected static ?string $model = Student::class;

    protected static ?string $navigationGroup = 'Persons Management';
    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function form(Form $form): Form
    {
        $qr_code = Uuid::uuid4()->toString();
        // Get the last student number from the database
        $lastStudent = Student::orderBy('id', 'desc')->first();
        if ($lastStudent) {
            // Extract the numeric part of the student number (e.g., from "20-00001" to "00001")
            $lastStudentNumber = intval(substr($lastStudent->student_number, 3)); // Extract numeric part and convert to integer
        } else {
            // If no students exist yet, start with 0
            $lastStudentNumber = 0;
        }

        // Generate the next student number in the format "20-XXXXX"
        $nextStudentNumber = '20-' . str_pad($lastStudentNumber + 1, 5, '0', STR_PAD_LEFT);

        return $form
            ->schema([

                Forms\Components\FileUpload::make('image')
                    ->image()
                    ->columnSpanFull(),
                // Forms\Components\ViewField::make('qr_code')
                // ->view('components.qr-code', compact('qr_code')),
                Forms\Components\TextInput::make('student_number')
                    ->readOnly()
                    ->default($nextStudentNumber),
                Forms\Components\TextInput::make('qr_code')
                    ->default($qr_code)
                    ->readOnly(),
                Forms\Components\Select::make('course_id')
                    ->relationship('course', 'name')
                    ->required(),
                Forms\Components\TextInput::make('first_name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('middle_name')
                    ->maxLength(255),
                Forms\Components\TextInput::make('last_name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('ext_name')
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('phone')
                    ->tel()
                    ->maxLength(255),
                Forms\Components\DatePicker::make('birth_date'),
                Forms\Components\Select::make('gender')
                    ->options([
                        'male' => 'Male',
                        'female' => 'Female',
                    ]),
                Forms\Components\Select::make('academic_year_semester_id')
                    ->relationship('academic_year_semester', 'name'),
                Forms\Components\TextInput::make('password')
                    ->label('Password'),
                // ->hidden(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image'),
                Tables\Columns\TextColumn::make('student_number')
                    ->searchable(),
                // Tables\Columns\TextColumn::make('qr_code')
                //     ->searchable(),
                Tables\Columns\TextColumn::make('first_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('middle_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('last_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('ext_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                    ->searchable(),
                Tables\Columns\TextColumn::make('birth_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('gender'),
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
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
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
            'index' => Pages\ListStudents::route('/'),
            'create' => Pages\CreateStudent::route('/create'),
            'view' => Pages\ViewStudent::route('/{record}'),
            'edit' => Pages\EditStudent::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
