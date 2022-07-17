<?php

namespace GetCandy\Hub\Http\Livewire\Components\Settings\Attributes;

use GetCandy\Facades\FieldTypeManifest;
use GetCandy\Hub\Http\Livewire\Traits\Notifies;
use GetCandy\Hub\Http\Livewire\Traits\WithLanguages;
use GetCandy\Models\AttributeGroup;
use GetCandy\Models\Collection as CollectionModel;
use GetCandy\Models\ProductFeature;
use GetCandy\Models\ProductOption;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Livewire\Component;

class AttributeGroupEdit extends Component
{
    use WithLanguages;
    use Notifies;

    /**
     * The type of attributable this is.
     *
     * @var string
     */
    public $attributableType;

    /**
     * The handle for the attributable type.
     *
     * @var string
     */
    public $typeHandle;

    /**
     * The new attribute group.
     *
     * @var AttributeGroup
     */
    public ?AttributeGroup $attributeGroup = null;

    /**
     * {@inheritDoc}
     */
    public function rules()
    {
        return [
            "attributeGroup.type" => "required",
            "attributeGroup.source" => "sometimes|required",
            "attributeGroup.name.{$this->defaultLanguage->code}" => [
                'required',
                'string',
                'max:255',
            ],
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function mount()
    {
        $this->attributeGroup = $this->attributeGroup ?: new AttributeGroup();
        $this->attributeGroup->type = $this->getGroupTypesProperty()->keys()->first();
    }

    /**
     * Return the available group types.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getGroupTypesProperty(): Collection
    {
        return collect([
            'default' => 'Default',
            'model' => 'Model',
        ]);
    }

    /**
     * Return the models collection.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getModelsCollectionProperty(): Collection
    {
        return collect([
            'brands' => CollectionModel::class,
            'features' => ProductFeature::class,
            'options' => ProductOption::class,
        ]);
    }

    public function create()
    {
        $this->validate();

        $handle = Str::handle("{$this->typeHandle}_{$this->attributeGroup->translate('name')}");
        $this->attributeGroup->handle = $handle;

        $this->validate([
            'attributeGroup.handle' => 'unique:'.get_class($this->attributeGroup).',handle',
        ]);

        if ($this->attributeGroup->id) {
            $this->attributeGroup->save();
            $this->emit('attribute-group-edit.updated', $this->attributeGroup->id);
            $this->notify(
                __('adminhub::notifications.attribute-groups.updated')
            );

            return;
        }

        $this->attributeGroup->attributable_type = $this->attributableType;
        $this->attributeGroup->position = AttributeGroup::whereAttributableType(
            $this->attributableType
        )->count() + 1;

        $this->attributeGroup->handle = $handle;
        $this->attributeGroup->save();

        $this->emit('attribute-group-edit.created', $this->attributeGroup->id);

        $this->attributeGroup = new AttributeGroup();

        $this->notify(
            __('adminhub::notifications.attribute-groups.created')
        );
    }

    /**
     * Render the livewire component.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('adminhub::livewire.components.settings.attributes.attribute-group-edit')
            ->layout('adminhub::layouts.base');
    }
}
