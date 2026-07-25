@php
    $record = $getRecord();
    $state = $getState();
    $name = $getName();

    $options = [
        'win' => ['label' => 'Win', 'color' => 'success'],
        'lose' => ['label' => 'Lose', 'color' => 'danger'],
        'return' => ['label' => 'Return', 'color' => 'warning'],
    ];
@endphp

<div class="inline-flex rounded-lg bg-gray-100 p-1 dark:bg-gray-800" onclick="event.stopPropagation()">
    @foreach ($options as $value => $option)
        @php
            $isActive = $state === $value;
        @endphp

        <button
            type="button"
            wire:click="$call('updateTableColumnState', '{{ $name }}', '{{ $record->getKey() }}', '{{ $value }}')"
            @class([
                'px-2.5 py-1 text-xs font-semibold rounded-md transition-colors duration-150',
                // Активная кнопка
                'bg-white text-gray-900 shadow-sm dark:bg-gray-700 dark:text-white' => $isActive,
                'text-emerald-600 dark:text-emerald-400' => $isActive && $option['color'] === 'success',
                'text-rose-600 dark:text-rose-400' => $isActive && $option['color'] === 'danger',
                'text-amber-600 dark:text-amber-400' => $isActive && $option['color'] === 'warning',

                // Неактивная кнопка
                'text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200' => ! $isActive,
            ])
        >
            {{ $option['label'] }}
        </button>
    @endforeach
</div>
