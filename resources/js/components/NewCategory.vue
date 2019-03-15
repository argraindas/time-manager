<template>

    <div class="form-row align-items-center">

        <div class="col-auto">
            <div class="input-group input-group-sm mb-3">
                <input type="text" class="form-control input" placeholder="New category" name="name" v-model="name">
            </div>
        </div>
        <div class="col-auto">
            <div class="input-group input-group-sm mb-3">
                <button type="submit" class="btn btn-sm btn-primary" @click="addCategory">Create</button>
            </div>
        </div>

    </div>

</template>

<script>

    import route from '../mixins/route';

    export default {

        mixins: [route],

        data() {
            return {
                name: ''
            }
        },

        methods: {
            addCategory() {
                axios.post(this.route('api.categories.store'), { name: this.name })
                    .catch(error => {
                        flash(error.response.data, 'danger');
                    })
                    .then(({data}) => {
                        this.name = '';

                        flash('Category was successfully added!');

                        this.$emit('added', data);
                    });
            }
        }
    }

</script>
