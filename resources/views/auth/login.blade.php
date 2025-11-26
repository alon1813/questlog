<x-guest-layout>
    <div class="w-full max-w-md bg-[var(--bg-secondary)] p-8 md:p-12 rounded-lg border border-[var(--border-color)]">
        <img src="{{ asset('images/logo4.png') }}" alt="" class="w-full h-40 object-cover">
        
        <h2 class="text-3xl font-bold text-center mb-6">Bienvenido a QuestLog</h2>

        {{-- ✅ Mostrar mensajes de estado (ej: "Te hemos enviado un enlace") --}}
        @if (session('status'))
            <div class="mb-4 p-4 bg-green-900 border border-green-600 text-green-200 rounded-lg text-sm">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="mb-4">
                <label for="email" class="block mb-2 text-sm font-medium text-[var(--text-secondary)]">Correo Electrónico</label>
                <input 
                    id="email" 
                    type="email" 
                    name="email" 
                    value="{{ old('email') }}" 
                    required 
                    autofocus 
                    autocomplete="username"
                    class="w-full p-3 rounded-md border border-[var(--border-color)] bg-[var(--bg-primary)] text-[var(--text-secondary)] focus:border-[var(--text-primary)] focus:ring-2 focus:ring-[var(--text-primary)] transition-colors"
                >
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div class="mb-6">
                <label for="password" class="block mb-2 text-sm font-medium text-[var(--text-secondary)]">Contraseña</label>
                <input 
                    id="password" 
                    type="password" 
                    name="password" 
                    required 
                    autocomplete="current-password"
                    class="w-full p-3 rounded-md border border-[var(--border-color)] bg-[var(--bg-primary)] text-[var(--text-secondary)] focus:border-[var(--text-primary)] focus:ring-2 focus:ring-[var(--text-primary)] transition-colors"
                >
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            {{-- ✅ AÑADIR: Recordarme y Olvidé mi contraseña --}}
            <div class="flex items-center justify-between mb-6">
                <label class="flex items-center">
                    <input 
                        type="checkbox" 
                        name="remember" 
                        class="rounded border-gray-300 text-[var(--text-primary)] shadow-sm focus:ring-[var(--text-primary)]"
                    >
                    <span class="ml-2 text-sm text-[var(--text-secondary)]">Recordarme</span>
                </label>

                <a 
                    href="{{ route('password.request') }}" 
                    class="text-sm text-[var(--text-primary)] hover:underline"
                >
                    ¿Olvidaste tu contraseña?
                </a>
            </div>

            <button 
                type="submit" 
                class="w-full py-3 bg-[var(--text-primary)] text-white font-bold rounded-lg uppercase hover:opacity-80 transition-opacity"
            >
                Iniciar Sesión
            </button>
        </form>

        <div class="text-center my-6 text-gray-400">o</div>
        
        <p class="text-center">
            ¿No tienes cuenta? 
            <a href="{{ route('register') }}" class="font-bold text-[var(--text-primary)] hover:underline">
                Regístrate ahora
            </a>
        </p>
    </div>
</x-guest-layout>