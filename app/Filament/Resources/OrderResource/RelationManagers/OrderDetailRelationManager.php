<?php

namespace App\Filament\Resources\OrderResource\RelationManagers;

use App\Filament\Resources\ProdukResource;
use App\Models\Order;
use App\Models\Produk;
use Filament\Forms;
use Livewire\Component;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OrderDetailRelationManager extends RelationManager
{
    protected static string $relationship = 'OrderDetail';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('produks_id')
                    ->relationship('produk', 'name')
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function ($state, $get, $set) {
                        // dd($state);
                        $ketemu = Produk::find($state);
                        // dd($ketemu);
                        $harga = $ketemu->harga;
                        $subtotal = $harga * $get('qty');
                        $set('harga', $harga);
                        $set('subtotal', $subtotal);
                    })
                    ->createOptionForm(fn (Form $form) => ProdukResource::form($form) ?? [])
                    ->editOptionForm(fn (Form $form) => ProdukResource::form($form) ?? [])
                    ->label('Produk'),
                TextInput::make('qty')
                    ->reactive()
                    ->afterStateUpdated(function ($state, $get, $set) {
                        $produk = Produk::find($get('produks_id'));
                        // dd($state);
                        $subtotal = 0;
                        $subtotal = $produk->harga * $state;
                        $set('subtotal', $subtotal);
                    })
                    ->default(1)
                    ->label('Jumlah'),
                TextInput::make('harga')
                    ->readOnly()
                    ->label('Harga'),
                TextInput::make('subtotal')
                    ->reactive()
                    ->readOnly()
                    ->label('SubTotal'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('orders_id')
            ->columns([
                TextColumn::make('produk.name')
                    ->label('Nama Produk'),
                TextColumn::make('harga')
                    ->label('Harga'),
                TextColumn::make('qty')
                    ->label('QTY'),
                TextColumn::make('subtotal')
                    ->label('Sub Total'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->after(function (Component $livewire,  $record) {
                        $orderId =  $record->orders_id;
                        if ($orderId) {
                            $order = Order::find($orderId);
                            $sum = $order->OrderDetail->sum('subtotal');
                            $qty = $order->OrderDetail->sum('qty');
                            $order->total = $sum;
                            $order->qty = $qty;
                            $order->update();
                        }
                        return $livewire->dispatch('refreshForm');
                    }),

            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->after(function (Component $livewire,  $record) {
                        $salesOrderId = $record->orders_id;
                        if ($salesOrderId) {
                            $salesOrder = Order::find($salesOrderId);
                            $sum = $salesOrder->OrderDetail->sum('subtotal');
                            $qty = $salesOrder->OrderDetail->sum('qty');
                            $salesOrder->total = $sum;
                            $salesOrder->qty = $qty;
                            $salesOrder->update();
                        }
                        return $livewire->dispatch('refreshForm');
                    }),
                Tables\Actions\DeleteAction::make()
                    ->after(function (Component $livewire, $record) {
                        if ($record) {
                            $salesOrderId = $record->orders_id;
                            // Hapus semua order details terkait
                            $orderDetails = $record->orderDetails;
                            foreach ($orderDetails as $orderDetail) {
                                $orderDetail->delete();
                            }
                            // Hapus order
                            $salesOrder = Order::find($salesOrderId);
                            if ($salesOrder) {
                                $salesOrder->delete();
                            }
                        }
                        return $livewire->dispatch('refreshForm');
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->after(function (Component $livewire, $records) {
                            foreach ($records as $record) {
                                if ($record) {
                                    $salesOrderId = $record->orders_id;
                                    // Hapus semua order details terkait
                                    $orderDetails = $record->orderDetails;
                                    foreach ($orderDetails as $orderDetail) {
                                        $orderDetail->delete();
                                    }
                                    // Hapus order
                                    $salesOrder = Order::find($salesOrderId);
                                    if ($salesOrder) {
                                        $salesOrder->delete();
                                    }
                                }
                            }
                            $livewire->dispatch('refreshForm');
                        }),
                ]),
            ]);
    }
}
