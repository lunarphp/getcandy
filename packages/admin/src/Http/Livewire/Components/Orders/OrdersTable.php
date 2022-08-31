<?php

namespace GetCandy\Hub\Http\Livewire\Components\Orders;

use GetCandy\Hub\Tables\Builders\OrdersTableBuilder;
use GetCandy\LivewireTables\Components\Table;
use GetCandy\LivewireTables\Components\Columns\TextColumn;
use GetCandy\LivewireTables\Components\Filters\SelectFilter;
use GetCandy\LivewireTables\Components\Actions\Action;
use GetCandy\LivewireTables\Components\Actions\BulkAction;
use GetCandy\Models\Order;
use Illuminate\Support\Collection;

class OrdersTable extends Table
{
    /**
     * {@inheritDoc}
     */
    protected $tableBuilderBinding = OrdersTableBuilder::class;

    public $searchable = true;

    public bool $canSaveSearches = true;

    public function build()
    {
        $this->tableBuilder->addColumn(
            TextColumn::make('reference')->value(function ($record) {
                return $record->reference;
            }),
        );

        $this->tableBuilder->addColumn(
            TextColumn::make('status')->sortable(true)->viewComponent('hub::orders.status')
        );

        $this->tableBuilder->addFilter(
            SelectFilter::make('status')->options(function () {
                $statuses = collect(
                    config('getcandy.orders.statuses'),
                    []
                )->mapWithKeys(fn($status, $key) => [$key => $status['label']]);
                return collect([
                    null => 'All Statuses',
                ])->merge($statuses);
            })
        );

        $this->tableBuilder->addAction(
            Action::make('view')->label('View product')->url(function ($record) {
                return route('hub.products.show', $record->id);
            })
        );

        $this->tableBuilder->addBulkAction(
            BulkAction::make('export')->label('Export orders')
        );
    }

    /**
     * Return the saved searches available to the table.
     *
     * @return Collection
     */
    public function getSavedSearchesProperty(): Collection
    {
        return auth()->getUser()->savedSearches()->whereComponent(
            $this->getName()
        )->get()->map(function ($savedSearch) {
            return [
                'key' => $savedSearch->id,
                'label' => $savedSearch->name,
            ];
        });
    }

    public function getData()
    {
        return Order::paginate(50);
    }
}
