<x-guest-layout>
    <div class="w-full max-w-md bg-[var(--bg-secondary)] p-8 md:p-12 rounded-lg border border-[var(--border-color)]">
        <img src="{{ asset('images/logo4.png') }}" alt="" class="w-full h-40 object-cover mb-6">
        
        <h2 class="text-3xl font-bold text-center mb-4 text-white">¿Olvidaste tu contraseña?</h2>
        
        <p class="text-sm text-[var(--text-secondary)] mb-6 text-center">
            No hay problema. Solo dinos tu dirección de correo electrónico y te enviaremos un enlace para restablecer tu contraseña.
        </p>

        @if (session('status'))
            <div class="mb-4 p-4 bg-green-900 border border-green-600 text-green-200 rounded-lg text-sm">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="mb-6">
                <label for="email" class="block mb-2 text-sm font-medium text-[var(--text-secondary)]">
                    Correo Electrónico
                </label>
                <input 
                    id="email" 
                    type="email" 
                    name="email" 
                    value="{{ old('email') }}" 
                    required 
                    autofocus
                    class="w-full p-3 rounded-md border border-[var(--border-color)] bg-[var(--bg-primary)] text-[var(--text-secondary)] focus:border-[var(--text-primary)] focus:ring-2 focus:ring-[var(--text-primary)] transition-colors"
                    placeholder="tu@email.com"
                >
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <button 
                type="submit" 
                class="w-full py-3 bg-[var(--text-primary)] text-white font-bold rounded-lg uppercase hover:opacity-80 transition-opacity"
            >
                Enviar Enlace de Recuperación
            </button>
        </form>

        <div class="text-center mt-6">
            <a 
                href="{{ route('login') }}" 
                class="text-sm text-[var(--text-primary)] hover:underline"
            >
                ← Volver al inicio de sesión
            </a>
        </div>
    </div>
</x-guest-layout>