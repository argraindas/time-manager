<template>
    <div>

        <new-category @added="fetch"></new-category>

        <div class="spinner-border text-primary" role="status" v-if="loading">
            <span class="sr-only">Loading...</span>
        </div>

        <ul class="list-group">
            <li class="list-group-item" v-for="category in items" :key="category.id" v-if="! loading">
                <category :category="category" @deleted="fetch"></category>
            </li>
        </ul>

        <div v-if="items.length === 0 && ! loading">There are no categories created. Please create one!</div>

        <paginator class="mt-3" :dataSet="dataSet" @changed="fetch"></paginator>

    </div>
</template>

<script>
    import Category from './Category.vue';
    import NewCategory from './NewCategory.vue';

    export default {
        components: {Category, NewCategory},

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
                    this.dataSet = {
                        current_page: data.meta.current_page,
                        prev_page_url: data.links.prev,
                        next_page_url: data.links.next,
                    };
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
            }
        }
    };
</script>


