<div>
    <label for="{{ $field }}"
           class="lt-block lt-text-xs lt-font-medium lt-text-gray-700 lt-capitalize">
        {{ $heading }}
    </label>

    <div
      x-data="{
        value: @entangle('filters.'.$field),
        init() {
          this.$nextTick(() => {
            flatpickr($refs.input, {
                mode: 'range',
            })
          })
        }
      }"
      @change="value = $event.target.value"
      class="lt-flex lt-relative lt-mt-1"
    >
      <x-hub::input.text
        x-ref="input"
        type="text"
        x-bind:value="value"
      />
      <div x-show="value" class="absolute right-0 mr-3">
        <button x-on:click="value = null" type="button" class="lt-inline-flex lt-items-center lt-text-sm lt-text-gray-400 lt-hover:text-gray-800">
          <x-hub::icon ref="x-circle" class="w-4 mt-2" />
        </button>
      </div>
    </div>

</div>
