@props(['count' => 5])

<div class="px-2 pt-3 pb-1" aria-hidden="true">
    @for ($i = 0; $i < $count; $i++)
        <x-scoutify::gs.skeleton-row :delay="($i * 60).'ms'" />
    @endfor
</div>
