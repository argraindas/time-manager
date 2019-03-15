<template>

    <div class="form-row align-items-center">

        <div class="col-auto">
            <div class="input-group input-group-sm mb-3">
                <input type="text" class="form-control input" placeholder="New category" name="name" v-model="form.name" @keydown="form.errors.clear($event.target.name)">
                <span class="help is-danger" v-if="form.errors.has('name')" v-text="form.errors.get('name')"></span>
            </div>
        </div>
        <div class="col-auto">
            <div class="input-group input-group-sm mb-3">
                <button type="submit" class="btn btn-sm btn-primary" @click="addCategory" :disabled="form.errors.any()">Create</button>
            </div>
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
                        flash('Category was successfully added!');
                        this.$emit('added', data);
                    })
                    .catch(() => {
                        flash('Please check your form!', 'danger')
                    });
            }
        }
    }

</script>
