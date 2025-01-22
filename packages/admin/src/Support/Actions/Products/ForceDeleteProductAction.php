<?php

namespace Lunar\Admin\Support\Actions\Products;

use Filament\Actions\ForceDeleteAction;
use Illuminate\Database\Eloquent\Model;
use Lunar\Facades\DB;

class ForceDeleteProductAction extends ForceDeleteAction
{
    public function setUp(): void
    {
        parent::setUp();

        $this->action(function (): void {
            $result = $this->process(static function (Model $record) {

                return DB::transaction(function () use ($record) {
                    $record->collections()->detach();

                    $record->customerGroups()->detach();

                    $record->urls()->delete();

                    $record->variants()->get()->each->forceDelete();

                    $record->productOptions()->detach();

                    $record->associations()->delete();

                    $record->channels()->detach();

                    return $record->forceDelete();
                });
            });

            if (! $result) {
                $this->failure();

                return;
            }

            $this->success();
        });
    }
}
