<?php

namespace App\Filament\Resources\OrderResource\RelationManagers;

use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PembayaranRelationManager extends RelationManager
{
    protected static string $relationship = 'Pembayaran';

    public function form(Form $form): Form
    {

        return $form
            ->schema([
                TextInput::make('total_bayar')
                    ->readOnly()
                    ->default(function ($livewire) {
                        $order = $livewire->ownerRecord;
                        return $order ? $order->total : 0;
                    })
                    ->label('Total Bayar'),
                TextInput::make('diskon')
                    ->default(0)
                    ->live(onBlur: true)
                    ->afterStateUpdated(function ($state, $set, $get) {
                        $total_bayar = $get('total_bayar') ?? 0;
                        $diskon = $state ?? 0;
                        $dibayar = $get('dibayar') ?? 0;
                        $kembalian = $dibayar - $total_bayar + $diskon;
                        $set('kembali', $kembalian);
                    })
                    ->label('Diskon'),
                TextInput::make('dibayar')
                    ->default(0)
                    ->live(onBlur: true)
                    ->afterStateUpdated(function ($state, $set, $get) {
                        $total_bayar = $get('total_bayar') ?? 0;
                        $diskon = $get('diskon') ?? 0;
                        $dibayar = $state ?? 0;
                        $kembalian = $dibayar - $total_bayar + $diskon;
                        $set('kembali', $kembalian);
                    })
                    ->label('Di Bayar'),
                TextInput::make('kembali')
                    ->default(0)
                    ->readOnly()
                    ->label('Kembalian'),

            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('orders_id')
            ->columns([
                TextColumn::make('orders_id'),
                TextColumn::make('total_bayar'),
                TextColumn::make('diskon'),
                TextColumn::make('dibayar'),
                TextColumn::make('kembali'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
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
}
