<?php

namespace Lunar\Admin\Support\Pages;

use Filament\Resources\Pages\ViewRecord;

abstract class BaseViewRecord extends ViewRecord
{
    use Concerns\ExtendsHeaderActions;
    use Concerns\ExtendsHeadings;
    use Concerns\ExtendsHeaderWidgets;
    use \Lunar\Admin\Support\Concerns\CallsHooks;
}
