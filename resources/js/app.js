import './bootstrap';

Vue.mixin(Route);

Vue.component('flash', require('./components/Flash.vue').default);
Vue.component('paginator', require('./components/Paginator.vue').default);
Vue.component('sidebar-left', require('./components/SidebarLeft/SidebarLeft.vue').default);
Vue.component('categories', require('./components/Category/Categories.vue').default);

const app = new Vue({
    el: '#app'
});
