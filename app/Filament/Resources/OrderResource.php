<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers\OrderDetailRelationManager;
use App\Models\Order;
use App\Models\OrderDetail;
use Filament\Facades\Filament;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Order')
                    ->schema([
                        Select::make('customers_id')
                            ->relationship('customer', 'name')
                            ->searchable()
                            ->createOptionForm(fn (Form $form) => CustomerResource::form($form) ?? [])
                            ->editOptionForm(fn (Form $form) => CustomerResource::form($form) ?? [])
                            ->label('Customer'),
                        TextInput::make('qty')
                            ->reactive()
                            ->default(0)
                            ->numeric()
                            ->afterStateHydrated(function ($set, $get) {
                                $OrderId = $get('id');
                                if ($OrderId) {
                                    $sum = OrderDetail::where('orders_id', $OrderId)->sum('qty');
                                    $set('qty', $sum);
                                }
                            }),
                        TextInput::make('total')
                            ->reactive()
                            ->afterStateHydrated(function ($set, $get) {
                                $OrderId = $get('id');
                                if ($OrderId) {
                                    $sum = OrderDetail::where('orders_id', $OrderId)->sum('subtotal');
                                    $set('total', $sum);
                                }
                            })
                            ->default(0)
                            ->numeric(),
                    ])->columns(3),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('customers_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('qty')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total')
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
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Action::make('salesOrder')
                ->button()
                ->label('Print')
                ->url(fn (Order $record): string => route('pdf.invoice', [
                    'id' => $record->id
                ]), shouldOpenInNewTab: true),
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
            OrderDetailRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
