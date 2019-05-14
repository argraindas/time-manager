<template>
    <div>

        <new-task :cardId="cardId" @added="add"></new-task>

        <ul class="list-unstyled mt-3">
            <li class="my-1" v-for="task in tasks" :key="task.id">
                <task :task="task"></task>
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
                this.tasks.unshift(item);
            }
        }
    }
</script>
