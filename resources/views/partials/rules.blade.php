<x-core::card class="mt-3">
    <x-core::card.header>
        <x-core::card.title>
            {{ trans('packages/data-synchronize::data-synchronize.import.rules.title') }}
        </x-core::card.title>
    </x-core::card.header>
    <div class="table-responsive">
        <x-core::table>
            <x-core::table.header>
                <x-core::table.header.cell>
                    {{ trans('packages/data-synchronize::data-synchronize.import.rules.column') }}
                </x-core::table.header.cell>
                <x-core::table.header.cell>
                    {{ trans('packages/data-synchronize::data-synchronize.import.rules.title') }}
                </x-core::table.header.cell>
            </x-core::table.header>
            <x-core::table.body>
                @foreach($importer->getColumns() as $column)
                    <x-core::table.body.row>
                        <x-core::table.body.cell>
                            {{ $column->getLabel() }}
                        </x-core::table.body.cell>
                        <x-core::table.body.cell>
                            {{ $column->getRulesDescription() ?: Arr::join($column->getRules(), ', ') }}
                        </x-core::table.body.cell>
                    </x-core::table.body.row>
                @endforeach
            </x-core::table.body>
        </x-core::table>
    </div>
</x-core::card>
