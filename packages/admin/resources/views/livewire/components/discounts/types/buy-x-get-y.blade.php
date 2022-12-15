<div class="space-y-4">


    <header class="flex items-center justify-between">
        <div>
            <strong>Qualify Products</strong>
            <p class="text-sm text-gray-600">Select the products required for the discount to apply</p>
        </div>
        <div>
            @livewire('hub.components.product-search', [
                'existing' => $this->conditions,
                'ref' => 'discount-conditions',
                'showBtn' => true,
            ])
        </div>
    </header>

    <div class="space-y-1">
        @if($errors->first('selectedConditions'))
            <x-hub::alert level="danger">
                You must select at least 1 qualifying product.
            </x-hub::alert>
        @endif
        @if(!$this->purchasableConditions->count())
            <div class="text-sm text-gray-700 border p-4 rounded bg-gray-50">
                No products currently selected.
            </div>
        @endif

        @foreach($this->purchasableConditions as $product)
            <div
                wire:key="condition_product_{{ $product->id }}"
                class="rounded border px-3 py-2 flex items-center"
            >
                @if($thumbnail = $product->thumbnail)
                <div>
                    <img class="w-8 rounded" src="{{ $thumbnail->getUrl('small') }}">
                </div>
                @endif
                <div class="grow ml-4">
                    {{ $product->translateAttribute('name') }}
                </div>
                <div>
                    <button type="button" wire:click="removeCondition({{ $product->id }})">
                        <x-hub::icon ref="trash" class="w-4 h-4" />
                    </button>
                </div>
            </div>
        @endforeach
    </div>

    <div class="grid grid-cols-2">
        <x-hub::input.group for="min_qty" :error="$errors->first('discount.data.min_qty')" label="Product Quantity" instructions="Set how many of the above products are required to qualify for a reward">
            <x-hub::input.text type="number" id="min_qty" wire:model="discount.data.min_qty" />
        </x-hub::input.group>
    </div>

    <header class="flex items-center justify-between">
        <div>
            <strong>Product Rewards</strong>
            <p class="text-sm text-gray-600">Select which products will be discounted if they exist in the cart and the above conditions are met.</p>
        </div>
        <div>
            @livewire('hub.components.product-search', [
                'existing' => $this->rewards,
                'ref' => 'discount-rewards',
                'showBtn' => true,
            ])
        </div>
    </header>

    @if($errors->first('selectedRewards'))
        <x-hub::alert level="danger">
            You must select at least 1 qualifying product.
        </x-hub::alert>
    @endif

    @if(!$this->purchasableRewards->count())
        <div class="text-sm text-gray-600 border p-4 rounded bg-gray-50">
            No products currently selected
        </div>
    @endif

    <div class="space-y-1">
        @foreach($this->purchasableRewards as $product)
            <div wire:key="reward_product_{{ $product->id }}" class="rounded border px-3 py-2 flex items-center">
                @if($thumbnail = $product->thumbnail)
                <div>
                    <img class="w-8 rounded" src="{{ $thumbnail->getUrl('small') }}">
                </div>
                @endif
                <div class="grow ml-4">
                    {{ $product->translateAttribute('name') }}
                </div>
                <div>
                    <button type="button" wire:click="removeReward({{ $product->id }})">
                        <x-hub::icon ref="trash" class="w-4 h-4" />
                    </button>
                </div>
            </div>
        @endforeach
    </div>

    <x-hub::alert>
        If one or more items are in the cart, the cheapest item will be discounted.
    </x-hub::alert>

    <div class="grid grid-cols-2 gap-4">
        <x-hub::input.group for="reward_qty" :error="$errors->first('discount.data.reward_qty')" label="No. of free items" instructions="How many of each item are discounted">
            <x-hub::input.text type="number" wire:model="discount.data.reward_qty" />
        </x-hub::input.group>

        <x-hub::input.group for="max_reward_qty" label="Maximum reward quantity" :error="$errors->first('discount.data.max_reward_qty')" instructions="The maximum amount of products which can be discounted, regardless of criteria.">
            <x-hub::input.text type="number" wire:model="discount.data.max_reward_qty" />
        </x-hub::input.group>
    </div>
</div>
