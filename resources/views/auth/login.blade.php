<x-guest-layout>
    <div class="w-full max-w-md bg-[var(--bg-secondary)] p-8 md:p-12 rounded-lg border border-[var(--border-color)]">
        <img src="{{ asset('images/logo4.png') }}" alt="" class="w-full h-40 object-cover">
        
            <h2 class="text-3xl font-bold text-center mb-6">Bienvenido a QuestLog</h2>
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="mb-4">
                    <label for="email" class="block mb-2 text-sm font-medium text-[var(--text-secondary)]">Correo Electrónico</label>
                    <input id="email" type="email" name="email" :value="old('email')" required autofocus class="w-full p-3 rounded-md border border-[var(--border-color)] bg-[var(--bg-primary)] text-[var(--text-secondary)]">
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>
                <div class="mb-6">
                    <label for="password" class="block mb-2 text-sm font-medium text-[var(--text-secondary)]">Contraseña</label>
                    <input id="password" type="password" name="password" required class="w-full p-3 rounded-md border border-[var(--border-color)] bg-[var(--bg-primary)] text-[var(--text-secondary)]">
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>
                <button type="submit" class="w-full py-3 bg-[var(--text-primary)] text-white font-bold rounded-lg uppercase hover:opacity-80">Iniciar Sesión</button>
            </form>
            <div class="text-center my-6 text-gray-400">o</div>
            <p class="text-center">¿No tienes cuenta? <a href="{{ route('register') }}" class="font-bold hover:underline">Regístrate ahora</a></p>
        
    </div>
</x-guest-layout>