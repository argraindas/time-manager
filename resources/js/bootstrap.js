import Vue from 'vue';
import axios from 'axios';
import Form from './core/Form';
// import Popper from 'popper.js';
// import $ from 'jquery';
// import lodash from 'lodash';
// import feather from 'feather-icons';
import 'bootstrap';

window.Vue = Vue;
window.axios = axios;
window.Form = Form;
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
