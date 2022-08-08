<?php

namespace GetCandy\Hub\Http\Livewire\Components\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\DatePicker;
use GetCandy\Hub\Http\Livewire\Traits\Notifies;
use GetCandy\Hub\Http\Livewire\Traits\WithSavedSearches;
use GetCandy\Hub\Tables\Columns\OrderStatusColumn;
use GetCandy\Hub\Tables\Columns\PriceColumn;
use GetCandy\Hub\Tables\GetCandyTable;
use GetCandy\Models\Order;
use Illuminate\Contracts\Database\Query\Builder;
use Filament\Tables\Actions\BulkAction;
use Illuminate\Support\Collection;
use Filament\Forms\Components\Select;

class OrdersTable extends GetCandyTable
{
    use WithSavedSearches, Notifies;

    /**
     * {@inheritDoc}
     */
    public function isTableSearchable(): bool
    {
        return true;
    }

    /**
     * {@inheritDoc}
     */
    protected function getTableQuery(): Builder
    {
        return Order::query()->orderBy('created_at', 'desc');
    }

    /**
     * {@inheritDoc}
     */
    protected function applySearchToTableQuery(Builder $query): Builder
    {
        if (filled($searchQuery = $this->getTableSearchQuery())) {
            $query->whereIn('id', Order::search($searchQuery)->keys());
        }

        return $query;
    }

    /**
     * {@inheritDoc}
     */
    protected function getBaseTableColumns(): array
    {
        return [
            OrderStatusColumn::make('status'),
            TextColumn::make('reference')->url(fn (Order $record): string => route('hub.orders.show', ['order' => $record])),
            TextColumn::make('billingAddress.fullName')->label('Customer'),
            TextColumn::make('billingAddress.company_name')->label('Company Name'),
            TextColumn::make('billingAddress.contact_email')->label('Billing Email'),
            PriceColumn::make('total')->label('Total'),
            TextColumn::make('placed_at')->dateTime(),
        ];
    }

    /**
     * {@inheritDoc}
     */
    protected function getBaseTableActions(): array
    {
        return [];
    }

    /**
     * {@inheritDoc}
     */
    protected function getBaseTableBulkActions(): array
    {
        $statuses = collect(config('getcandy.orders.statuses', []))->mapWithKeys(function ($status, $key) {
            return [$key => $status['label'] ?? $status];
        });

        return [
            BulkAction::make('updateStatus')
            ->action(function (Collection $records, array $data): void {
                Order::whereIn('id', $records->pluck('id')->toArray())->update([
                    'status' => $data['status'],
                ]);
            })
            ->form([
                Select::make('status')
                    ->label('Status')
                    ->options($statuses->toArray())
                    ->required(),
            ])->deselectRecordsAfterCompletion()
        ];
    }

    /**
     * {@inheritDoc}
     */
    protected function getBaseTableFilters(): array
    {
        $statuses = config('getcandy.orders.statuses', []);

        return [
            SelectFilter::make('status')->options(
                Order::distinct()->pluck('status')->mapWithKeys(function ($status) use ($statuses) {
                    return [
                       $status => $statuses[$status]['label'] ?? $status,
                    ];
                }),
            ),
            Filter::make('created_at')
            ->form([
                DatePicker::make('created_from')
                    ->placeholder(fn ($state): string => 'Dec 18, ' . now()->subYear()->format('Y')),
                DatePicker::make('created_until')
                    ->placeholder(fn ($state): string => now()->format('M d, Y')),
            ])
            ->query(function (Builder $query, array $data): Builder {
                return $query
                    ->when(
                        $data['created_from'],
                        fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                    )
                    ->when(
                        $data['created_until'],
                        fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                    );
            })
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function rules()
    {
        return array_merge([
        ], $this->withSavedSearchesValidationRules());
    }

    /**
     * Render the livewire component.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('adminhub::livewire.components.tables.orders-table')
            ->layout('adminhub::layouts.base');
    }
}
