<template>
    <form @submit.prevent="addRecord" @keydown="form.errors.clear($event.target.name)">
        <div class="form-row align-items-center mb-3">

            <div class="col-auto">
                <div class="input-group input-group-sm">
                    <input type="text" class="form-control" placeholder="Description" name="description" v-model="form.description">
                </div>
            </div>
            <div class="col-auto">
                <div class="input-group input-group-sm">
                    <select name="category_id" class="form-control" v-model="form.category_id" @change="form.errors.clear($event.target.name)">
                        <option disabled value="">-- Please Select --</option>
                        <option v-for="category in categories" v-text="category.name" :value="category.id"></option>
                    </select>
                </div>
            </div>
            <div class="col-auto">
                <div class="input-group input-group-sm">
                   <datetime type="datetime"
                             format="yyyy-MM-dd HH:mm"
                             :minute-step="5"
                             value-zone="local"
                             auto
                             class="form-control time-picker"
                             v-model="isoTimeStart"
                             @close="form.errors.clear('time_start')"
                   ></datetime>
                </div>
            </div>
            <div class="col-auto">
                <div class="input-group input-group-sm">
                    <datetime type="datetime"
                              format="yyyy-MM-dd HH:mm"
                              :minute-step="5"
                              value-zone="local"
                              auto
                              class="form-control time-picker"
                              v-model="isoTimeEnd"
                              @close="form.errors.clear('time_end')"
                    ></datetime>
                </div>
            </div>
            <div class="col-auto">
                <div class="input-group input-group-sm">
                    <button type="submit" class="btn btn-sm btn-primary" :disabled="form.errors.any()">Create</button>
                </div>
            </div>

            <div class="col-auto mt-1"  v-if="form.errors.any()">
                <div class="help is-danger" v-if="form.errors.has('description')" v-text="form.errors.get('description')"></div>
                <div class="help is-danger" v-if="form.errors.has('category_id')" v-text="form.errors.get('category_id')"></div>
                <div class="help is-danger" v-if="form.errors.has('time_start')" v-text="form.errors.get('time_start')"></div>
                <div class="help is-danger" v-if="form.errors.has('time_end')" v-text="form.errors.get('time_end')"></div>
            </div>
        </div>
    </form>

</template>

<script>

    import DateTimePicker from '../../mixins/datetime-picker';

    export default {
        props: ['categories'],
        mixins: [DateTimePicker],

        data() {
            return {
                isoTimeStart: null,
                isoTimeEnd: null,

                form: new Form({
                    description: '',
                    category_id: '',
                    time_start: null,
                    time_end: null
                }),
            }
        },

        watch: {
            isoTimeStart() {
                this.form.time_start = this.toSQL(this.isoTimeStart);
            },

            isoTimeEnd() {
                this.form.time_end = this.toSQL(this.isoTimeEnd);
            }
        },

        methods: {
            addRecord() {
                this.form.post(this.route('api.records.store'))
                    .then(data => {
                        this.$emit('added', data);
                        flash(data);

                        this.isoTimeStart = null;
                        this.isoTimeEnd = null;
                    })
                    .catch(() => flash());
            }
        }
    }

</script>

<style>
    .time-picker input{
        border: 0 none;
    }
</style>
