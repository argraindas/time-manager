<template>

    <div class="participants small-text">
        <span class="text-muted text-red">Participants:</span>
        <a href="#" class="add-participant" v-if="isCreator && ! selecting" @click.prevent="toggle"><i class="material-icons">person_add</i></a>

        <select v-if="isCreator && selecting" class="form-control form-control-sm" v-model="newParticipant" @change="add" @focusout="selecting = !selecting">
            <option disabled value="" v-text="selectText"></option>
            <option v-for="user in availableUsers" v-text="user.name" :value="user.id"></option>
        </select>

        <div v-for="user in this.participants">
            <a class="text-blue" href="#" v-text="user.name"></a>
        </div>
    </div>

</template>

<script>
    export default {
        props: ['items', 'cardId', 'isCreator'],

        data() {
            return {
                participants: this.items,
                newParticipant: '',
                selecting: false,
                availableUsers: [],
                fetched: false,
                selectText: 'Please select...',
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
                    .then(({data}) => {
                        this.availableUsers = data.data;
                        this.selectText = this.availableUsers.length > 0 ? 'Please select...' : 'No users found.';
                    });
            },

            add() {
                axios.post(this.route('api.cardParticipants.store', {id: this.cardId}), {user_id: this.newParticipant})
                    .then(({data}) => {
                        this.participants.push(data.user);
                        this.newParticipant = '';
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
