import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
window.axios.defaults.withCredentials = true; 
window.axios.defaults.withXSRFToken = true;

let token = document.head.querySelector('meta[name="csrf-token"]');

if (token) {
    window.axios.defaults.headers.common['X-XSRF-TOKEN'] = token.content; 
    console.log('[Axios Config] X-XSRF-TOKEN configurado:', token.content);
} else {
    console.error('CSRF token not found');
}