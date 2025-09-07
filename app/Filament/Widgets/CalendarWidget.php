<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Task;
use Illuminate\Database\Eloquent\Model;
use Saade\FilamentFullCalendar\Widgets\FullCalendarWidget;
use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DateTimePicker; // Import DateTimePicker
use Filament\Forms;
use GuzzleHttp\Promise\Create;
use Saade\FilamentFullCalendar\Data\EventData;
use App\Filament\Resources\TaskResource;
use Filament\Forms\Components\Grid;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Illuminate\Support\Facades\Storage;
use League\Csv\Reader;
use League\Csv\Statement;
use Carbon\Carbon;



class CalendarWidget extends FullCalendarWidget
{
    public Model | string | null $model = Task::class;

    public function fetchEvents(array $fetchInfo): array
    {

        return Task::where('start', '>=', $fetchInfo['start'])
            ->where('end', '<=', $fetchInfo['end'])
            ->get()
            ->map(
                fn(Task $task) => EventData::make()
                    ->id($task->id)
                    ->title($task->uraian_kegiatan)
                    ->start($task->start)
                    ->end($task->end)
                    ->url(
                        url: TaskResource::getUrl(name: 'view', parameters: ['record' => $task]),
                        shouldOpenUrlInNewTab: true
                    )

            )
            ->toArray();
    }


    public function getFormSchema(): array
    {

        return [
            TextInput::make('uraian_kegiatan')
                ->label('Uraian Kegiatan')
                ->required(),

            Grid::make()
                ->schema([
                    DateTimePicker::make('start'),

                    DateTimePicker::make('end'),
                ]),
        ];
    }



    protected function getModelActions(): array
    {
        return [
            EditAction::make()
                ->mountUsing(
                    function (Task $record, Forms\Form $form, array $arguments) {
                        $form->fill([
                            'uraian_kegiatan' => $record->name,
                            'start' => $arguments['event']['start'] ?? $record->start,
                            'end' => $arguments['event']['end'] ?? $record->end
                        ]);
                    }
                ),
            DeleteAction::make(),

        ];
    }



    protected function viewAction(): ViewAction

    {
        return ViewAction::make();
    }


    public function config(): array
    {
        return [
            'firstDay' => 1,
            'headerToolbar' => [
                'left' => 'dayGridWeek,dayGridDay',
                'center' => 'title',
                'right' => 'prev,next today',
            ],
        ];
    }

    public function eventDidMount(): string
    {
        return <<<JS
        function({ event, timeText, isStart, isEnd, isMirror, isPast, isFuture, isToday, el, view }){
            el.setAttribute("x-tooltip", "tooltip");
            el.setAttribute("x-data", "{ tooltip: '"+event.title+"' }");
        }
    JS;
    }


    /**
     * Tambahkan tombol Import CSV di toolbar widget
     */
    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Jadwal Baru')
                ->color('primary'),
            Action::make('importCsv')
                ->label('Import Jadwal CSV')
                ->color('success')
                ->icon('heroicon-o-arrow-up-tray')
                ->button()
                ->form([
                    FileUpload::make('csv_file')
                        ->label('Upload File CSV')
                        ->disk('local')
                        ->directory('imports')
                        ->acceptedFileTypes(['text/csv', 'application/csv'])
                        ->required()
                        ->helperText('Format CSV: uraian_kegiatan,start,end. Contoh: "Rapat Guru","2025-01-15 08:00:00","2025-01-15 10:00:00"'),
                ])
                ->action(function (array $data): void {
                    try {
                        $filePath = Storage::disk('local')->path($data['csv_file']);

                        $csv = Reader::createFromPath($filePath, 'r');
                        $csv->setHeaderOffset(0);

                        $records = (new Statement())->process($csv);
                        $importedCount = 0;

                        foreach ($records as $record) {
                            // Validasi data
                            $uraian = $record['uraian_kegiatan'] ?? $record['uraian'] ?? 'Tanpa Judul';
                            $start = $record['start'] ?? $record['tanggal_mulai'] ?? now();
                            $end = $record['end'] ?? $record['tanggal_selesai'] ?? now()->addHour();

                            // Parse tanggal jika dalam format string
                            if (is_string($start)) {
                                $start = Carbon::parse($start);
                            }
                            if (is_string($end)) {
                                $end = Carbon::parse($end);
                            }

                            Task::create([
                                'uraian_kegiatan' => $uraian,
                                'start' => $start,
                                'end' => $end,
                            ]);

                            $importedCount++;
                        }

                        // Hapus file setelah import
                        Storage::disk('local')->delete($data['csv_file']);

                        // Tampilkan notifikasi sukses
                        \Filament\Notifications\Notification::make()
                            ->title('Import Berhasil!')
                            ->body("Berhasil mengimport {$importedCount} jadwal dari CSV.")
                            ->success()
                            ->send();
                    } catch (\Exception $e) {
                        \Filament\Notifications\Notification::make()
                            ->title('Import Gagal!')
                            ->body('Terjadi kesalahan saat mengimport CSV: ' . $e->getMessage())
                            ->danger()
                            ->send();
                    }
                }),
        ];
    }
}
