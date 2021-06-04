<?php

namespace PowerComponents\LivewirePowerGrid;

use Livewire\Component;
use Livewire\WithPagination;
use PowerComponents\LivewirePowerGrid\Helpers\Collection;
use PowerComponents\LivewirePowerGrid\Services\Spout\ExportToCsv;
use PowerComponents\LivewirePowerGrid\Services\Spout\ExportToXLS;
use PowerComponents\LivewirePowerGrid\Traits\Checkbox;
use PowerComponents\LivewirePowerGrid\Traits\Filter;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use App\Traits\Notify;

class PowerGridComponent extends Component
{
    use WithPagination,
        Notify,
        Checkbox,
        Filter;

    /**
     * @var bool
     */
    public $load = true;

    /**
     * @var array
     */
    public array $headers = [];
    /**
     * @var bool
     */
    public bool $search_input = false;
    /**
     * @var bool
     */
    public bool $show_export = true;
    /**
     * @var string
     */
    public string $search = '';
    /**
     * @var bool
     */
    public bool $perPage_input = false;
    /**
     * @var string
     */
    public string $orderBy = 'id';
    /**
     * @var bool
     */
    public bool $orderAsc = false;
    /**
     * @var
     */
    public $perPage = 10;
    /**
     * @var array
     */
    public array $columns = [];
    /**
     * @var string
     */
    protected string $paginationTheme = 'bootstrap';
    /**
     * @var array
     */
    public array $perPageValues = [10, 25, 50, 100, 0];
    /**
     * @var string
     */
    public string $sortIcon = '&#8597;';
    /**
     * @var string
     */
    public string $sortAscIcon = '&#8593;';
    /**
     * @var string
     */
    public string $sortDescIcon = '&#8595;';
    /**
     * @var string
     */
    public string $record_count = '';

    /**
     * @var string
     */
    public string $fileName = 'download';

    public array $filtered = [];
    /**
     * @var string[]
     */
    protected $listeners = [
        'powergrid:refresh' => 'refresh',
        'powergrid:eventChangeDatePiker' => 'eventChangeDatePiker',
        'powergrid:eventChangeInput' => 'eventChangeInput',
        'powergrid:eventMultiSelect' => 'eventMultiSelect'
    ];

    private $collection;

    /**
     * Apply checkbox, perPage and search view and theme
     */
    public function setUp()
    {
        $this->showPerPage();
    }

    /**
     * @return $this
     * Show search input into component
     */
    public function showSearchInput(): PowerGridComponent
    {
        $this->search_input = true;
        return $this;
    }

    /**
     * default full. other: short, min
     * @param string $mode
     * @return $this
     */
    public function showRecordCount($mode = 'full'): PowerGridComponent
    {
        $this->record_count = $mode;
        return $this;
    }

    /**
     * @param int $perPage
     * @return $this
     */
    public function showPerPage(int $perPage = 10): PowerGridComponent
    {
        if (\Str::contains($perPage, $this->perPageValues)) {
            $this->perPage_input = true;
            $this->perPage = $perPage;
        }
        return $this;
    }

    public function mount()
    {
        $this->columns = $this->columns();

        $this->paginationTheme = config('livewire-powergrid.theme');

        $this->renderFilter();

        $this->setUp();
    }

    /**
     * @return array
     */
    public function columns(): array
    {
        return [
            Column::add()
                ->title('ID')
                ->field('id')
                ->searchable()
                ->sortable(),

            Column::add()
                ->title('Created at')
                ->field('created_at'),
        ];
    }

    /**
     * @return  \Illuminate\Support\Collection
     */
    protected function data(): \Illuminate\Support\Collection
    {
        return collect([]);
    }

    public function refresh()
    {
        $this->collection(false);
    }

    /**
     * @param array $data
     * @throws \Exception
     */
    public function eventChangeInput(array $data): void
    {
        $update = $this->update($data);
        $collection = $this->collection();

        if ($update) {
            $cached = $collection->map(function ($row) use ($data) {
                $field = $data['field'];
                if ($row->id === $data['id']) {
                    $row->{$field} = $data['value'];
                }
                return $row;
            });

            $this->collection($cached);

            $this->notify('success', $this->updateMessages('success', $data['field']));
            //session()->flash('success', $this->updateMessages('success', $data['field']));
        }


        $this->notify('error', $this->updateMessages('success', $data['field']));
        //session()->flash('error', $this->updateMessages('error', $data['field']));
    }

    /**
     * @return \Illuminate\Support\Collection|mixed
     * @throws \Exception
     */
    public function collection($cached = [])
    {
        if (!empty($cached)) {
            \cache()->forget($this->id);
            return \cache()->rememberForever($this->id, function () use ($cached) {
                return $cached;
            });
        }

        if (!$cached) {
            \cache()->forget($this->id);
        }

        $cache = config('livewire-powergrid.cached_data');

        if ($cache) {
            return \cache()->rememberForever($this->id, function () {
                return $this->data();
            });
        }

        return $this->data();
    }

    public function load()
    {
        $this->load = true;
    }

    public function render()
    {
        if ($this->load) {
            $this->columns = $this->columns();
            $this->collection = $this->collection();
        }

        $this->tempSelected = [];
        $data = [];

        if (filled($this->collection)) {

            $data = Collection::search($this->collection, $this->search, $this->columns());
            $data = $this->advancedFilter($data);
            $data = $data->sortBy($this->orderBy, SORT_REGULAR, $this->orderAsc);

            if ($data->count()) {
                $this->filtered = $data->pluck('id')->toArray();
                $data = Collection::paginate($data, ($this->perPage == '0') ? $data->count() : $this->perPage);
            }
        }

        if (session()->get('actions.messages')) {
            foreach (session()->get('actions.messages') as $message) {
                $this->notify($message['type'], $message['message']);
            }

            session()->forget('actions.messages');
        }

        return $this->renderView($data);

    }

    private function renderView($data)
    {
        $theme = config('livewire-powergrid.theme');
        $version = config('livewire-powergrid.theme_versions')[$theme];

        return view('livewire-powergrid::' . $theme . '.' . $version . '.table', [
            'data' => $data
        ]);
    }

    /**
     * @param $field
     */
    public function setOrder($field)
    {
        if ($this->orderBy === $field) {
            $this->orderAsc = !$this->orderAsc;
        }
        $this->orderBy = $field;
    }

    /**
     * @param array $data
     * @return bool
     */
    public function update(array $data): bool
    {
        return false;
    }

    /**
     * @param string $status
     * @param string $field
     * @return string
     */
    public function updateMessages(string $status, string $field = '_default_message'): string
    {
        $updateMessages = [
            'success' => [
                '_default_message' => __('Data has been updated successfully!'),
                //'status' => __('Status updated successfully!'),
            ],
            "error" => [
                '_default_message' => __('Error updating the data.'),
                //'custom_field' => __('Error updating custom field.'),
            ]
        ];

        return ($updateMessages[$status][$field] ?? $updateMessages[$status]['_default_message']);
    }

    public function checkedValues(): array
    {
        return $this->checkbox_values;
    }

    /**
     * set name to exported file (xlsx/csv)
     * @param string $fileName
     * @return $this
     */
    public function exportedFileName(string $fileName): PowerGridComponent
    {
        $this->fileName = $fileName;
        return $this;
    }

    /**
     * @throws \Exception
     */
    public function exportToExcel(): BinaryFileResponse
    {
        return (new ExportToXLS())
            ->fileName($this->fileName)
            ->fromCollection($this->columns(), $this->collection())
            ->withCheckedRows(array_merge($this->checkbox_values, $this->filtered))
            ->download();

    }

    /**
     * @throws \Exception
     */
    public function exportToCsv(): BinaryFileResponse
    {
        return (new ExportToCsv())
            ->fileName($this->fileName)
            ->fromCollection($this->columns(), $this->collection())
            ->withCheckedRows(array_merge($this->checkbox_values, $this->filtered))
            ->download();
    }
}
