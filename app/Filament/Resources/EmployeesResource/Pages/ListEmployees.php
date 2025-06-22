<?php

namespace App\Filament\Resources\EmployeesResource\Pages;

use App\Filament\Resources\EmployeesResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Storage;
use League\Csv\Reader;
use App\Models\Employee;

class ListEmployees extends ListRecords
{
    protected static string $resource = EmployeesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),

            Action::make('Import CSV')
                ->label('Import CSV')
                                ->icon('heroicon-m-arrow-down-tray')

                ->color('success')
                ->form([
                    FileUpload::make('csv_file')
                        ->label('Upload CSV File')
                        ->required()
                        ->acceptedFileTypes(['text/csv', 'text/plain'])
                        ->disk('local') // disimpan di storage/app
                        ->directory('temp-uploads'),
                ])
                ->action(function (array $data): void {
                    $path = storage_path('app/' . $data['csv_file']);
                    $csv = Reader::createFromPath($path, 'r');
                    $csv->setHeaderOffset(0); // baris pertama sebagai header

                    foreach ($csv->getRecords() as $record) {
                        Employee::create([
                            'nama' => $record['nama'],
                            'nik' => $record['nik'],
                            'bagian' => $record['bagian'],
                            // sesuaikan dengan kolom yang ada di tabel
                        ]);
                    }

                    Storage::delete($data['csv_file']); // hapus file setelah diimport
                Notification::make()
    ->title('Berhasil')
    ->body('Data berhasil diimpor!')
    ->success()
    ->send();

                }),
        ];
    }
}
