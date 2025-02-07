<?php

namespace Lunar\Admin\Filament\Resources\ProductVariantResource\Pages;

use Filament\Forms;
use Filament\Support\Facades\FilamentIcon;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Lunar\Admin\Filament\Resources\ProductVariantResource;
use Lunar\Admin\Support\Pages\BaseManageRelatedRecords;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class ManageVariantMedia extends BaseManageRelatedRecords
{
    protected static string $relationship = 'images';

    protected static string $resource = ProductVariantResource::class;

    public function getTitle(): string|Htmlable
    {
        return __('lunarpanel::productvariant.pages.media.title');
    }

    public static function getNavigationLabel(): string
    {
        return __('lunarpanel::productvariant.pages.media.title');
    }

    public static function getNavigationIcon(): ?string
    {
        return FilamentIcon::resolve('lunar::media');
    }

    protected function getDefaultHeaderActions(): array
    {
        return [
            ProductVariantResource::getVariantSwitcherWidget(
                $this->getRecord()
            ),
        ];
    }

    public function table(Table $table): Table
    {
        return $table
            ->heading(function () {
                return __('lunarpanel::relationmanagers.medias.title');
            })
            ->description(function () {
                return __('lunarpanel::relationmanagers.medias.variant_description');
            })
            ->recordTitleAttribute('name')
            ->modifyQueryUsing(fn (Builder $query) => $query->orderBy('position'))
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->state(function (Media $record): string {
                        return $record->hasGeneratedConversion('small') ? $record->getUrl('small') : '';
                    })
                    ->label(__('lunarpanel::relationmanagers.medias.table.image.label')),
                Tables\Columns\TextColumn::make('file_name')
                    ->limit(30)
                    ->label(__('lunarpanel::relationmanagers.medias.table.file.label')),
                Tables\Columns\TextColumn::make('custom_properties.name')
                    ->label(__('lunarpanel::relationmanagers.medias.table.name.label')),
                Tables\Columns\ToggleColumn::make('primary')
                    ->label(__('lunarpanel::relationmanagers.medias.table.primary.label'))
                    ->beforeStateUpdated(function ($record, $state) {
                        if ($state === true) {
                            $record = $this->getOwnerRecord();

                            $record->images->each(fn ($media) => $record->images()->updateExistingPivot($media->id, ['primary' => false]));
                        }
                    }),
            ])
            ->headerActions([
                CreateAction::make('attach')
                    ->label(__('lunarpanel::relationmanagers.medias.actions.attach.label'))
                    ->modalHeading(__('lunarpanel::relationmanagers.medias.actions.attach.label'))
                    ->form([
                        Forms\Components\Select::make('media_id')
                            ->label(__('lunarpanel::relationmanagers.medias.table.file.label'))
                            ->options(function () {
                                return $this->getRecord()
                                    ->product
                                    ->media
                                    ->filter(fn ($media) => ! $this->getRecord()->images->pluck('id')->contains($media->id))
                                    ->mapWithKeys(fn ($media) => [
                                        $media->getKey() => Arr::get($media->data, 'custom_properties.name', $media->name),
                                    ]);
                            })
                            ->required(),

                        Forms\Components\Toggle::make('primary')
                            ->label(__('lunarpanel::relationmanagers.medias.table.primary.label')),
                    ])
                    ->using(function (array $data): Model {
                        $record = $this->getOwnerRecord();

                        $isPrimary = $data['primary'] ?? false;
                        $position = 0;

                        if (! $isPrimary && $record->images->isEmpty()) {
                            $isPrimary = true;
                        }

                        if ($record->images->isNotEmpty()) {
                            $position = $record->images->pluck('pivot.position')->sort()->last() + 1;

                            if ($isPrimary) {
                                $record->images->each(fn ($media) => $record->images()->updateExistingPivot($media->id, ['primary' => false]));
                            }
                        }

                        $record->images()->attach([
                            $data['media_id'] => [
                                'position' => $position,
                                'primary' => $isPrimary,
                            ],
                        ]);

                        return $record;
                    }),
            ])
            ->reorderRecordsTriggerAction(
                fn (Action $action, bool $isReordering) => $action
                    ->button(),
            )
            ->reorderable('position', true);
    }
}
