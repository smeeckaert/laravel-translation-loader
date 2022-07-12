<?php

namespace Novius\TranslationLoader\TranslationLoaders;

use Novius\TranslationLoader\LanguageLine;
use Novius\TranslationLoader\Exceptions\InvalidConfiguration;
use Throwable;

class Db implements TranslationLoader
{
    public function loadTranslations(string $locale, string $group, string $namespace = '*'): array
    {
        $model = $this->getConfiguredModelClass();

        try {
            return $model::getTranslationsForGroup($locale, $group, $namespace);
        }
        catch (Throwable $e) {
            // If console ignore
            if (app()->runningInConsole()) {
                return [];
            }
            throw $e;
        }
    }

    protected function getConfiguredModelClass(): string
    {
        $modelClass = config('translation-loader.model');

        if (!is_a(new $modelClass, LanguageLine::class)) {
            throw InvalidConfiguration::invalidModel($modelClass);
        }

        return $modelClass;
    }
}
