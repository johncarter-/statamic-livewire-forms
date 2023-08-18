<div>
    <form wire:submit.prevent="submit">
        {{-- Choose: 1. Manual field rendering --}}
        {{--
            <input autocomplete="name" type="text" wire:model.lazy="data.handle" />
            @error('data.handle')<div>{{ $message }}</div>@enderror
        --}}

        {{-- Or, 2. Automatic field rendering --}}
        <div class="grid grid-cols-12 gap-4">
            @foreach ($fields as $fieldHandle => $config)
                @php
                    $colSpanClass = match ($config['width'] ?? null) {
                        25 => 'col-span-3',
                        33 => 'col-span-4',
                        50 => 'col-span-6',
                        66 => 'col-span-8',
                        75 => 'col-span-9',
                        100 => 'col-span-12',
                        default => 'col-span-12',
                    };
                @endphp

                <div class="{{ $colSpanClass }}">
                    <label>
                        <span class="block w-full">{{ $config['display'] }}</span>
                        @switch($config['type'])
                            @case('text')
                                <input class="w-full" type="{{ $config['input_type'] }}" wire:model.lazy="data.{{ $fieldHandle }}">
                            @break

                            @case('textarea')
                                <textarea class="w-full" wire:model.lazy="data.{{ $fieldHandle }}"></textarea>
                            @break

                            @default
                        @endswitch
                    </label>

                    @error('data.' . $fieldHandle)
                        <div>{{ $message }}</div>
                    @enderror
                </div>
            @endforeach
        </div>

        <button>Submit</button>

        @if ($errors->any())
            <ul>
                @foreach ($errors->all() as $error)
                    <li class="mb-1">{{ $error }}</li>
                @endforeach
            </ul>
        @endif
        @if ($success)
            <div>
                <p>Thanks!</p>
            </div>
        @endif
    </form>
</div>
