import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// Read CSRF token from <meta name="csrf-token"> and send it as X-CSRF-TOKEN header.
// This replaces the XSRF-TOKEN cookie approach so the cookie can be disabled.
const csrfToken = document.head.querySelector('meta[name="csrf-token"]');
if (csrfToken) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = csrfToken.getAttribute('content');
}
