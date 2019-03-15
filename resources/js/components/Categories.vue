<template>
    <div>

        <new-category @added="add"></new-category>

        <div class="spinner-border text-primary" role="status" v-if="loading">
            <span class="sr-only">Loading...</span>
        </div>

        <ul class="list-group">
            <li class="list-group-item" v-for="(category, index) in items" :key="category.id" v-if="! loading">
                <category :category="category" @deleted="remove(index)"></category>
            </li>
        </ul>

        <paginator class="mt-3" :dataSet="dataSet" @changed="fetch"></paginator>

    </div>
</template>

<script>
    import Category from './Category.vue';
    import NewCategory from './NewCategory.vue';
    import route from '../mixins/route';

    export default {
        components: {Category, NewCategory},

        mixins: [route],

        data() {
            return {
                dataSet: [],
                items: [],
                loading: true
            }
        },

        created() {
            this.fetch();
        },

        methods: {
            fetch(page) {
                this.loading = true;
                axios.get(this.url(page)).then(({data}) => {
                    this.dataSet = data;
                    this.items = data.data;
                    this.loading = false;
                });
            },

            url(page) {
                if (! page) {
                    let query = location.search.match(/page=(\d+)/);
                    page = query ? query[1] : 1;
                }

                return this.route('api.categories', {page: page});
            },

            add(item) {
                this.items.unshift(item);
            },

            remove(index) {
                this.items.splice(index, 1);
            }
        }
    };
</script>


