<?php

namespace App\Filament\Resources\OrderResource\RelationManagers;

use App\Models\Pengiriman;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PengirimanRelationManager extends RelationManager
{
    protected static string $relationship = 'Pengiriman';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                DatePicker::make('tgl_kirim')
                    ->label('Tanggal Kirim'),
                TextInput::make('nama_pengirim')
                    ->label('Nama pengirim'),
                TextInput::make('nama_penerima')
                    ->label('Nama Penerima'),
                Select::make('status')
                    ->options([
                        'proses' => 'Proses',
                        'kirim' => 'kirim',
                        'selesai' => 'selesai'
                    ])
                    ->label('Status Pengiriman'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('orders_id')
            ->columns([
                TextColumn::make('orders_id'),
                TextColumn::make('tgl_kirim'),
                TextColumn::make('nama_pengirim'),
                TextColumn::make('nama_penerima'),

                SelectColumn::make('status')
                    ->options(function () {
                        return [
                            'proses' => 'proses',
                            'kirim' => 'kirim',
                            'selesai' => 'selesai',
                            'selesai' => 'selesai',
                        ];
                    })
                    ->getStateUsing(fn ($record) => $record->status),
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
