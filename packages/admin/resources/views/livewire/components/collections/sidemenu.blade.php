<div>
    <x-hub::modal.dialog form="createCollectionGroup"
                         wire:model="showCreateModal">
        <x-slot name="title">
            {{ __('adminhub::catalogue.collections.sidemenu.modal.title') }}
        </x-slot>

        <x-slot name="content">
            <x-hub::input.group :label="__('adminhub::inputs.name')"
                                for="name"
                                required
                                :error="$errors->first('name')">

                <x-hub::input.text wire:model="name"
                                   :error="$errors->first('name')" />
            </x-hub::input.group>
        </x-slot>

        <x-slot name="footer">
            <x-hub::button type="button"
                           wire:click.prevent="$set('showCreateModal', false)"
                           theme="gray">
                {{ __('adminhub::global.cancel') }}
            </x-hub::button>

            <x-hub::button type="submit">
                {{ __('adminhub::catalogue.collections.sidemenu.modal.btn') }}
            </x-hub::button>
        </x-slot>
    </x-hub::modal.dialog>

    <div>
        <header class="flex items-center justify-between">
            <h3 class="font-bold text-gray-900 dark:text-white">
                {{ __('adminhub::catalogue.collections.sidemenu.menu_title') }}
            </h3>

            <button type="button"
                    x-on:click="showGroupSlideover = false"
                    class="block lg:hidden">
                <svg class="w-5 h-5"
                     xmlns="http://www.w3.org/2000/svg"
                     fill="none"
                     viewBox="0 0 24 24"
                     stroke-width="2"
                     stroke="currentColor"
                     aria-hidden="true">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </header>

        <nav class="mt-4 space-y-2">
            @forelse($this->collectionGroups as $group)
                <a href="{{ route('hub.collection-groups.show', $group->id) }}"
                   @class([
                       'block p-2 rounded text-gray-500 dark:text-gray-400',
                       'bg-blue-50 text-blue-700 hover:text-blue-600 dark:bg-gray-800 dark:text-gray-400 dark:hover:text-white' =>
                           $currentGroup->id == $group->id,
                       'hover:bg-blue-50 hover:text-blue-700 dark:hover:bg-gray-800 dark:hover:text-white' =>
                           $currentGroup->id == $group->id,
                   ])
                   aria-current="page">
                    <span class="text-sm font-medium">
                        {{ $group->name }}
                    </span>
                </a>
            @empty
            @endforelse
        </nav>

        <div class="mt-8">
            <x-hub::button theme="gray"
                           wire:click.prevent="$set('showCreateModal', true)">
                {{ __('adminhub::catalogue.collections.sidemenu.create_btn') }}
            </x-hub::button>
        </div>
    </div>
</div>
