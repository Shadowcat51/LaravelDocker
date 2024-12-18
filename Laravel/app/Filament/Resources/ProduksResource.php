<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Produks;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Card;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\BadgeColumn;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\ProduksResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ProduksResource\RelationManagers;

class ProduksResource extends Resource
{
    protected static ?string $model = Produks::class;

    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';
    protected static ?string $activeNavigationIcon = 'heroicon-s-squares-2x2';
    protected static ?string $navigationGroup = 'Belanja';
    protected static ?string $navigationLabel = 'Produk';
    protected static ?string $modelLabel = 'Produk';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->schema([
                        TextInput::make('NamaProduk')->unique(ignoreRecord:true)->required(),
                        TextInput::make('Harga')->numeric()->required(),
                        TextInput::make('Stok')->numeric()->required(),
                    ])
                    ->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('NamaProduk')->searchable(),
                TextColumn::make('Harga')->numeric()->searchable(),
                BadgeColumn::make('Stok')
                    ->searchable()
                    ->numeric()
                    ->colors([
                    'primary' => fn ($state): int => $state > 0,
                    'badge' => fn ($state): int => $state === 0,
                    'success' => fn ($state): int => $state >= 20,
                    ])
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                ActionGroup::make([
                    Tables\Actions\DeleteAction::make(),
                ])
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
            'index' => Pages\ManageProduks::route('/'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('Stok', '=', '0')->count();
    }
}
