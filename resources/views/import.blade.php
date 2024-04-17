@extends(BaseHelper::getAdminMasterLayoutTemplate())

@section('content')
    @php
        /** @var \Botble\DataSynchronize\Importer\Importer $importer */

        $acceptedFiles = Arr::join(array_map(fn ($item) => ".$item", $importer->getAcceptedFiles()), ',');
    @endphp

    <x-core::form
        method="post"
        :url="route('data-synchronize.upload')"
        :data-validate-url="$importer->getValidateUrl()"
        :data-import-url="$importer->getImportUrl()"
        data-bb-toggle="import-form"
        :data-accepted-files="$acceptedFiles"
        :data-chunk-size="$importer->chunkSize()"
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

                <div>
                    <div class="dropzone">
                        <div class="dz-message">
                            {{ trans('packages/data-synchronize::data-synchronize.import.form.dropzone_message') }}
                        </div>
                    </div>

                    <x-core::form.helper-text class="mt-1">
                        {{ trans('packages/data-synchronize::data-synchronize.import.form.mime_types_allowed', ['types' => Arr::join($importer->getAcceptedFiles(), ', ')]) }}
                    </x-core::form.helper-text>
                </div>

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
    </x-core::form>

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

    @include('packages/data-synchronize::partials.rules')
@stop

@push('footer')
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
@endpush
