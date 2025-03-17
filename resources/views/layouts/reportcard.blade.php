@props(['bulletin'])
<div class="m-3 report-card">
    @include('layouts.reportcardheader')
    <main>
        {{ $slot }}
    </main>
    @include('layouts.reportcardfooter')
    <!-- JS file -->
    <script src="{{ asset('bootstrap/js/bootstrap.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</div>