<template>

    <div class="tasks">
        <new-task :cardId="cardId" @added="add"></new-task>

        <ul class="list-unstyled">
            <li class="my-1" v-for="(task, index) in tasks" :key="task.id">
                <task :task="task" :cardId="cardId" @removed="remove(index)"></task>
            </li>
        </ul>
    </div>

</template>

<script>
    import Task from './Task';
    import NewTask from './NewTask';

    export default {
        props: ['items', 'cardId'],
        components: {Task, NewTask},

        data() {
            return {
                tasks: this.items
            };
        },

        methods: {
            add(item) {
                item.isNew = true;
                this.tasks.unshift(item);
            },

            remove(index) {
                this.tasks.splice(index, 1);
            }
        }
    }
</script>

<style lang="scss">
    .tasks {
        input[type="text"]{
            height: 24px;
        }

        ul{
            margin: .75rem 0 0;
        }
    }
</style>
