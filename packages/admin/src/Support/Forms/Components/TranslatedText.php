<?php

namespace Lunar\Admin\Support\Forms\Components;

use Closure;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;
use Lunar\Models\Language;
use Spatie\LaravelBlink\BlinkFacade as Blink;

class TranslatedText extends TextInput
{
    protected string $view = 'lunarpanel::forms.components.translated-text';

    protected bool $requireDefault = false;

    public function getLanguages(): Collection
    {
        $key = 'lunarpanel_'.Str::snake(self::class);

        return Blink::once($key, function () {
            return Language::orderBy('default', 'desc')->get();
        });
    }

    public function getLanguageDefaults(): array
    {
        return $this->getLanguages()->mapWithKeys(fn ($language) => [$language->code => null])->toArray();
    }

    public function getDefaultLanguage(): Language
    {
        return Language::getDefault();
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->default(static function (TranslatedText $component): array {
            return $component->getLanguageDefaults();
        });

        $this->afterStateHydrated(static function ($state, TranslatedText $component) {
            $defaults = $component->getLanguageDefaults();

            foreach ($defaults as $language => $_) {
                $defaults[$language] = $state[$language] ?? null;
            }

            $component->state($defaults);
        });

        $this->mutateDehydratedStateUsing(static function (TranslatedText $component, ?array $state) {
            return (object) $state;
        });

        $this->rules([
            function (TranslatedText $component) {
                return function (string $attribute, $value, Closure $fail) use ($component) {
                    $defaultLanguage = $component->getDefaultLanguage();

                    if (blank($value[$defaultLanguage->code] ?? null)) {
                        $fail("The {$defaultLanguage->name} :attribute is required.");
                    }
                };
            },
        ], fn (TranslatedText $component) => $component->isRequireDefault());
    }

    public function requireDefault($requireDefault = true): static
    {
        $this->requireDefault = $requireDefault;

        return $this;
    }

    public function isRequireDefault(): bool
    {
        return $this->requireDefault;
    }
}
