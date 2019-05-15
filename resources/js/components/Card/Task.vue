<template>
        <div class="task custom-control custom-checkbox d-flex" :class="'task-status-'+status">
            <div class="task-name">
                <input type="checkbox" class="custom-control-input" :id="task.id" @click="toggle" :checked="isChecked">
                <label class="custom-control-label" :for="task.id" v-text="task.name"></label>
            </div>
            <div class="task-actions">
                <a href="#" class="icon-blue" @click.prevent="edit"><i class="material-icons">edit</i></a>
                <a href="#" class="icon-red" @click.prevent="remove"><i class="material-icons">delete_forever</i></a>
            </div>

            <transition name="fade">
                <div v-if="isNew" class="new-item-bg"></div>
            </transition>
        </div>
</template>

<script>
    export default {
        props: ['task', 'cardId'],

        data() {
            return {
                status: this.task.status,
                isChecked: this.task.status === 'done',
                isNew: this.task.isNew ? this.task.isNew: false,
            }
        },

        watch: {
            status() {
                this.isChecked = (this.status === 'done');
            },
        },

        created() {
            setTimeout(() => {
                this.isNew = false;
            }, 3000);
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

            edit() {

            },

            remove() {
                axios.delete(this.route('api.tasks.destroy', {task: this.task.id, card: this.cardId}))
                    .then(({data}) => {
                        flash(data);
                        this.$emit('removed', data);
                    })
                    .catch(() => flash());
            }
        }
    }
</script>

<style lang="scss">
    @import "../../../sass/variables";

    .fade-enter-active, .fade-leave-active {
        transition: opacity 3s;
    }

    .fade-enter, .fade-leave-to{
        opacity: 0;
    }

    .new-item-bg{
        background-color: #1bed254d;
        position: absolute;
        width: 100%;
        height: 100%;
        left: 0;
    }

    .task-status-done{
        color: $muted;

        label{
            text-decoration: line-through;
        }
    }

    .task{
        position: relative;

        &:hover{
            .task-actions{
                display: block;
            }
        }
    }

    .task-name{
        width: calc(100% - 50px);

        label{
            display: block;
        }
    }

    .icon-red{
        color: $red !important;
    }

    .icon-blue{
        color: $blue !important;
    }

    .task-actions{
        position: absolute;
        right: 0;
        display: none;

        a{
            opacity: .8;
            width: 17px;
            display: inline-block;

            &:hover{
                opacity: 1;
            }

            i{
                font-size: 19px;
            }
        }
    }

</style>
