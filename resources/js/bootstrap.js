import Vue from 'vue';
import axios from 'axios';
import Form from './core/Form';
import Route from './mixins/route';
// import Popper from 'popper.js';
// import $ from 'jquery';
// import lodash from 'lodash';
// import feather from 'feather-icons';
import 'bootstrap';

window.Vue = Vue;
window.axios = axios;
window.Form = Form;
window.Route = Route;
window.events = new Vue();
// window.Popper = Popper;
// window.$ = window.jQuery = $;
// window._ = lodash;
// feather.replace();

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
