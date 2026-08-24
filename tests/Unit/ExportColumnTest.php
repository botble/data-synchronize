<?php

namespace Botble\DataSynchronize\Tests\Unit;

use Botble\DataSynchronize\Enums\ExportColumnType;
use Botble\DataSynchronize\Exporter\ExportColumn;
use PHPUnit\Framework\TestCase;

class ExportColumnTest extends TestCase
{
    public function test_make_returns_a_column_with_the_given_name(): void
    {
        $this->assertSame('first_name', ExportColumn::make('first_name')->getName());
    }

    public function test_label_defaults_to_a_title_cased_name(): void
    {
        $this->assertSame('First Name', ExportColumn::make('first_name')->getLabel());
        $this->assertSame('Id', ExportColumn::make('id')->getLabel());
    }

    public function test_explicit_label_wins_over_the_derived_one(): void
    {
        $this->assertSame('ID', ExportColumn::make('id')->label('ID')->getLabel());
    }

    public function test_type_defaults_to_text(): void
    {
        $this->assertSame(ExportColumnType::TEXT, ExportColumn::make('name')->getType());
    }

    public function test_type_can_be_set_explicitly(): void
    {
        $this->assertSame(
            ExportColumnType::NUMBER,
            ExportColumn::make('price')->type(ExportColumnType::NUMBER)->getType()
        );
    }

    public function test_boolean_sets_type_true_false_values_and_options(): void
    {
        $column = ExportColumn::make('is_featured')->boolean();

        $this->assertSame(ExportColumnType::BOOLEAN, $column->getType());
        $this->assertSame('Yes', $column->getTrueValue());
        $this->assertSame('No', $column->getFalseValue());
        $this->assertSame(['Yes', 'No'], $column->getOptions());
    }

    public function test_boolean_accepts_custom_labels(): void
    {
        $column = ExportColumn::make('active')->boolean('On', 'Off');

        $this->assertSame('On', $column->getTrueValue());
        $this->assertSame('Off', $column->getFalseValue());
        $this->assertSame(['On', 'Off'], $column->getOptions());
    }

    public function test_date_time_sets_type_and_default_format(): void
    {
        $column = ExportColumn::make('created_at')->dateTime();

        $this->assertSame(ExportColumnType::DATETIME, $column->getType());
        $this->assertSame('yyyy-mm-dd hh:mm:ss', $column->getDateTimeFormat());
    }

    public function test_date_time_accepts_a_custom_format(): void
    {
        $this->assertSame(
            'dd/mm/yyyy',
            ExportColumn::make('created_at')->dateTime('dd/mm/yyyy')->getDateTimeFormat()
        );
    }

    public function test_dropdown_sets_type_and_options(): void
    {
        $column = ExportColumn::make('status')->dropdown(['published', 'draft']);

        $this->assertSame(ExportColumnType::DROPDOWN, $column->getType());
        $this->assertSame(['published', 'draft'], $column->getOptions());
    }

    public function test_options_are_empty_by_default(): void
    {
        $this->assertSame([], ExportColumn::make('name')->getOptions());
    }

    public function test_disabled_defaults_to_false_and_is_toggleable(): void
    {
        $this->assertFalse(ExportColumn::make('name')->isDisabled());
        $this->assertTrue(ExportColumn::make('name')->disabled()->isDisabled());
        $this->assertFalse(ExportColumn::make('name')->disabled(false)->isDisabled());
    }

    public function test_validation_messages_are_null_until_set(): void
    {
        $column = ExportColumn::make('status');

        $this->assertNull($column->getValidationErrorTitle());
        $this->assertNull($column->getValidationError());
        $this->assertNull($column->getValidationPromptTitle());
        $this->assertNull($column->getValidationPrompt());
    }

    public function test_validation_messages_are_returned_once_set(): void
    {
        $column = ExportColumn::make('status')
            ->validationErrorTitle('Invalid')
            ->validationError('Pick a listed value')
            ->validationPromptTitle('Status')
            ->validationPrompt('Choose one');

        $this->assertSame('Invalid', $column->getValidationErrorTitle());
        $this->assertSame('Pick a listed value', $column->getValidationError());
        $this->assertSame('Status', $column->getValidationPromptTitle());
        $this->assertSame('Choose one', $column->getValidationPrompt());
    }

    public function test_builder_methods_are_chainable_and_return_the_same_instance(): void
    {
        $column = ExportColumn::make('status');

        $this->assertSame($column, $column->label('Status'));
        $this->assertSame($column, $column->disabled());
        $this->assertSame($column, $column->type(ExportColumnType::TEXT));
        $this->assertSame($column, $column->dropdown([]));
        $this->assertSame($column, $column->boolean());
        $this->assertSame($column, $column->dateTime());
        $this->assertSame($column, $column->validationError('x'));
    }
}
