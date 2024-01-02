<?php

namespace Lunar\Admin\Support\Actions\Orders;

use Filament\Actions\Action;
use Lunar\Admin\Support\Actions\Traits\UpdatesOrderStatus;
use Lunar\Models\Order;

class UpdateStatusAction extends Action
{
    use UpdatesOrderStatus;

    protected function setUp(): void
    {
        parent::setUp();

        $this->label(
            __('lunarpanel::actions.orders.update_status.label')
        );

        $this->modalWidth('lg');
        $this->slideOver();

        $this->form(fn () => $this->getUpdatesOrderStatusFormInputs());

        $this->action(
            fn (Order $record, array $data) => $this->updateStatus($record, $data)
        );
    }
}
