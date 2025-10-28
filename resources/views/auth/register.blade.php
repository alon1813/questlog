<x-guest-layout>
    <div class="w-full max-w-md bg-[var(--bg-secondary)] p-8 md:p-12 rounded-lg border border-[var(--border-color)]">
        <img src="{{ asset('images/logo4.png') }}" alt="" class="w-full h-40 object-cover">
        <h2 class="text-3xl font-bold text-center mb-6">Crea tu Cuenta</h2>
        <form method="POST" action="{{ route('register') }}">
    @csrf
    <div class="mb-4">
        <label for="name" class="block mb-2 text-sm font-medium text-[var(--text-secondary)]">Nombre</label>
        <input id="name" type="text" name="name" :value="old('name')" required autofocus class="w-full p-3 rounded-md border border-[var(--border-color)] bg-[var(--bg-primary)] text-[var(--text-secondary)]">
        <x-input-error :messages="$errors->get('name')" class="mt-2" />
    </div>

    <div class="mb-4">
        <label for="username" class="block mb-2 text-sm font-medium text-[var(--text-secondary)]">Nombre de Usuario</label>
        <input id="username" type="text" name="username" :value="old('username')" required class="w-full p-3 rounded-md border border-[var(--border-color)] bg-[var(--bg-primary)] text-[var(--text-secondary)]">
        <x-input-error :messages="$errors->get('username')" class="mt-2" />
    </div>

    <div class="mb-4">
        <label for="email" class="block mb-2 text-sm font-medium text-[var(--text-secondary)]">Correo Electrónico</label>
        <input id="email" type="email" name="email" :value="old('email')" required class="w-full p-3 rounded-md border border-[var(--border-color)] bg-[var(--bg-primary)] text-[var(--text-secondary)]">
        <x-input-error :messages="$errors->get('email')" class="mt-2" />
    </div>

    <div class="mb-4">
        <label for="password" class="block mb-2 text-sm font-medium text-[var(--text-secondary)]">Contraseña</label>
        <input id="password" type="password" name="password" required class="w-full p-3 rounded-md border border-[var(--border-color)] bg-[var(--bg-primary)] text-[var(--text-secondary)]">
        <x-input-error :messages="$errors->get('password')" class="mt-2" />
    </div>

    <div class="mb-6">
        <label for="password_confirmation" class="block mb-2 text-sm font-medium text-[var(--text-secondary)]">Confirmar Contraseña</label>
        <input id="password_confirmation" type="password" name="password_confirmation" required class="w-full p-3 rounded-md border border-[var(--border-color)] bg-[var(--bg-primary)] text-[var(--text-secondary)]">
        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
    </div>

    <button type="submit" class="w-full mt-6 py-3 bg-[var(--text-primary)] text-white font-bold rounded-lg uppercase hover:opacity-80">Registrarse</button>
</form>
        <p class="text-center mt-6">¿Ya tienes cuenta? <a href="{{ route('login') }}" class="font-bold hover:underline">Inicia sesión</a></p>
    </div>
</x-guest-layout>