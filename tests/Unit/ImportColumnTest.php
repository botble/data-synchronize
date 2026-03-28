<?php

namespace Botble\DataSynchronize\Tests\Unit;

use Botble\DataSynchronize\Importer\ImportColumn;
use PHPUnit\Framework\TestCase;

class ImportColumnTest extends TestCase
{
    public function test_make_creates_instance_with_name(): void
    {
        $column = ImportColumn::make('email');

        $this->assertSame('email', $column->getName());
        $this->assertSame('email', $column->getHeading());
    }

    public function test_make_with_custom_heading(): void
    {
        $column = ImportColumn::make('email', 'Email Address');

        $this->assertSame('email', $column->getName());
        $this->assertSame('Email Address', $column->getHeading());
    }

    public function test_heading_method_overrides_constructor_heading(): void
    {
        $column = ImportColumn::make('email', 'Old Heading')
            ->heading('New Heading');

        $this->assertSame('New Heading', $column->getHeading());
    }

    public function test_rules_are_set_and_retrieved(): void
    {
        $column = ImportColumn::make('email')
            ->rules(['required', 'email'], 'Must be a valid email');

        $this->assertSame(['required', 'email'], $column->getRules());
        $this->assertSame('Must be a valid email', $column->getRulesDescription());
    }

    public function test_default_rules_are_empty(): void
    {
        $column = ImportColumn::make('name');

        $this->assertSame([], $column->getRules());
        $this->assertNull($column->getRulesDescription());
    }

    public function test_label_from_explicit_set(): void
    {
        $column = ImportColumn::make('first_name')
            ->label('First Name');

        $this->assertSame('First Name', $column->getLabel());
    }

    public function test_label_falls_back_to_heading(): void
    {
        $column = ImportColumn::make('email', 'Email Address');

        $this->assertSame('Email Address', $column->getLabel());
    }

    public function test_label_falls_back_to_name_titlecase(): void
    {
        $column = ImportColumn::make('first_name');

        $this->assertSame('First Name', $column->getLabel());
    }

    public function test_nullable_defaults_to_false(): void
    {
        $column = ImportColumn::make('name');

        $this->assertFalse($column->isNullable());
    }

    public function test_nullable_can_be_set(): void
    {
        $column = ImportColumn::make('name')->nullable();

        $this->assertTrue($column->isNullable());
    }

    public function test_boolean_defaults_to_false(): void
    {
        $column = ImportColumn::make('active');

        $this->assertFalse($column->isBoolean());
    }

    public function test_boolean_with_default_values(): void
    {
        $column = ImportColumn::make('active')->boolean();

        $this->assertTrue($column->isBoolean());
        $this->assertSame('Yes', $column->getTrueValue());
        $this->assertSame('No', $column->getFalseValue());
    }

    public function test_boolean_with_custom_values(): void
    {
        $column = ImportColumn::make('active')->boolean('1', '0');

        $this->assertTrue($column->isBoolean());
        $this->assertSame('1', $column->getTrueValue());
        $this->assertSame('0', $column->getFalseValue());
    }

    public function test_fluent_api_chaining(): void
    {
        $column = ImportColumn::make('email')
            ->label('Email')
            ->heading('email_address')
            ->rules(['required', 'email'])
            ->nullable();

        $this->assertSame('email', $column->getName());
        $this->assertSame('Email', $column->getLabel());
        $this->assertSame('email_address', $column->getHeading());
        $this->assertSame(['required', 'email'], $column->getRules());
        $this->assertTrue($column->isNullable());
    }
}
