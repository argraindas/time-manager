<template>
    <div>
        <ul v-for="category in items">
            <li v-text="category.name"></li>
        </ul>

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
            fetch() {
                axios.get('/api/categories').then(({data}) => {
                    this.dataSet = data;
                    this.items = data.data;
                });
            },
        }
    };
</script>
