<?php

namespace Botble\DataSynchronize\Concerns\Importer;

use Illuminate\Support\Collection;

trait HasImportResults
{
    protected int $currentRow = 0;

    protected array $successes = [];

    protected array $failures = [];

    public function onSuccess(array $values): void
    {
        $this->successes[] = $values;
    }

    public function onFailure(int $row, string $attribute, array $errors, array $values = []): void
    {
        $this->failures[] = [
            'row' => $row,
            'attribute' => $attribute,
            'errors' => $errors,
            'values' => $values,
        ];
    }

    public function successes(): Collection
    {
        return new Collection($this->successes);
    }

    public function failures(): Collection
    {
        return new Collection($this->failures);
    }
}
