<template>
    <div class="card mb-4 shadow-sm">
        <div class="card-header">
            <div class="d-flex align-items-center">
                <div class="card-name flex-fill">
                    <h4 v-if="! editingName" v-text="form.name"></h4>

                    <form class="mb-2" v-if="editingName" @submit.prevent="update" @keydown="form.errors.clear('name')">
                        <div class="input-group input-group-sm">
                            <input type="text" v-model="form.name" class="form-control">
                        </div>
                        <div class="help is-danger small-text mt-2" v-if="form.errors.has('name')" v-text="form.errors.get('name')"></div>
                    </form>

                    <div class="card-actions">
                        <a href="#" class="icon-blue" @click.prevent="toggleName" v-if="! editingName"><i class="material-icons">edit</i></a>
                        <a href="#" class="icon-blue" @click.prevent="cancelName" v-if="editingName"><i class="material-icons">highlight_off</i></a>
                    </div>
                </div>

                <small class="text-muted text-right text-nowrap ml-2">{{ card.created_at | ago }}</small>

                <a href="#" class="remove-icon icon-red" @click.prevent="remove"><i class="material-icons">close</i></a>
            </div>
            <div class="small-text">
                <span class="text-muted">Created by: </span>
                <a class="text-blue" href="#" v-text="card.creator.name"></a>
            </div>
        </div>
        <div class="card-body">
            <div class="card-description">
                <div v-if="! editingDescription" class="card-text text-muted" v-text="form.description"></div>

                <form class="mb-2" v-if="editingDescription" @submit.prevent="update" @keydown="form.errors.clear('description')">
                    <div class="input-group input-group-sm">
                        <input type="text" v-model="form.description" class="form-control">
                    </div>
                    <div class="help is-danger small-text mt-2" v-if="form.errors.has('description')" v-text="form.errors.get('description')"></div>
                </form>

                <div class="card-actions">
                    <a href="#" class="icon-blue" @click.prevent="toggleDescription" v-if="! editingDescription"><i class="material-icons">edit</i></a>
                    <a href="#" class="icon-blue" @click.prevent="cancelDescription" v-if="editingDescription" style="margin-top: 5px"><i class="material-icons">highlight_off</i></a>
                </div>
            </div>

            <tasks :items="card.tasks" :cardId="card.id"></tasks>
        </div>
        <div class="card-footer">
            <participants :items="card.participants" :cardId="card.id" :isCreator="authorize('isCreator', card.creator)"></participants>
        </div>
    </div>
</template>

<script>
    import Tasks from './Tasks.vue';
    import Participants from './Participants.vue';
    import moment from 'moment';

    export default {
        props: ['card'],
        components: {Tasks, Participants},

        data() {
            return {
                editingName: false,
                editingDescription: false,
                form: new Form({
                    name: this.card.name,
                    description: this.card.description,
                }, false),
            }
        },

        filters: {
            ago(date) {
                return moment(date).fromNow();
            }
        },

        methods: {
            toggleName() {
                this.editingName = ! this.editingName;
            },

            toggleDescription() {
                this.editingDescription = ! this.editingDescription;
            },

            cancelName() {
                this.toggleName();
                this.form.restore();
            },

            cancelDescription() {
                this.toggleDescription();
                this.form.restore();
            },

            update() {
                this.form.patch(this.route('api.cards.update', {id: this.card.id}))
                    .then((data) => {
                        if (this.editingName) {
                            this.cancelName();
                        }

                        if (this.editingDescription) {
                            this.cancelDescription();
                        }
                        flash(data);
                    })
                    .catch(() => flash());
            },

            remove() {
                axios.delete(this.route('api.cards.destroy', {id: this.card.id}))
                    .then(({data}) => {
                        this.$emit('removed', data);
                        flash(data);
                    })
                    .catch(() => flash());
            }
        }
    }
</script>

<style lang="scss">
    .card-body{
        padding-top: .75rem;
        padding-bottom: .75rem;
    }

    .card-text{
        font-style: italic;
        font-size: .75rem;
        line-height: 1.3;
        margin-bottom: 0.75rem;
        min-height: 15px;
    }

    .card-name, .card-description{
        position: relative;

        &:hover{
            .card-actions{
                display: block;
            }
        }
    }

    .card-actions{
        position: absolute;
        right: 0;
        top: 0;
        margin-right: 5px;
        margin-top: 5px;
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

    .card-description .card-actions{
        margin-top: 0;
    }

    .remove-icon{
        position: absolute;
        top: 0;
        right: 0;
        line-height: 13px;
        margin-right: 3px;
        margin-top: 3px;

        i{
            font-size: 13px;
        }
    }

</style>
