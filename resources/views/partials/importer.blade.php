@php
    /** @var \Botble\DataSynchronize\Importer\Importer $importer */

    $acceptedFiles = Arr::join(array_map(fn ($item) => $item, $importer->getAcceptedFiles()), ',');
@endphp

{!! apply_filters('data_synchronize_import_page_before', null, $importer) !!}

<x-core::form
    method="post"
    :url="$importer->getUploadUrl()"
    :data-validate-url="$importer->getValidateUrl()"
    :data-import-url="$importer->getImportUrl()"
    data-bb-toggle="import-form"
    :data-accepted-files="$acceptedFiles"
    :data-uploading-message="trans('packages/data-synchronize::data-synchronize.import.uploading_message')"
    :data-validate-failed-message="trans('packages/data-synchronize::data-synchronize.import.validating_failed_message')"
    class="data-synchronize-import-form"
>
    <x-core::card>
        @if($importer->getHeading())
            <x-core::card.header>
                <x-core::card.title>
                    {{ $importer->getHeading() }}
                </x-core::card.title>
            </x-core::card.header>
        @endif

        <x-core::card.body>
            @if($importer->getExportUrl())
                <x-core::alert
                    type="info"
                    :data-url="$importer->getExportUrl()"
                >
                    {!! BaseHelper::clean(trans(
                        'packages/data-synchronize::data-synchronize.import.form.quick_export_message',
                        [
                            'label' => $importer->getLabel(),
                            'export_csv_link' => Html::tag('button', trans('packages/data-synchronize::data-synchronize.import.form.quick_export_button', ['format' => 'CSV']), [
                                'class' => 'data-synchronize-quick-export-button',
                                'data-bb-toggle' => 'quick-export',
                                'data-value' => 'csv',
                            ]),
                            'export_excel_link' => Html::tag('button', trans('packages/data-synchronize::data-synchronize.import.form.quick_export_button', ['format' => 'Excel']), [
                                'class' => 'data-synchronize-quick-export-button',
                                'data-bb-toggle' => 'quick-export',
                                'data-value' => 'xlsx',
                            ]),
                        ]
                    )) !!}
                </x-core::alert>
            @endif

            {!! apply_filters('data_synchronize_import_form_before', null, $importer) !!}

            <div class="mb-3">
                <div class="dropzone">
                    <div class="dz-message">
                        {{ trans('packages/data-synchronize::data-synchronize.import.form.dropzone_message') }}
                    </div>
                </div>

                <x-core::form.helper-text class="mt-1">
                    {{ trans('packages/data-synchronize::data-synchronize.import.form.allowed_extensions', ['extensions' => Arr::join($importer->getFileExtensions(), ', ')]) }}
                </x-core::form.helper-text>
            </div>

            <div class="mb-3">
                <x-core::form.label for="chunk-size">{{ trans('packages/data-synchronize::data-synchronize.import.form.chunk_size') }}</x-core::form.label>
                <x-core::form.text-input type="number" class="form-control" id="chunk-size" name="chunk_size" value="{{ $importer->chunkSize() }}" />
                <x-core::form.helper-text>
                    {{ trans('packages/data-synchronize::data-synchronize.import.form.chunk_size_helper') }}
                </x-core::form.helper-text>
            </div>

            {!! apply_filters('data_synchronize_import_form_after', null, $importer) !!}

            <pre class="mt-3 data-synchronize-import-output" style="display: none"></pre>
        </x-core::card.body>
        <x-core::card.footer>
            <x-core::button type="submit" color="primary" :disabled="true">
                {{ trans('packages/data-synchronize::data-synchronize.import.form.import_button') }}
            </x-core::button>
        </x-core::card.footer>
    </x-core::card>

    <x-core::alert class="mt-3" type="danger" style="display: none" data-bb-toggle="import-errors">
        <ul></ul>
    </x-core::alert>

    <x-core::card class="mt-3 bg-warning-lt" data-bb-toggle="import-failures" style="display: none">
        <x-core::card.header>
            <x-core::card.title>
                {{ trans('packages/data-synchronize::data-synchronize.import.failures.title') }}
            </x-core::card.title>
        </x-core::card.header>
        <x-core::table>
            <x-core::table.header>
                <x-core::table.header.cell>
                    #
                </x-core::table.header.cell>
                <x-core::table.header.cell>
                    {{ trans('packages/data-synchronize::data-synchronize.import.failures.attribute') }}
                </x-core::table.header.cell>
                <x-core::table.header.cell>
                    {{ trans('packages/data-synchronize::data-synchronize.import.failures.errors') }}
                </x-core::table.header.cell>
            </x-core::table.header>
            <x-core::table.body></x-core::table.body>
        </x-core::table>
    </x-core::card>
</x-core::form>

<template id="failures-template">
    <x-core::table.body.row>
        <x-core::table.body.cell>
            __index__
        </x-core::table.body.cell>
        <x-core::table.body.cell>
            __attribute__
        </x-core::table.body.cell>
        <x-core::table.body.cell>
            __errors__
        </x-core::table.body.cell>
    </x-core::table.body.row>
</template>

{!! apply_filters('data_synchronize_import_page_after', null, $importer) !!}

@if($importer->getExportUrl())
    <x-core::form
        method="POST"
        :url="$importer->getExportUrl()"
        data-bb-toggle="export-data"
        :data-success-message="trans('packages/data-synchronize::data-synchronize.export.success_message')"
        :data-error-message="trans('packages/data-synchronize::data-synchronize.export.error_message')"
    />
@endif

@if ($importer->getExamples())
    @include('packages/data-synchronize::partials.example')
@endif

@includeWhen($importer->showRulesCheatSheet(), 'packages/data-synchronize::partials.rules')

<x-core::custom-template id="data-synchronize-import-preview-template">
    <div class="position-relative d-flex gap-2 data-synchronize-import-preview-template">
        <x-core::icon name="ti ti-file" style="height: 2rem; width: 2rem" />
        <div>
            <h4><span data-dz-name></span></h4>
            <div class="d-flex align-items-center small text-muted">
                <span data-dz-size></span>
                <button type="button" class="ms-1 text-danger cursor-pointer" data-dz-remove>
                    <x-core::icon name="ti ti-trash" />
                </button>
            </div>
            <div class="text-danger small" data-dz-errormessage></div>
        </div>
    </div>
</x-core::custom-template>

{!! apply_filters('data_synchronize_import_page_footer', null, $importer) !!}
