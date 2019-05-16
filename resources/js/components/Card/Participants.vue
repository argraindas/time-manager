<template>

    <div class="participants small-text">
        <span class="text-muted text-red">Participants:</span>
        <a href="#" class="add-participant" v-if="! selecting" @click.prevent="toggle"><i class="material-icons">person_add</i></a>

        <select ref="participants" v-if="selecting" class="custom-select custom-select-sm" v-model="newParticipant" @change="add" @focusout="selecting = !selecting">
            <option disabled value="">Please select...</option>
            <option v-for="user in availableUsers" v-text="user.name" :value="user.id"></option>
        </select>

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
                participants: this.items,
                newParticipant: null,
                selecting: false,
                availableUsers: [],
                fetched: false,
            };
        },

        methods: {

            toggle() {
                this.selecting = ! this.selecting;
                if (! this.fetched) {
                    this.fetch();
                    this.fetched = true;
                }
            },

            fetch() {
                axios.get(this.route('api.cardParticipants', {id: this.cardId}))
                    .then(({data}) => this.availableUsers = data.data);
            },

            add() {
                axios.post(this.route('api.cardParticipants.store', {id: this.cardId}), {user_id: this.newParticipant})
                    .then(({data}) => {
                        this.participants.push(data.user);
                        this.newParticipant = null;
                        this.selecting = false;
                        this.fetched = false;
                        flash(data);
                    })
                    .catch(() => flash());
            },

            remove(index) {
                this.participants.splice(index, 1);
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
