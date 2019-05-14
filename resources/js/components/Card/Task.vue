<template>
    <div class="custom-control custom-checkbox mr-sm-2" :class="isChecked ? 'task-status-done' : ''">
        <input type="checkbox" class="custom-control-input" :id="task.id" @click="changeStatus" :checked="isChecked">
        <label class="custom-control-label" :for="task.id" v-text="task.name"></label>
    </div>
</template>

<script>
    export default {
        props: ['task'],

        data() {
            return {
                status: this.task.status,
                isChecked: this.task.status === 'done',
            }
        },

        watch: {
            status() {
                this.isChecked = (this.status === 'done');
            }
        },

        methods: {
            update(status) {
                axios.patch(this.route('api.taskStatus.update', {id: this.task.id}), {status: status})
                    .then(({data}) => {
                        this.$emit('updated', data);
                        this.status = status;
                        flash(data);
                    })
                    .catch(() => flash());
            },

            changeStatus() {
                this.update(this.isChecked ? 'new' : 'done');
            },
        }
    }
</script>

<style lang="scss">
    .task-status-done{
        color: #b2b7d2;

        label{
            text-decoration: line-through;
        }
    }
</style>
