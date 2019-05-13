<template>
    <div>

        <div class="spinner-border text-primary" role="status" v-if="loading">
            <span class="sr-only">Loading...</span>
        </div>

        <ul class="list-group">
            <li class="list-group-item" v-for="card in items" :key="card.id" v-if="! loading">
                <card :card="card" @deleted="fetch"></card>
            </li>
        </ul>

        <div v-if="items.length === 0 && ! loading">
            There are no cards created. Please create one!
        </div>

    </div>
</template>

<script>
    import Card from './Card.vue';

    export default {
        components: {Card},

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
                axios.get(this.route('api.categories', {page: page})).then(({data}) => {
                    this.dataSet = data;
                    this.items = data.data;
                    this.loading = false;
                });
            }
        }
    };
</script>


