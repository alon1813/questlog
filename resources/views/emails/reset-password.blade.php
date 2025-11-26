<x-mail::message>
# Restablecer Contraseña

Has recibido este email porque se ha solicitado un restablecimiento de contraseña para tu cuenta en QuestLog.

<x-mail::button :url="$url">
Restablecer Contraseña
</x-mail::button>

Este enlace de restablecimiento expirará en {{ config('auth.passwords.'.config('auth.defaults.passwords').'.expire') }} minutos.

Si no solicitaste un restablecimiento de contraseña, no es necesario realizar ninguna acción.

Saludos,<br>
El equipo de {{ config('app.name') }}

---

Si tienes problemas al hacer clic en el botón "Restablecer Contraseña", copia y pega la siguiente URL en tu navegador:

{{ $url }}
</x-mail::message>