<template>

    <div class="task custom-control custom-checkbox d-flex" :class="'task-status-'+status">
        <div class="task-name">
            <input type="checkbox" class="custom-control-input" :id="task.id" @click="toggleStatus" :checked="isChecked">
            <label v-if="! editing" class="custom-control-label" :for="task.id" v-text="form.name"></label>
            <div v-if="editing">
                <form @submit.prevent="update" @keydown="form.errors.clear($event.target.name)">
                    <div class="input-group input-group-sm">
                        <input type="text" v-model="form.name" class="form-control">
                    </div>
                </form>
                <div class="help is-danger small-text mt-2" v-if="form.errors.has('name')" v-text="form.errors.get('name')"></div>
            </div>
        </div>
        <div class="task-actions">
            <a href="#" class="icon-blue" @click.prevent="toggleEdit" v-if="! editing"><i class="material-icons">edit</i></a>
            <a href="#" class="icon-blue" @click.prevent="cancelEdit" v-if="editing"><i class="material-icons">highlight_off</i></a>
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
                form: new Form({
                    name: this.task.name,
                }, false),
                status: this.task.status,
                isChecked: this.task.status === 'done',
                isNew: this.task.isNew ? this.task.isNew: false,
                editing: false,
            }
        },

        watch: {
            status() {
                this.isChecked = (this.status === 'done');
            },
        },

        created() {
            if (this.isNew) {
                setTimeout(() => {
                    this.isNew = false;
                }, 2000);
            }
        },

        methods: {
            toggleStatus() {
                this.isChecked ? this.updateStatus('new') : this.updateStatus('done');
            },

            toggleEdit() {
                this.editing = ! this.editing;
            },

            cancelEdit() {
                this.toggleEdit();
                this.form.restore();
            },

            updateStatus(status) {
                axios.patch(this.route('api.taskStatus.update', {id: this.task.id}), {status: status})
                    .then(({data}) => {
                        this.status = status;
                        flash(data);
                    })
                    .catch(() => flash());
            },

            update() {
                this.form.patch(this.route('api.tasks.update', {task: this.task.id, card: this.cardId}))
                    .then((data) => {
                        this.toggleEdit();
                        flash(data);
                    })
                    .catch(() => flash());
            },

            remove() {
                axios.delete(this.route('api.tasks.destroy', {task: this.task.id, card: this.cardId}))
                    .then(({data}) => {
                        this.$emit('removed', data);
                        flash(data);
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
        width: calc(100% + 40px);
        height: 100%;
        left: 0;
        margin-left: -20px;
        margin-right: -20px;
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

        input[type="text"]{
            height: 24px;
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
