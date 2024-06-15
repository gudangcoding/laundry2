<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProdukResource\Pages;
use App\Filament\Resources\ProdukResource\RelationManagers;
use App\Models\Produk;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProdukResource extends Resource
{
    protected static ?string $model = Produk::class;
    protected static ?string $title = "Produk";
    protected static ?string $navigationGroup = "Master Data";
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Produk')
                ->schema([
                    TextInput::make('name')
                    ->required()
                    ->label('Nama Produk'),
                Select::make('katagori_id')
                    ->relationship('kategori', 'name')
                    ->required()
                    ->createOptionForm(fn (Form $form) => KategoriResource::form($form) ?? [])
                    ->editOptionForm(fn (Form $form) => KategoriResource::form($form) ?? [])
                    ->label('Kategori'),
                TextInput::make('stok')
                    ->numeric()
                    ->required()
                    ->label('Stok'),
                FileUpload::make('image')
                    ->image()
                    ->required()
                    ->label('Gambar Produk'),
                TextInput::make('harga')
                    ->numeric()
                    ->required()
                    ->label('Harga'),
                Textarea::make('deskripsi')
                    ->required()
                    ->label('Deskripsi Produk'),
                ])->columns(2)

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name'),
                TextColumn::make('kategori.name')
                ->label('Nama Kategori'),
                TextColumn::make('harga'),
                TextColumn::make('stok'),
                //
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
            'index' => Pages\ListProduks::route('/'),
            'create' => Pages\CreateProduk::route('/create'),
            'edit' => Pages\EditProduk::route('/{record}/edit'),
        ];
    }
}
