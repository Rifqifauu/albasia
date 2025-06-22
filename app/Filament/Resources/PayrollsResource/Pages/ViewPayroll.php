<?php

namespace App\Filament\Resources\PayrollsResource\Pages;

use App\Filament\Resources\PayrollsResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewPayroll extends ViewRecord 
{
    protected static string $resource = PayrollsResource::class;
    
    protected static string $view = 'filament.resources.payrolls-resource.pages.view-payroll';

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
    
}