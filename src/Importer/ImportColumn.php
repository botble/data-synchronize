<?php

namespace Botble\DataSynchronize\Importer;

class ImportColumn
{
    protected array $rules = [];

    protected ?string $rulesDescription = null;

    protected bool $nullable = false;

    protected string $label;

    protected bool $boolean = false;

    protected string $trueValue;

    protected string $falseValue;

    public function __construct(protected string $name, protected ?string $heading)
    {
    }

    public static function make(string $name, ?string $heading = null): static
    {
        return new static($name, $heading);
    }

    public function rules(array $rules, ?string $description = null): static
    {
        $this->rules = $rules;
        $this->rulesDescription = $description;

        return $this;
    }

    public function label(string $label): static
    {
        $this->label = $label;

        return $this;
    }

    public function boolean(string $trueValue = 'Yes', string $falseValue = 'No'): static
    {
        $this->boolean = true;

        $this->trueValue = $trueValue;
        $this->falseValue = $falseValue;

        return $this;
    }

    public function nullable(): static
    {
        $this->nullable = true;

        return $this;
    }

    public function heading(string $heading): static
    {
        $this->heading = $heading;

        return $this;
    }

    public function getHeading(): string
    {
        return $this->heading ?? $this->getName();
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getRules(): array
    {
        return $this->rules;
    }

    public function isNullable(): bool
    {
        return $this->nullable;
    }

    public function getLabel(): string
    {
        if (isset($this->label)) {
            return $this->label;
        }

        if (isset($this->heading)) {
            return $this->heading;
        }

        return str($this->name)
            ->title()
            ->replace('_', ' ');
    }

    public function getRulesDescription(): ?string
    {
        return $this->rulesDescription;
    }

    public function isBoolean(): bool
    {
        return $this->boolean;
    }

    public function getTrueValue(): string
    {
        return $this->trueValue;
    }

    public function getFalseValue(): string
    {
        return $this->falseValue;
    }
}
