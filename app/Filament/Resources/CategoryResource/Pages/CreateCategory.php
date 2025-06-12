<?php

namespace App\Filament\Resources\CategoryResource\Pages;

use App\Filament\Resources\CategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateCategory extends CreateRecord
{
    protected static string $resource = CategoryResource::class;

    protected function beforeFill(): void
    {
        // Runs before the form fields are populated with their default values.
    }

    protected function afterFill(): void
    {
        // Runs after the form fields are populated with their default values.
    }

    protected function beforeValidate(): void
    {
        // Runs before the form fields are validated when the form is submitted.
    }

    protected function afterValidate(): void
    {
        // Runs after the form fields are validated when the form is submitted.
    }

    protected function beforeCreate(): void
    {
        // dd(request()->all());
        // Runs before the form fields are saved to the database.
    }

    protected function afterCreate(): void
    {
        // Runs after the form fields are saved to the database.
    }
    protected function mutateFormDataBeforeCreate(array $data): array
    {
// dd($data);
        // $data['sender_id'] = auth()->user()->id;

        return $data;
        // dd ($data);
    }
}
