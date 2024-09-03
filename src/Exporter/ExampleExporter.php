<?php

namespace Botble\DataSynchronize\Exporter;

use Botble\DataSynchronize\Importer\ImportColumn;
use Illuminate\Support\Collection;

class ExampleExporter extends Exporter
{
    public function __construct(protected array $examples, protected array $columns, protected string $label)
    {
    }

    public function columns(): array
    {
        return array_map(fn (ImportColumn $item) => ExportColumn::make($item->getName())->label($item->getLabel()), $this->columns);
    }

    public function getExportFileName(): string
    {
        return sprintf('%s-example.%s', str($this->label)->trim()->replace(' ', '-'), $this->format);
    }

    public function collection(): Collection
    {
        return collect($this->examples)->map(fn ($item) => (object) $item);
    }
}
