<?php

namespace Botble\DataSynchronize\Exporter;

use Botble\DataSynchronize\Enums\ExportColumnType;

class ExportColumn
{
    protected string $name;

    protected string $label;

    protected bool $disabled = false;

    protected string $type = ExportColumnType::TEXT;

    protected string $trueValue;

    protected string $falseValue;

    protected string $dateTimeFormat;

    protected array $options = [];

    protected ?string $validationErrorTitle = null;

    protected ?string $validationError = null;

    protected ?string $validationPromptTitle = null;

    protected ?string $validationPrompt = null;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public static function make(string $name): static
    {
        return new static($name);
    }

    public function label(string $label): static
    {
        $this->label = $label;

        return $this;
    }

    public function disabled(bool $disabled = true): static
    {
        $this->disabled = $disabled;

        return $this;
    }

    public function type(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function boolean(string $trueValue = 'Yes', string $falseValue = 'No'): static
    {
        $this->type = ExportColumnType::BOOLEAN;
        $this->trueValue = $trueValue;
        $this->falseValue = $falseValue;

        $this->options = [
            $trueValue,
            $falseValue,
        ];

        return $this;
    }

    public function dateTime(string $format = 'yyyy-mm-dd hh:mm:ss'): static
    {
        $this->type = ExportColumnType::DATETIME;
        $this->dateTimeFormat = $format;

        return $this;
    }

    public function dropdown(array $options): static
    {
        $this->type = ExportColumnType::DROPDOWN;
        $this->options = $options;

        return $this;
    }

    public function validationErrorTitle(string $title): static
    {
        $this->validationErrorTitle = $title;

        return $this;
    }

    public function validationError(string $error): static
    {
        $this->validationError = $error;

        return $this;
    }

    public function validationPromptTitle(string $title): static
    {
        $this->validationPromptTitle = $title;

        return $this;
    }

    public function validationPrompt(string $prompt): static
    {
        $this->validationPrompt = $prompt;

        return $this;
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getLabel(): string
    {
        if (isset($this->label)) {
            return $this->label;
        }

        return str($this->name)
            ->replace('_', ' ')
            ->title();
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function isDisabled(): bool
    {
        return $this->disabled;
    }

    public function getTrueValue(): string
    {
        return $this->trueValue;
    }

    public function getFalseValue(): string
    {
        return $this->falseValue;
    }

    public function getDateTimeFormat(): string
    {
        return $this->dateTimeFormat;
    }

    public function getValidationErrorTitle(): ?string
    {
        return $this->validationErrorTitle;
    }

    public function getValidationError(): ?string
    {
        return $this->validationError;
    }

    public function getValidationPromptTitle(): ?string
    {
        return $this->validationPromptTitle;
    }

    public function getValidationPrompt(): ?string
    {
        return $this->validationPrompt;
    }
}
