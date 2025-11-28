<x-guest-layout>
    <div class="w-full max-w-md bg-[var(--bg-secondary)] p-8 md:p-12 rounded-lg border border-[var(--border-color)] text-center">
        
        <div class="mb-6">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-green-500 rounded-full animate-bounce">
                <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
        </div>

        <h2 class="text-3xl font-bold text-white mb-4">¡Email Verificado!</h2>
        
        <p class="text-[var(--text-secondary)] mb-6">
            Tu cuenta ha sido verificada exitosamente. Te hemos enviado un email de bienvenida con información útil.
        </p>

        <div class="bg-[var(--bg-primary)] border border-[var(--border-color)] rounded-lg p-4 mb-6">
            <p class="text-sm text-gray-400 flex items-center justify-center">
                <svg class="animate-spin h-4 w-4 mr-2 text-[var(--text-primary)]" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Redirigiendo al dashboard en unos segundos...
            </p>
        </div>

        <a href="{{ route('dashboard') }}" class="inline-block w-full py-3 bg-[var(--text-primary)] text-white font-bold rounded-lg uppercase hover:opacity-80 transition-opacity">
            Ir al Dashboard Ahora
        </a>
    </div>

    <script>
        setTimeout(function() {
            window.location.href = "{{ route('dashboard') }}?verified=1&welcome=sent";
        }, 10000);
    </script>
</x-guest-layout>