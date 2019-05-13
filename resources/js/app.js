import './bootstrap';

Vue.mixin(Route);

Vue.component('flash', require('./components/Flash.vue').default);
Vue.component('paginator', require('./components/Paginator.vue').default);
Vue.component('categories', require('./components/Category/Categories.vue').default);
Vue.component('records', require('./components/Record/Records.vue').default);
Vue.component('cards', require('./components/Card/Cards.vue').default);

const app = new Vue({
    el: '#app'
});
