import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
window.axios.defaults.withCredentials = true; // ¡MUY IMPORTANTE!

let token = document.head.querySelector('meta[name="csrf-token"]');

if (token) {
    window.axios.defaults.headers.common['X-XSRF-TOKEN'] = token.content; // ¡X-XSRF-TOKEN!
    console.log('[Axios Config] X-XSRF-TOKEN configurado:', token.content);
} else {
    console.error('CSRF token not found');
}