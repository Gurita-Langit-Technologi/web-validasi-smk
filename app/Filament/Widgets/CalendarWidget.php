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
}
