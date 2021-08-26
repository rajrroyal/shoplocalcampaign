import io from 'socket.io-client';
window.io = io;

import Echo from 'laravel-echo';
window.Echo = new Echo({
    broadcaster: 'socket.io',
    host: window.location.host,
    path: '/socket.io',
});

import toasts from './utils/toasts';
window.toasts = toasts;

window.logout = () => {
    document.getElementById('logout-form').submit();
};
