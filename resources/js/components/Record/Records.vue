<template>
    <div>

        <new-record @added="fetch"></new-record>

        <div class="spinner-border text-primary" role="status" v-if="loading">
            <span class="sr-only">Loading...</span>
        </div>

        <ul class="list-group">
            <li class="list-group-item" v-for="record in items" :key="record.id" v-if="! loading">
                <record :record="record" @deleted="fetch"></record>
            </li>
        </ul>

        <div v-if="items.length === 0 && ! loading">No records found.</div>

        <paginator class="mt-3" :dataSet="dataSet" @changed="fetch"></paginator>

    </div>
</template>

<script>
    import Record from './Record.vue';
    import NewRecord from './NewRecord.vue';

    export default {
        components: {Record, NewRecord},

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

                return this.route('api.records', {page: page});
            }
        }
    };
</script>
