<x-mail::message>
# 🎮 ¡Bienvenido a QuestLog, {{ $user->name }}!

Estamos emocionados de tenerte en nuestra comunidad de gamers y otakus.

## 🚀 Empieza tu aventura

Aquí hay algunas cosas que puedes hacer:

- **📚 Crea tu colección** - Añade tus juegos y animes favoritos
- **⭐ Puntúa y reseña** - Comparte tus opiniones con la comunidad
- **👥 Sigue a otros usuarios** - Descubre qué están jugando/viendo
- **🛒 Visita la tienda** - Encuentra merchandising exclusivo

<x-mail::button :url="route('dashboard')">
Ir a Mi Dashboard
</x-mail::button>

Si tienes alguna pregunta, no dudes en contactarnos.

¡Que disfrutes tu aventura!<br>
El equipo de QuestLog
</x-mail::message>