<template>
    <div class="row">

        <div class="spinner-border text-primary" role="status" v-if="loading">
            <span class="sr-only">Loading...</span>
        </div>

        <div class="col-md-3" v-for="card in items" :key="card.id" v-if="! loading">
            <card :card="card" @deleted="fetch"></card>
        </div>

        <div v-if="items.length === 0 && ! loading" class="col-md-12 d-flex justify-content-center">
            <div class="alert alert-info">
                There are no cards created. Please create one!
            </div>
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
                loading: false
            }
        },

        created() {
            this.setData(this.cards);
        },

        methods: {
            fetch() {
                this.loading = true;
                axios.get(this.route('api.cards')).then(({data}) => this.setData(data));
            },

            setData(data) {
                this.dataSet = data;
                this.items = data.data;
                this.loading = false;
            }
        }
    };
</script>


