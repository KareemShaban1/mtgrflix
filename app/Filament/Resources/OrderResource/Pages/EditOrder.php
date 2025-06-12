<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use App\Http\Traits\Helper;
use App\Models\Status;
use App\Models\User;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditOrder extends EditRecord
{
    use Helper;
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }


    protected function afterSave(): void
    {
        $status = $this->data['status_id'];
        $status = Status::find($status);
        $user = User::find($this->data['user_id']);

        // $result =  $this->nerachat($user->formatted_phone, 'تم تغيير حالة الطلب الخاص بك  ' . ' إلى ' . $status->getTranslation('name', 'ar') . ' يرجى الدخول للمتجر لمعاينة الطلب');
        // \Log::info('Nerachat response:', $result);
        // dd($id);
        // $user = User::find($id);
        // if ($this->data['approved'] && !$user->account_approved) {
        //     $user->notify(new UserApprovedNotification);
        //     $user->account_approved = true;
        //     $user->save();
        // }
    }
}
