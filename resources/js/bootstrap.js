// Bootstrap del proyecto - configuración inicial de JavaScript

// Axios para requests HTTP
import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// Configurar CSRF token
let token = document.head.querySelector('meta[name="csrf-token"]');

if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}

// Alpine.js - Configuración específica para Livewire
import Alpine from 'alpinejs';

// Hacer Alpine.js disponible globalmente antes de que Livewire lo necesite
window.Alpine = Alpine;

// No llamar Alpine.start() aquí - Livewire lo manejará
// Alpine.start(); // <-- Comentado para evitar conflictos

// Echo para broadcast (comentado por ahora)
// import Echo from 'laravel-echo';
// import Pusher from 'pusher-js';
// window.Pusher = Pusher;
// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: import.meta.env.VITE_PUSHER_APP_KEY,
//     cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
//     forceTLS: true
// });
