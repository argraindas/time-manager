<template>

    <div class="row">
        <div class="col-md-12">
            <div v-if="!visible" class="mb-3">
                <a class="card-action-icon" href="#" @click.prevent="visible = ! visible"><i class="material-icons">add_circle</i></a>
            </div>

            <form v-if="visible" class="mb-4" @submit.prevent="add" @keydown="form.errors.clear($event.target.name)">
                <div class="form-row align-items-center">
                    <div class="col-auto">
                        <div class="input-group input-group-sm">
                            <input type="text" class="form-control" placeholder="Name" v-model="form.name">
                        </div>
                    </div>

                    <div class="col-auto">
                        <div class="input-group input-group-sm">
                            <input type="text" class="form-control" placeholder="Description" v-model="form.description">
                        </div>
                    </div>

                    <div class="col-auto">
                        <div class="input-group input-group-sm">
                            <button type="submit" class="btn btn-sm btn-primary mr-2" :disabled="form.errors.any()">Create</button>
                            <button type="submit" class="btn btn-sm btn-danger" @click.prevent="visible = ! visible">Cancel</button>
                        </div>
                    </div>
                </div>

                <div class="mt-1"  v-if="form.errors.any()">
                    <div class="help is-danger" v-if="form.errors.has('name')" v-text="form.errors.get('name')"></div>
                    <div class="help is-danger" v-if="form.errors.has('description')" v-text="form.errors.get('description')"></div>
                </div>
            </form>
        </div>
    </div>

</template>

<script>
    export default {
        data() {
            return {
                visible: false,
                form: new Form({
                    name: '',
                    description: '',
                }),
            }
        },

        methods: {
            add() {
                this.form.post(this.route('api.cards.store'))
                    .then(data => {
                        this.$emit('added', data.item);
                        flash(data);
                    })
                    .catch(() => flash());
            }
        }
    };
</script>

<style lang="scss" scoped>
    .card-action-icon{
        i{font-size: 40px}
    }
</style>
