<template>
    <div>
        <ul v-for="category in items">
            <li v-text="category.name"></li>
        </ul>

        <paginator :dataSet="dataSet" @changed="fetch"></paginator>

    </div>
</template>

<script>
    export default {
        props: [],

        data() {
            return {
                dataSet: [],
                items: []
            }
        },

        created() {
            this.fetch();
        },

        methods: {
            fetch(page) {
                axios.get(this.url(page)).then(({data}) => {
                    this.dataSet = data;
                    this.items = data.data;
                });
            },

            url(page) {
                if (! page) {
                    let query = location.search.match(/page=(\d+)/);

                    page = query ? query[1] : 1;
                }

                return `/api/categories?page=${page}`;
            }
        }
    };
</script>
