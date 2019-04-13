<template>
    <div>

        <div v-if="! editing">
            <h5 v-text="form.description" class="mb-2"></h5>
            <h6 v-text="categoryName" class="mb-2"></h6>

            <div class="d-flex text-green mb-2">
                <div v-text="form.time_start" class="mr-2"></div>
                <i v-if="form.time_end" class="material-icons text-red">arrow_right_alt</i>
                <div v-text="form.time_end" class="ml-2"></div>
            </div>

            <button type="submit" class="btn btn-sm btn-primary" @click="editing = true">Edit</button>
        </div>

        <div v-if="editing">
            <div class="input-group input-group-sm mb-2">
                <input v-model="form.description" class="form-control">
                <div class="help is-danger" v-if="form.errors.has('description')" v-text="form.errors.get('description')"></div>
            </div>

            <div class="input-group input-group-sm mb-2">
                <select name="category_id" class="form-control" v-model="form.category_id" required>
                    <option disabled value="">-- Please Select --</option>
                    <option v-for="category in categories" v-text="category.name" :value="category.id"></option>
                </select>
                <div class="help is-danger" v-if="form.errors.has('category_id')" v-text="form.errors.get('category_id')"></div>
            </div>

            <div class="input-group input-group-sm mb-2">
                <datetime type="datetime"
                          format="yyyy-MM-dd HH:mm"
                          :minute-step="5"
                          value-zone="local"
                          auto
                          class="form-control time-picker"
                          v-model="isoTimeStart"
                          @keydown="form.errors.clear('time_start')"
                ></datetime>
                <div class="help is-danger" v-if="form.errors.has('time_start')" v-text="form.errors.get('time_start')"></div>
            </div>

            <div class="input-group input-group-sm mb-2">
                <datetime type="datetime"
                          format="yyyy-MM-dd HH:mm"
                          :minute-step="5"
                          value-zone="local"
                          auto
                          class="form-control time-picker"
                          v-model="isoTimeEnd"
                          @keydown="form.errors.clear('time_end')"
                ></datetime>
                <div class="help is-danger" v-if="form.errors.has('time_end')" v-text="form.errors.get('time_end')"></div>
            </div>

            <div class="input-group input-group-sm">
                <div class="flex-fill">
                    <button type="submit" class="btn btn-sm btn-success mr-2" @click="update">Update</button>
                    <button type="submit" class="btn btn-sm btn-secondary mr-2" @click="cancel">Cancel</button>
                </div>

                <button type="submit" class="btn btn-sm btn-danger " @click="destroy">Delete</button>
            </div>

        </div>

    </div>
</template>

<script>
    import DateTimePicker from '../../mixins/datetime-picker';

    export default {
        props: ['record', 'categories'],

        mixins: [DateTimePicker],

        data() {
            return {
                id: this.record.id,
                editing: false,

                isoTimeStart: this.toISO(this.record.time_start),
                isoTimeEnd: this.toISO(this.record.time_end),

                form: new Form({
                    description: this.record.description,
                    category_id: this.record.category_id,
                    time_start: this.record.time_start,
                    time_end: this.record.time_end
                }, false)
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

        computed: {
            categoryName() {
                let category = this.categories.find(category => category.id === this.form.category_id);

                return category ? category.name : '';
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
                this.form.restore();

                this.isoTimeStart = this.toISO(this.record.time_start);
                this.isoTimeEnd = this.toISO(this.record.time_end);
            }
        }
    }
</script>
