<x-guest-layout>
    <div class="w-full max-w-md bg-[var(--bg-secondary)] p-8 md:p-12 rounded-lg border border-[var(--border-color)]">
        <img src="{{ asset('images/logo4.png') }}" alt="" class="w-full h-40 object-cover mb-6">
        
        <h2 class="text-3xl font-bold text-center mb-6 text-white">Restablecer Contraseña</h2>

        <form method="POST" action="{{ route('password.store') }}">
            @csrf

            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <div class="mb-4">
                <label for="email" class="block mb-2 text-sm font-medium text-[var(--text-secondary)]">
                    Correo Electrónico
                </label>
                <input 
                    id="email" 
                    type="email" 
                    name="email" 
                    value="{{ old('email', $request->email) }}" 
                    required 
                    autofocus 
                    autocomplete="username"
                    class="w-full p-3 rounded-md border border-[var(--border-color)] bg-[var(--bg-primary)] text-[var(--text-secondary)] focus:border-[var(--text-primary)] focus:ring-2 focus:ring-[var(--text-primary)] transition-colors"
                >
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div class="mb-4">
                <label for="password" class="block mb-2 text-sm font-medium text-[var(--text-secondary)]">
                    Nueva Contraseña
                </label>
                <input 
                    id="password" 
                    type="password" 
                    name="password" 
                    required 
                    autocomplete="new-password"
                    class="w-full p-3 rounded-md border border-[var(--border-color)] bg-[var(--bg-primary)] text-[var(--text-secondary)] focus:border-[var(--text-primary)] focus:ring-2 focus:ring-[var(--text-primary)] transition-colors"
                >
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
                
                <p class="mt-2 text-xs text-gray-400">
                    La contraseña debe tener mínimo 8 caracteres, incluir mayúsculas, minúsculas, números y símbolos.
                </p>
            </div>

            <div class="mb-6">
                <label for="password_confirmation" class="block mb-2 text-sm font-medium text-[var(--text-secondary)]">
                    Confirmar Contraseña
                </label>
                <input 
                    id="password_confirmation" 
                    type="password" 
                    name="password_confirmation" 
                    required 
                    autocomplete="new-password"
                    class="w-full p-3 rounded-md border border-[var(--border-color)] bg-[var(--bg-primary)] text-[var(--text-secondary)] focus:border-[var(--text-primary)] focus:ring-2 focus:ring-[var(--text-primary)] transition-colors"
                >
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>

            <button 
                type="submit" 
                class="w-full py-3 bg-[var(--text-primary)] text-white font-bold rounded-lg uppercase hover:opacity-80 transition-opacity"
            >
                Restablecer Contraseña
            </button>
        </form>
    </div>
</x-guest-layout>