<x-core::card class="mt-3">
    <x-core::card.header class="flex-wrap gap-3">
        <x-core::card.title>
            {{ trans('packages/data-synchronize::data-synchronize.import.example.title') }}
        </x-core::card.title>

        <x-core::card.actions class="d-flex flex-wrap gap-1 ps-0">
            <x-core::form method="post" :url="$importer->getDownloadExampleUrl()">
                <x-core::button type="submit" icon="ti ti-file-type-csv" name="format" value="csv">
                    {{ trans('packages/data-synchronize::data-synchronize.import.example.download', ['type' => 'CSV']) }}
                </x-core::button>
                <x-core::button type="submit" icon="ti ti-file-spreadsheet" name="format" value="xlsx">
                    {{ trans('packages/data-synchronize::data-synchronize.import.example.download', ['type' => 'Excel']) }}
                </x-core::button>
            </x-core::form>
        </x-core::card.actions>
    </x-core::card.header>
    <div class="table-responsive">
        <x-core::table>
            <x-core::table.header>
                @foreach ($importer->getColumns() as $column)
                    <x-core::table.header.cell>
                        {{ $column->getLabel() }}
                    </x-core::table.header.cell>
                @endforeach
            </x-core::table.header>
            <x-core::table.body>
                @foreach ($importer->getExamples() as $example)
                    <x-core::table.body.row>
                        @foreach ($importer->getColumns() as $column)
                            <x-core::table.body.cell>
                                {{ Arr::get($example, $column->getName()) }}
                            </x-core::table.body.cell>
                        @endforeach
                    </x-core::table.body.row>
                @endforeach
            </x-core::table.body>
        </x-core::table>
    </div>
</x-core::card>
