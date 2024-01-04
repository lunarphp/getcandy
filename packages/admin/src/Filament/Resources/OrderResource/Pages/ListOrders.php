<?php

namespace Lunar\Admin\Filament\Resources\OrderResource\Pages;

use Filament\Resources\Components\Tab;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;
use Lunar\Admin\Filament\Resources\OrderResource;
use Lunar\Admin\Support\Pages\BaseListRecords;
use Lunar\Models\Order;

class ListOrders extends BaseListRecords
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All'),
            'in-process' => Tab::make('In Process')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'in-process')),
            'on-back-order' => Tab::make('On Backorder')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'on-back-order')),
            'collection-processed' => Tab::make('Collection Processed')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'collection-processed')),
            'dispatched' => Tab::make('Dispatched')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'dispatched')),
        ];
    }

    protected function applySearchToTableQuery(Builder $query): Builder
    {
        $this->applyColumnSearchesToTableQuery($query);

        if (filled($search = $this->getTableSearch())) {
            $query->whereIn(
                'id',
                collect(Order::search($search)->keys())->map(
                    fn ($result) => str_replace(Order::class.'::', '', $result)
                )
            );
        }

        return $query;
    }

    protected function paginateTableQuery(Builder $query): Paginator
    {
        return $query->simplePaginate($this->getTableRecordsPerPage());
    }

    public function getMaxContentWidth(): ?string
    {
        return 'full';
    }
}
