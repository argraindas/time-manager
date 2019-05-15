<template>
    <div class="task custom-control custom-checkbox d-flex" :class="'task-status-'+status">
        <div class="task-name">
            <input type="checkbox" class="custom-control-input" :id="task.id" @click="toggle" :checked="isChecked">
            <label class="custom-control-label" :for="task.id" v-text="task.name"></label>
        </div>
        <div class="task-delete">
            <a href="#" class="text-red" @click.prevent="remove"><i class="material-icons">delete_forever</i></a>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['task', 'cardId'],

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
            toggle() {
                this.isChecked ? this.updateStatus('new') : this.updateStatus('done');
            },

            updateStatus(status) {
                axios.patch(this.route('api.taskStatus.update', {id: this.task.id}), {status: status})
                    .then(({data}) => {
                        this.status = status;
                        flash(data);
                        this.$emit('updated', data);
                    })
                    .catch(() => flash());
            },

            remove() {
                axios.delete(this.route('api.tasks.destroy', {task: this.task.id, card: this.cardId}))
                    .then(({data}) => {
                        flash(data);
                        this.$emit('removed', data);
                    })
                    .catch(() => flash());
            },
        }
    }
</script>

<style lang="scss">
    @import "../../../sass/variables";

    .task-status-done{
        color: $muted;

        label{
            text-decoration: line-through;
        }
    }

    .task{
        position: relative;

        &:hover{
            .task-delete{
                display: block;
            }
        }
    }

    .task-name{
        width: calc(100% - 24px);

        label{
            display: block;
        }
    }

    .task-delete{
        position: absolute;
        right: 0;
        display: none;

        a{
            color: $red !important;
            opacity: .8;

            &:hover{
                opacity: 1;
            }
        }
    }

</style>
