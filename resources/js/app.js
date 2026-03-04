import './bootstrap';

import { createApp } from 'vue';
import Dashboard from './components/Dashboard.vue';
import Tickets from './components/Tickets.vue';
import Assets from './components/Assets.vue';
import Reservations from './components/Reservations.vue';

import Alpine from 'alpinejs';
import Echo from 'laravel-echo';

window.Pusher = require('pusher-js');

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: process.env.MIX_PUSHER_APP_KEY || process.env.MIX_PUSHER_KEY,
    cluster: process.env.MIX_PUSHER_APP_CLUSTER || process.env.MIX_PUSHER_CLUSTER,
    encrypted: true,
    forceTLS: true,
});

const vueApp = createApp({
    data() {
        return {
            page: null,
            user: window.Auth ? window.Auth.user : null,
        };
    },
    mounted() {
        const appEl = document.getElementById('app');
        if (appEl) {
            this.page = appEl.dataset.page;
        }
    }
});

vueApp.component('dashboard', Dashboard);
vueApp.component('tickets-list', Tickets);
vueApp.component('assets-list', Assets);
vueApp.component('reservations-list', Reservations);

window.addEventListener('DOMContentLoaded', () => {
    const appEl = document.getElementById('app');
    if (appEl) {
        vueApp.mount('#app');
        console.log('Vue mounted on #app, page:', appEl.dataset.page);
    }
});

window.Alpine = Alpine;

if (window.Echo && window.Auth && window.Auth.user) {
    window.Echo.private(`App.Models.User.${window.Auth.user.id}`)
        .notification((notif) => {
            console.log('notification', notif);
            alert(notif.type + ': ' + (notif.data.message || JSON.stringify(notif.data)));
        });
}

Alpine.start();
