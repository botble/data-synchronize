<x-core::empty-state
    :$title
    :subtitle="$description"
    :$icon
>
    @if($actionLabel)
        <x-slot:action>
            <x-core::button tag="a" :href="$actionUrl" color="primary" icon="ti ti-arrow-left">
                {{ $actionLabel }}
            </x-core::button>
        </x-slot:action>
    @endif
</x-core::empty-state>
