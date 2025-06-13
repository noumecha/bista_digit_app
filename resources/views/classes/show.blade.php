<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <x-app.navbar />
        <div class="container-fluid py-4">
            classe page - {{ $classe->libClasse }}
        </div>
        <x-app.footer />
    </main>
</x-app-layout>