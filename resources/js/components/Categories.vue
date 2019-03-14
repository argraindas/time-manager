<template>
    <div>

        <new-category @created="add"></new-category>

        <div class="spinner-border text-primary" role="status" v-if="loading">
            <span class="sr-only">Loading...</span>
        </div>

        <div v-for="(category, index) in items" :key="category.id">
            <category :category="category" @deleted="remove(index)" v-text="category.name"></category>
        </div>

        <paginator :dataSet="dataSet" @changed="fetch"></paginator>

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
                this.items.push(item);

                this.$emit('added');
            },

            remove(index) {
                this.items.splice(index, 1);

                this.$emit('removed');
            }
        }
    };
</script>


