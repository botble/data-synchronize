<?php

namespace Botble\DataSynchronize\Exporter;

class ExportCounter
{
    protected string|int $value;

    protected string $label;

    public static function make(): static
    {
        return new static();
    }

    public function value(string|int $value): static
    {
        $this->value = $value;

        return $this;
    }

    public function label(string $label): static
    {
        $this->label = $label;

        return $this;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function getLabel(): string
    {
        return $this->label;
    }
}
