@extends(BaseHelper::getAdminMasterLayoutTemplate())

@section('content')
    <x-core::form
        method="POST"
        :url="$exporter->getUrl()"
        data-bb-toggle="export-data"
        class="data-synchronize-export-form"
        :data-success-message="trans('packages/data-synchronize::data-synchronize.export.success_message')"
        :data-error-message="trans('packages/data-synchronize::data-synchronize.export.error_message')"
    >
        <x-core::card>
            @if ($exporter->getHeading())
                <x-core::card.header>
                    <x-core::card.title>
                        {{ $exporter->getHeading() }}
                    </x-core::card.title>
                </x-core::card.header>
            @endif

            <x-core::card.body>
                <div>
                    @if($exporter->allColumnsIsDisabled())
                        <x-core::form.label>
                            {!! BaseHelper::clean(trans(
                                'packages/data-synchronize::data-synchronize.export.form.all_columns_disabled',
                                ['columns' => Arr::join(array_map(fn ($column) => "<strong>{$column->getLabel()}</strong>", $exporter->getColumns()), ', ')]
                            )) !!}
                        </x-core::form.label>
                    @else
                        <x-core::form.label>
                            {{ trans('packages/data-synchronize::data-synchronize.export.form.columns') }}
                        </x-core::form.label>

                        @foreach ($exporter->getColumns() as $column)
                            <x-core::form-group>
                                <x-core::form.checkbox
                                    name="columns[]"
                                    :value="$column->getName()"
                                    :label="$column->getLabel()"
                                    :disabled="$column->isDisabled()"
                                    checked
                                />
                            </x-core::form-group>
                        @endforeach
                    @endif
                </div>

                <x-core::form.radio-list
                    :label="trans('packages/data-synchronize::data-synchronize.export.form.format')"
                    name="format"
                    :options="[
                        'csv' => 'CSV',
                        'xlsx' => 'Excel',
                    ]"
                    value="csv"
                >
                </x-core::form.radio-list>
            </x-core::card.body>
            <x-core::card.footer>
                <x-core::button type="submit" color="primary">
                    {{ trans('packages/data-synchronize::data-synchronize.export.form.export_button') }}
                </x-core::button>
            </x-core::card.footer>
        </x-core::card>
    </x-core::form>
@stop
