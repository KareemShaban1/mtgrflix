<?php

namespace App\Filament\Resources\UserResource\Pages;

use Filament\Actions;
use Filament\Actions\ImportAction;
use App\Filament\Imports\UserImporter;
use App\Filament\Resources\UserResource;
use Filament\Pages\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\Imports\Jobs\ImportCsv;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            
        ];
    }
}
