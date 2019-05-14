<template>
    <ul class="list-unstyled mt-3">
        <li class="my-1" v-for="task in items" :key="task.id">
            <task :task="task"></task>
        </li>
    </ul>
</template>

<script>
    import Task from './Task';

    export default {
        props: ['items'],
        components: {Task}
    }
</script>
