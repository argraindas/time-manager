<template>
    <div class="row">

        <div class="spinner-border text-primary" role="status" v-if="loading">
            <span class="sr-only">Loading...</span>
        </div>

        <div class="col-md-3" v-for="card in items" :key="card.id" v-if="! loading">
            <card :card="card" @deleted="fetch"></card>
        </div>

        <div v-if="items.length === 0 && ! loading">
            There are no cards created. Please create one!
        </div>

    </div>
</template>

<script>
    import Card from './Card.vue';

    export default {
        props: ['cards'],
        components: {Card},

        data() {
            return {
                dataSet: [],
                items: [],
                loading: true
            }
        },

        created() {
            this.dataSet = this.cards;
            this.items = this.cards.data;
            this.loading = false;
        },

        methods: {
            fetch() {
                this.loading = true;
                axios.get(this.route('api.cards')).then(({data}) => {
                    this.dataSet = data;
                    this.items = data.data;
                    this.loading = false;
                });
            }
        }
    };
</script>


