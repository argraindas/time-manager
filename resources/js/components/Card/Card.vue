<template>
    <div class="d-flex align-items-center">
        <div class="color mr-2"></div>

        <h6 v-if="! editing" v-text="form.name" class="flex-fill mb-0"></h6>
        <div class="input-group input-group-sm">
            <input v-if="editing" v-model="form.name" class="form-control flex-fill mr-2">
            <div class="help is-danger flex-fill mr-2" v-if="editing && form.errors.has('name')" v-text="form.errors.get('name')"></div>
        </div>

        <button type="submit" class="btn btn-sm btn-primary mr-2" v-if="! editing" @click="editing = true">Edit</button>
        <button type="submit" class="btn btn-sm btn-danger" v-if="! editing" @click="destroy">Delete</button>
        <button type="submit" class="btn btn-sm btn-success mr-2" v-if="editing" @click="update">Save</button>
        <button type="submit" class="btn btn-sm btn-secondary" v-if="editing" @click="cancel">Cancel</button>
    </div>
</template>

<script>

    export default {
        props: ['category'],

        data() {
            return {
                form: new Form({
                    name: this.category.name,
                }, false),
                id: this.category.id,
                editing: false
            }
        },

        methods: {
            update() {
                this.form.patch(this.route('api.categories.update', {id: this.id}))
                    .then(data => {
                        this.$emit('updated', data);
                        this.editing = false;
                        flash(data);
                    })
                    .catch(() => flash());
            },

            destroy() {
                this.form.delete(this.route('api.categories.destroy', {id: this.id}))
                    .then(data => {
                        this.$emit('deleted', data);
                        flash(data);
                    })
                    .catch(() => flash());
            },

            cancel() {
                this.editing = false;
                this.form.name = this.category.name;
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
