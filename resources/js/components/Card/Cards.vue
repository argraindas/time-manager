<template>

    <div>

        <new-card @added="add"></new-card>

        <div class="row">

            <div class="spinner-border text-primary" role="status" v-if="loading">
                <span class="sr-only">Loading...</span>
            </div>

            <div class="col-md-3" v-for="card in items" :key="card.id" v-if="! loading">
                <card :card="card" @deleted="remove"></card>
            </div>

            <div v-if="items.length === 0 && ! loading" class="col-md-12 d-flex justify-content-center">
                <div class="alert alert-info">
                    There are no cards created. Please create one!
                </div>
            </div>

        </div>
    </div>
</template>

<script>
    import Card from './Card.vue';
    import NewCard from './NewCard';

    export default {
        props: ['cards'],
        components: {Card, NewCard},

        data() {
            return {
                items: [],
            }
        },

        created() {
            this.setData(this.cards);
        },

        methods: {
            setData(data) {
                this.items = data.data;
            },

            add(card) {
                this.items.unshift(card);
            },

            remove(index) {
                this.items.splice(index, 1);
            }
        }
    };
</script>


