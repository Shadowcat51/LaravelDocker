<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\produks;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Form;
use App\Models\Penjualans;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Repeater;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\BadgeColumn;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextInputColumn;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\PenjualansResource\Pages;
use App\Filament\Resources\PenjualansResource\RelationManagers;

class PenjualansResource extends Resource
{
    protected static ?string $model = Penjualans::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';
    protected static ?string $activeNavigationIcon = 'heroicon-s-shopping-cart';
    protected static ?string $navigationGroup = 'Belanja';
    protected static ?string $navigationLabel = 'Pesan';
    protected static ?string $modelLabel = 'Pesan';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Wizard::make([
                    Wizard\Step::make('Data Pelanggan')
                        ->schema([
                            Select::make('PelangganID')
                                ->preload()
                                ->searchable()
                                ->required()
                                ->relationship('pelanggan', 'NamaPelanggan'),
                        ]),
                    Wizard\Step::make('Order Produk')
                        ->schema([
                            Repeater::make('produk')
                                ->schema([
                                    Select::make('ProdukID')
                                        ->preload()
                                        ->searchable()
                                        ->required()
                                        ->relationship('produk', 'NamaProduk')
                                        ->live()
                                        ->afterStateUpdated(fn ($state, Set $set) => $set('Subtotal', produks::find($state)?->Harga ?? 0)),
                                    TextInput::make('JumlahProduk')
                                        ->numeric()
                                        ->required()
                                        ->default(1)
                                        ->live()
                                        ->afterStateUpdated(fn ($state, Get $get, Set $set) => $get('ProdukID') ? $set('Subtotal', produks::find($get('ProdukID'))->Harga * $state) : $set('Subtotal', 0)),
                                    TextInput::make('Subtotal')
                                        ->disabled()
                                        ->dehydrated()
                                        ->live()
                                        ->required()
                                        ->numeric(),
                                ])
                                ->columns(3)
                                ->relationship('detailpenjualan'),
                                TextInput::make('TotalHarga')
                                    ->required()
                                    ->numeric()
                                    ->default(0)
                                    ->mask(fn (Get $get) => collect($get('produk'))->pluck('Subtotal')->sum()),
                        ]),
                ])->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('pelanggan.NamaPelanggan')->label('Nama Pelanggan')->searchable()->toggleable(),
                TextColumn::make('pelanggan.Alamat')->disabled()->label('Alamat Pembeli')->searchable()->toggleable(),
                BadgeColumn::make('detailpenjualan.produk.NamaProduk')->color('success')->toggleable(),
                TextColumn::make('TotalHarga')
                    ->toggleable()
                    ->label('Total Harga')
                    ->prefix('Rp.')
                    ->numeric(
                        decimalPlaces: 0,
                        thousandsSeparator: '.',
                    )
                    ->summarize(Sum::make()->label('Total')),
                TextColumn::make('TanggalPenjualan')->dateTime('d M Y')->toggleable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                ActionGroup::make([
                    Tables\Actions\DeleteAction::make(),
                ]),
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
            'index' => Pages\ListPenjualans::route('/'),
            'create' => Pages\CreatePenjualans::route('/create'),
            'edit' => Pages\EditPenjualans::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string{
        return static::getModel()::count();
    }
}
