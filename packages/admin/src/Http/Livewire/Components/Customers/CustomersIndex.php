<?php

namespace Lunar\Hub\Http\Livewire\Components\Customers;

use Livewire\Component;
use Livewire\WithPagination;
use Lunar\Models\Attribute;
use Lunar\Models\Customer;

class CustomersIndex extends Component
{
    use WithPagination;

    /**
     * The search term.
     *
     * @var string
     */
    public $search = '';

    /**
     * Define what to track in the query string.
     *
     * @var array
     */
    protected $queryString = ['search'];

    public function updatedSearch()
    {
        $this->setPage(1);
    }

    /**
     * Computed method to return customers.
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getCustomersProperty()
    {
        return Customer::search($this->search)->paginate(50);
    }

    /**
     * Return the available attributes for customers.
     *
     * @return Collection
     */
    public function getAttributesProperty()
    {
        return Attribute::whereAttributeType(Customer::class)->get();
    }

    /**
     * Computed method to return meta fields.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getMetaFieldsProperty()
    {
        return collect(config('lunar-hub.customers.searchable_meta'));
    }

    /**
     * Render the livewire component.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('adminhub::livewire.components.customers.index')
            ->layout('adminhub::layouts.base');
    }
}
