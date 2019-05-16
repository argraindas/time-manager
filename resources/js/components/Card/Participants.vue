<template>

    <div class="participants small-text">
        <span class="text-muted text-red">Participants:</span><a href="#" class="add-participant"><i class="material-icons">person_add</i></a>
        <div v-for="user in this.participants">
            <a class="text-blue" href="#" v-text="user.name"></a>
        </div>
    </div>

</template>

<script>
    export default {
        props: ['items', 'cardId'],

        data() {
            return {
                participants: this.items
            };
        },

        methods: {
            add(item) {
                this.tasks.unshift(item);
            },

            remove(index) {
                this.tasks.splice(index, 1);
            }
        }
    }
</script>

<style lang="scss">

    .add-participant{
        margin-left: 10px;

        i{
            font-size: 20px;
        }
    }

</style>
