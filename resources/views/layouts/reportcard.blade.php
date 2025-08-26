@props(['bulletin','principal','effectif'])
<div class="m-3 report-card">
    @include('layouts.reportcardheader')
    <main>
        {{ $slot }}
    </main>
    @include('layouts.reportcardfooter')
</div>
