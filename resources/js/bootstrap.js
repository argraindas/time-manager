import Vue from 'vue';
import axios from 'axios';
import Form from './core/Form';
import Route from './mixins/route';
// import Popper from 'popper.js';
// import $ from 'jquery';
// import lodash from 'lodash';
import 'bootstrap';

window.Vue = Vue;
window.axios = axios;
window.Form = Form;
window.Route = Route;
window.events = new Vue();
// window.Popper = Popper;
// window.$ = window.jQuery = $;
// window._ = lodash;

let authorizations = require('./authorizations');

Vue.prototype.authorize = function(...params) {
    if (!window.App.signedIn) return false;

    if (typeof params[0] === 'string') {
        return authorizations[params[0]](params[1], params[2]);
    }

    return params[0](window.App.user);
};

Vue.prototype.signedIn = window.App.signedIn;

let token = document.head.querySelector('meta[name="csrf-token"]');
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}

window.flash = function (data = null) {
    let message = data && data.message ? data.message : null;
    let status = data && data.status ? data.status : null;

    window.events.$emit('flash', { message, status });
};

// For Broadcasting with Pusher
import Echo from 'laravel-echo'

window.Pusher = require('pusher-js');

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: process.env.MIX_PUSHER_APP_KEY,
    cluster: process.env.MIX_PUSHER_APP_CLUSTER,
    encrypted: true
});
