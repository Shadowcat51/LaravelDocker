<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\UserResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\UserResource\Pages\EditUser;
use App\Filament\Resources\UserResource\Pages\CreateUser;
use App\Filament\Resources\UserResource\RelationManagers;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-circle';
    protected static ?string $navigationGroup = 'Registrasi';
    protected static ?string $navigationLabel = 'Akun';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->schema([
                        TextInput::make('name'),
                        TextInput::make('email')->unique(ignoreRecord:true),
                        TextInput::make('password')->password()->visible(fn($livewire) => $livewire instanceof CreateUser),
                        Select::make('role')
                            ->searchable()
                            ->options([
                                'admin'=>'admin',
                                'staff'=>'staff'
                            ]),
                    ])
                    ->columns(2),
                Section::make('New Password')
                    ->schema([
                        TextInput::make('new_password')->password(),
                        TextInput::make('new_password_confirmed')
                        ->password()
                        ->same('password')
                        ->requiredWith('password'),
                    ])
                    ->columns(2)
                    ->visible(fn($livewire) => $livewire instanceof EditUser),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable(),
                TextColumn::make('email')->disabled()->searchable(),
                SelectColumn::make('role')
                    ->options([
                        'admin' => 'Admin',
                        'staff' => 'Petugas',
                    ]),
                TextColumn::make('created_at')->label('Dibuat Tanggal')->dateTime('d M Y'),
            ])
            ->filters([
                SelectFilter::make('role')
                    ->options([
                        'admin' => 'Admin',
                        'staff' => 'Petugas'
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make()
                ])
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool
    {
        return auth()->user()->role === 'admin';
    }
}
