<template>

    <div class="form-row align-items-center mb-3">

        <div class="col-auto">
            <div class="input-group input-group-sm">
                <input type="text" class="form-control input" placeholder="New record" name="description" v-model="form.description" @keydown="form.errors.clear($event.target.description)">
            </div>
        </div>
        <div class="col-auto">
            <div class="input-group input-group-sm">
                <button type="submit" class="btn btn-sm btn-primary" @click="addRecord" :disabled="form.errors.any()">Create</button>
            </div>
        </div>
        <div class="col-auto mt-1">
            <div class="help is-danger" v-if="form.errors.has('description')" v-text="form.errors.get('description')"></div>
        </div>
    </div>

</template>

<script>

    export default {

        data() {
            return {
                form: new Form({
                    description: ''
                })
            }
        },

        methods: {
            addRecord() {
                this.form.post(this.route('api.records.store'))
                    .then(data => {
                        this.$emit('added', data);
                        flash(data);
                    })
                    .catch(() => flash());
            }
        }
    }

</script>
