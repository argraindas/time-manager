import './bootstrap';

Vue.component('flash', require('./components/Flash.vue').default);
// Vue.component('paginator', require('./components/Paginator.vue').default);
// Vue.component('user-notifications', require('./components/UserNotifications.vue').default);

Vue.component('categories', require('./components/Categories.vue').default);

const app = new Vue({
    el: '#app'
});
