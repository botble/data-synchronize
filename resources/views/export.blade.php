@extends($exporter->getLayout())

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
                @if($exporter->hasDataToExport())
                    @php
                        $countersCount = count($exporter->getCounters());
                    @endphp

                    @if($countersCount)
                        <div class="mb-5">
                            <div @class(['row g-3', 'row-cols-2' => $countersCount > 1, 'row-cols-sm-3' => $countersCount > 2, 'row-cols-lg-4' => $countersCount > 3])>
                                @foreach($exporter->getCounters() as $counter)
                                    <div class="col">
                                        <div class="text-center bg-body-tertiary rounded p-3">
                                            <h3 class="text-muted mb-2">{{ $counter->getLabel() }}</h3>
                                            <div class="fs-1 fw-bold">{{ $counter->getValue() }}</div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @yield('export_extra_filters_before')

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
                                <a href="javascript:void(0)" class="ms-2 text-primary check-all-columns">{{ trans('packages/data-synchronize::data-synchronize.check_all') }}</a>
                            </x-core::form.label>

                            <div @class(['row row-cols-1', 'row-cols-sm-2 row-cols-lg-3' => count($exporter->getColumns()) > 6])>
                                @foreach ($exporter->getColumns() as $column)
                                    <x-core::form-group>
                                        <x-core::form.checkbox
                                            class="export-column"
                                            name="columns[]"
                                            :value="$column->getName()"
                                            :label="$column->getLabel()"
                                            :disabled="$column->isDisabled()"
                                            checked
                                        />
                                    </x-core::form-group>
                                @endforeach
                            </div>
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

                    @yield('export_extra_filters_after')
                @else
                    {!! $exporter->getEmptyStateContent() !!}
                @endif
            </x-core::card.body>
                @if($exporter->hasDataToExport())
                    <x-core::card.footer>
                        <x-core::button type="submit" color="primary">
                            {{ trans('packages/data-synchronize::data-synchronize.export.form.export_button') }}
                        </x-core::button>
                    </x-core::card.footer>
                @endif
        </x-core::card>
    </x-core::form>

    @push('footer')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const form = document.querySelector('.data-synchronize-export-form');
                const storageKey = 'data-synchronize-export-form-' + window.location.pathname;
                const columnCheckboxes = form.querySelectorAll('.export-column');
                const checkAllButton = form.querySelector('.check-all-columns');

                // Function to save form values to localStorage
                function saveFormValues() {
                    const formData = new FormData(form);
                    const values = {};

                    // Save all form fields except _token
                    for (const [key, value] of formData.entries()) {
                        // Skip the CSRF token field
                        if (key === '_token') {
                            continue;
                        }

                        if (key === 'columns[]') {
                            if (!values.columns) {
                                values.columns = [];
                            }
                            values.columns.push(value);
                        } else {
                            values[key] = value;
                        }
                    }

                    localStorage.setItem(storageKey, JSON.stringify(values));
                }

                // Function to restore form values from localStorage
                function restoreFormValues() {
                    const savedValues = localStorage.getItem(storageKey);
                    if (!savedValues) return;

                    const values = JSON.parse(savedValues);

                    // Restore all form fields except _token
                    Object.entries(values).forEach(([key, value]) => {
                        // Skip the CSRF token field
                        if (key === '_token') {
                            return;
                        }

                        if (key === 'columns') {
                            // Handle checkboxes
                            columnCheckboxes.forEach(checkbox => {
                                checkbox.checked = value.includes(checkbox.value);
                            });
                        } else {
                            // Handle other form fields
                            const input = form.querySelector(`[name="${key}"]`);
                            if (input) {
                                if (input.type === 'radio') {
                                    const radio = form.querySelector(`input[name="${key}"][value="${value}"]`);
                                    if (radio) {
                                        radio.checked = true;
                                    }
                                } else {
                                    input.value = value;
                                }
                            }
                        }
                    });
                }

                // Handle check all functionality
                checkAllButton.addEventListener('click', function(e) {
                    e.preventDefault();
                    const allChecked = Array.from(columnCheckboxes).every(checkbox => checkbox.checked);

                    columnCheckboxes.forEach(checkbox => {
                        if (!checkbox.disabled) {
                            checkbox.checked = !allChecked;
                        }
                    });

                    saveFormValues();
                });

                // Save form values when any input changes
                form.addEventListener('change', saveFormValues);
                form.addEventListener('input', saveFormValues);

                // Restore form values when page loads
                restoreFormValues();
            });
        </script>
    @endpush
@stop
