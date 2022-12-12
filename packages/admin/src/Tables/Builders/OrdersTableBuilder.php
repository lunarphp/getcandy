<?php

namespace Lunar\Hub\Tables\Builders;

use Illuminate\Support\Collection;
use Lunar\Hub\Tables\TableBuilder;
use Lunar\LivewireTables\Components\Columns\BadgeColumn;
use Lunar\LivewireTables\Components\Columns\TextColumn;
use Lunar\Models\Order;

class OrdersTableBuilder extends TableBuilder
{
    /**
     * The field to sort using.
     *
     * @var string|null
     */
    public ?string $sortField = 'placed_at';

    /**
     * {@inheritDoc}
     */
    public function getColumns(): Collection
    {
        $baseColumns = collect([
            BadgeColumn::make('new_customer', function ($record) {
                return __(
                    'adminhub::components.orders.index.'.($record->new_customer ? 'new_customer' : 'returning_customer')
                );
            })->heading(false)->states(function ($record) {
                return [
                    'success' => $record->new_customer,
                    'info' => ! $record->new_customer,
                ];
            }),
            TextColumn::make('status')->sortable(true)->viewComponent('hub::orders.status'),
            TextColumn::make('reference')->value(function ($record) {
                return $record->reference;
            })->url(function ($record) {
                return route('hub.orders.show', $record->id);
            }),
            TextColumn::make('customer_reference')->heading('Customer Reference')->value(function ($record) {
                return $record->customer_reference;
            }),
            TextColumn::make('customer')->value(function ($record) {
                return $record->billingAddress?->fullName;
            }),
            TextColumn::make('postcode')->value(function ($record) {
                return $record->billingAddress?->postcode;
            }),
            TextColumn::make('email')->value(function ($record) {
                return $record->billingAddress?->contact_email;
            }),
            TextColumn::make('phone')->value(function ($record) {
                return $record->billingAddress?->contact_phone;
            }),
            TextColumn::make('total')->value(function ($record) {
                return $record->total->formatted;
            }),
            TextColumn::make('date')->value(function ($record) {
                return $record->placed_at?->format('Y/m/d @ H:ia');
            }),
        ]);

        return $this->resolveColumnPositions(
            $baseColumns,
            $this->columns
        );
    }

    /**
     * Return the query data.
     *
     * @param  string|null  $searchTerm
     * @param  array  $filters
     * @param  string  $sortField
     * @param  string  $sortDir
     * @return LengthAwarePaginator
     */
    public function getData(): iterable
    {
        $query = Order::with([
            'shippingLines',
            'billingAddress',
            'currency',
            'customer',
        ])->orderBy($this->sortField, $this->sortDir);

        if ($this->searchTerm) {
            $query->whereIn('id', Order::search($this->searchTerm)->keys());
        }

        $filters = collect($this->queryStringFilters)->filter(function ($value) {
            return (bool) $value;
        });

        foreach ($this->queryExtenders as $qe) {
            call_user_func($qe, $query, $this->searchTerm, $filters);
        }

        // Get the table filters we want to apply.
        $tableFilters = $this->getFilters()->filter(function ($filter) use ($filters) {
            return $filters->has($filter->field);
        });

        foreach ($tableFilters as $filter) {
            if ($closure = $filter->getQuery()) {
                call_user_func($filter->getQuery(), $filters, $query);
            }
        }

        return $query->paginate($this->perPage);
    }
}
