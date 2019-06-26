<template>

    <div class="participants small-text">
        <span class="text-muted">Participants:</span>
        <a href="#" class="add-participant" v-if="isCreator && ! selecting" @click.prevent="toggle"><i class="material-icons">person_add</i></a>

        <select v-if="isCreator && selecting" class="form-control form-control-sm" v-model="newParticipant" @change="add" @focusout="selecting = false">
            <option disabled value="" v-text="selectText"></option>
            <option v-for="user in availableUsers" v-text="user.name" :value="user.id"></option>
        </select>

        <div v-for="(user, index) in this.participants" :key="user.id" class="participant-name">
            <a class="text-blue" href="#" v-text="user.name"></a>
            <div v-if="isCreator" class="participant-actions">
                <a href="#" class="icon-red" @click.prevent="remove(index, user)"><i class="material-icons">delete_forever</i></a>
            </div>
        </div>
    </div>

</template>

<script>
    export default {
        props: ['items', 'cardUuid', 'isCreator'],

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
                axios.get(this.route('api.cardParticipants', {id: this.cardUuid}))
                    .then(({data}) => {
                        this.availableUsers = data.data;
                        this.selectText = this.availableUsers.length > 0 ? 'Please select...' : 'No users found.';
                    });
            },

            add() {
                axios.post(this.route('api.cardParticipants.store', {id: this.cardUuid}), {user_id: this.newParticipant})
                    .then(({data}) => {
                        this.participants.push(data.user);
                        flash(data);
                    })
                    .catch(() => flash());

                this.newParticipant = '';
                this.selecting = false;
                this.fetched = false;
            },

            remove(index, user) {
                axios.delete(this.route('api.cardParticipants.destroy', {card: this.cardUuid, user: user.id}))
                    .then(({data}) => flash(data))
                    .catch(() => flash());

                this.participants.splice(index, 1);
                this.fetched = false;
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

    .participant-name{
        position: relative;

        &:hover{
            .participant-actions{
                display: block;
            }
        }
    }

    .participant-actions{
        position: absolute;
        right: 0;
        top: 0;
        display: none;

        a{
            opacity: .8;
            width: 17px;
            display: inline-block;

            &:hover{
                opacity: 1;
            }

            i{
                font-size: 19px;
            }
        }
    }

</style>
