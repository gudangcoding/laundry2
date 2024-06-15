<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use Filament\Actions;
use Filament\Facades\Filament;
use Filament\Resources\Pages\EditRecord;


use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;
use Livewire\Attributes\On;

class EditOrder extends EditRecord
{
    protected static string $resource = OrderResource::class;
    #[On('refreshForm')]
    public function refreshForm(): void
    {
        parent::refreshFormData(array_keys($this->record->toArray()));
        // dd($this->record->toArray());
    }


    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function afterSave()
    {

        $orderId = $this->record->id;
        Notification::make()
            ->success()
            ->title('Print Nota ini?')
            ->body('Akan membuka jendela baru untuk print')
            ->persistent()
            ->actions([
                Action::make('Order')
                    ->label('Print')
                    ->button()
                    ->color('success')
                    ->url(route('pdf.invoice', [
                        'id' => $orderId
                    ]), shouldOpenInNewTab: true),
            ])
            ->send();

        $this->halt();
    }
}
