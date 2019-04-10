<template>

    <div class="form-row align-items-center mb-3">

        <div class="col-auto">
            <div class="input-group input-group-sm">
                <input type="text" class="form-control" placeholder="New category" name="name" v-model="form.name" @keydown="form.errors.clear($event.target.name)">
            </div>
        </div>
        <div class="col-auto">
            <div class="input-group input-group-sm">
                <button type="submit" class="btn btn-sm btn-primary" @click="addCategory" :disabled="form.errors.any()">Create</button>
            </div>
        </div>
        <div class="col-auto mt-1">
            <div class="help is-danger" v-if="form.errors.has('name')" v-text="form.errors.get('name')"></div>
        </div>
    </div>

</template>

<script>

    export default {

        data() {
            return {
                form: new Form({
                    name: ''
                })
            }
        },

        methods: {
            addCategory() {
                this.form.post(this.route('api.categories.store'))
                    .then(data => {
                        this.$emit('added', data);
                        flash(data);
                    })
                    .catch(() => flash());
            }
        }
    }

</script>
