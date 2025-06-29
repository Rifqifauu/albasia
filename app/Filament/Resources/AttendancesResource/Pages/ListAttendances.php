<?php

namespace App\Filament\Resources\AttendancesResource\Pages;

use App\Filament\Resources\AttendancesResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Storage;
use League\Csv\Reader;
use Filament\Resources\Pages\ListRecords;
use App\Models\Attendance;

class ListAttendances extends ListRecords
{
    protected static string $resource = AttendancesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),

            Action::make('Import CSV')
                ->label('Import CSV')
                ->color('success')
                ->icon('heroicon-m-arrow-down-tray')
                ->form([
                    FileUpload::make('csv_file')
                        ->label('Upload CSV File')
                        ->required()
                        ->acceptedFileTypes(['text/csv', 'text/plain'])
                        ->disk('local') // disimpan di storage/app
                        ->directory('temp-uploads'),
                ])
                ->action(function (array $data): void {
                    try {
                        $path = storage_path('app/' . $data['csv_file']);
                        $csv = Reader::createFromPath($path, 'r');
                        $csv->setHeaderOffset(0); // baris pertama sebagai header

                        foreach ($csv->getRecords() as $record) {
                            Attendance::create([
                                'nik' => $record['nik'],
                                'tanggal'     => $record['tanggal'],
                                'masuk'       => $record['masuk'],
                                'keluar'      => $record['keluar'],
                            ]);
                        }

                        // Hapus file setelah proses import
                        Storage::delete($data['csv_file']);

                        Notification::make()
                            ->title('Berhasil')
                            ->body('Data berhasil diimpor!')
                            ->success()
                            ->send();

                    } catch (\Exception $e) {
                        Notification::make()
                            ->title('Gagal')
                            ->body('Terjadi kesalahan saat mengimpor data: ' . $e->getMessage())
                            ->danger()
                            ->send();
                    }
                }),
        ];
    }
}
