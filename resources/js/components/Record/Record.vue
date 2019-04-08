<template>
    <div class="d-flex align-items-center">
        <div class="color mr-2"></div>

        <h6 v-if="! editing" v-text="form.description" class="flex-fill mb-0"></h6>
        <div class="input-group input-group-sm">
            <input v-if="editing" v-model="form.description" class="form-control flex-fill mr-2">
            <div class="help is-danger flex-fill mr-2" v-if="editing && form.errors.has('description')" v-text="form.errors.get('description')"></div>
        </div>

        <button type="submit" class="btn btn-sm btn-primary mr-2" v-if="! editing" @click="editing = true">Edit</button>
        <button type="submit" class="btn btn-sm btn-danger" v-if="! editing" @click="destroy">Delete</button>
        <button type="submit" class="btn btn-sm btn-success mr-2" v-if="editing" @click="update">Save</button>
        <button type="submit" class="btn btn-sm btn-secondary" v-if="editing" @click="cancel">Cancel</button>
    </div>
</template>

<script>

    export default {
        props: ['record'],

        data() {
            return {
                form: new Form({
                    description: this.record.description,
                }, false),
                id: this.record.id,
                editing: false
            }
        },

        methods: {
            update() {
                this.form.patch(this.route('api.records.update', {id: this.id}))
                    .then(data => {
                        this.$emit('updated', data);
                        this.editing = false;
                        flash(data);
                    })
                    .catch(() => flash());
            },

            destroy() {
                this.form.delete(this.route('api.records.destroy', {id: this.id}))
                    .then(data => {
                        this.$emit('deleted', data);
                        flash(data);
                    })
                    .catch(() => flash());
            },

            cancel() {
                this.editing = false;
                this.form.description = this.record.description;
            }
        }
    }
</script>

<style scoped>
    .color{
        width: 20px;
        height: 20px;
        border-radius: 4px;
        margin-left: -10px;

        background-color: yellow;
    }
</style>
