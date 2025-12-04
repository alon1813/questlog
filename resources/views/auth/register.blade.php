<x-guest-layout>
    <div class="w-full max-w-md bg-[var(--bg-secondary)] p-8 md:p-12 rounded-lg border border-[var(--border-color)]">
        <img src="{{ asset('images/logo4.png') }}" alt="" class="w-full h-40 object-cover">
        <h2 class="text-3xl font-bold text-center mb-6">Crea tu Cuenta</h2>

        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-900 border border-red-600 text-red-200 rounded-lg">
                <p class="font-bold mb-2">⚠️ Por favor, corrige los siguientes errores:</p>
                <ul class="list-disc list-inside text-sm space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}">
            @csrf
            
            <div class="mb-4">
                <label for="name" class="block mb-2 text-sm font-medium text-[var(--text-secondary)]">
                    Nombre Completo
                </label>
                <input 
                    id="name" 
                    type="text" 
                    name="name" 
                    value="{{ old('name') }}" 
                    required 
                    autofocus
                    class="w-full p-3 rounded-md border @error('name') border-red-500 @else border-[var(--border-color)] @enderror bg-[var(--bg-primary)] text-[var(--text-secondary)]"
                >
                @error('name')
                    <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="username" class="block mb-2 text-sm font-medium text-[var(--text-secondary)]">
                    Nombre de Usuario
                </label>
                <input 
                    id="username" 
                    type="text" 
                    name="username" 
                    value="{{ old('username') }}" 
                    required
                    class="w-full p-3 rounded-md border @error('username') border-red-500 @else border-[var(--border-color)] @enderror bg-[var(--bg-primary)] text-[var(--text-secondary)]"
                >
                @error('username')
                    <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="email" class="block mb-2 text-sm font-medium text-[var(--text-secondary)]">
                    Correo Electrónico
                </label>
                <input 
                    id="email" 
                    type="email" 
                    name="email" 
                    value="{{ old('email') }}" 
                    required
                    class="w-full p-3 rounded-md border @error('email') border-red-500 @else border-[var(--border-color)] @enderror bg-[var(--bg-primary)] text-[var(--text-secondary)]"
                >
                @error('email')
                    <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="password" class="block mb-2 text-sm font-medium text-[var(--text-secondary)]">
                    Contraseña
                </label>
                <input 
                    id="password" 
                    type="password" 
                    name="password" 
                    required
                    class="w-full p-3 rounded-md border @error('password') border-red-500 @else border-[var(--border-color)] @enderror bg-[var(--bg-primary)] text-[var(--text-secondary)]"
                >
                <p class="mt-1 text-xs text-gray-400">
                    Mínimo 8 caracteres: mayúsculas, minúsculas, números y símbolos
                </p>
                @error('password')
                    <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                @enderror
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
                    class="w-full p-3 rounded-md border border-[var(--border-color)] bg-[var(--bg-primary)] text-[var(--text-secondary)]"
                >
            </div>

            <button 
                type="submit" 
                class="w-full mt-6 py-3 bg-[var(--text-primary)] text-white font-bold rounded-lg uppercase hover:opacity-80"
            >
                Registrarse
            </button>
        </form>

        <p class="text-center mt-6">
            ¿Ya tienes cuenta? 
            <a href="{{ route('login') }}" class="font-bold hover:underline">Inicia sesión</a>
        </p>
    </div>
</x-guest-layout>