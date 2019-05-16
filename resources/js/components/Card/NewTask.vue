<template>

    <div>
        <form @submit.prevent="add" @keydown="form.errors.clear($event.target.name)">
            <div class="input-group input-group-sm">
                <input type="text" v-model="form.name" class="form-control" placeholder="Add task...">
            </div>
        </form>
        <div class="help is-danger small-text mt-2" v-if="form.errors.has('name')" v-text="form.errors.get('name')"></div>
    </div>

</template>

<script>

    export default {
        props: ['cardId'],

        data() {
            return {
                form: new Form({
                    name: '',
                })
            }
        },

        methods: {
            add() {
                this.form.post(this.route('api.tasks.store', {id: this.cardId}))
                    .then(data => {
                        this.$emit('added', data.item);
                        flash(data);
                    })
                    .catch(() => flash());
            }
        }
    }
</script>
