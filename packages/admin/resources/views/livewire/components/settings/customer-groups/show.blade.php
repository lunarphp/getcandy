<div class="space-y-4">
    <header>
        <h1 class="text-xl font-bold text-gray-900 md:text-2xl dark:text-white">
            {{ __('adminhub::settings.customer-groups.show.title') }}
        </h1>
    </header>

    <form action="#"
          method="POST"
          wire:submit.prevent="update">
        @include('adminhub::partials.forms.customer-group')
    </form>
</div>
