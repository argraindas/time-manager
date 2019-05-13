<template>
    <ul class="list-unstyled mt-3">
        <li class="my-1" v-for="task in items" :key="task.id">
            <div class="custom-control custom-checkbox mr-sm-2">
                <input type="checkbox" class="custom-control-input" :id="task.id">
                <label class="custom-control-label" :for="task.id" v-text="task.name"></label>
            </div>
        </li>
    </ul>
</template>

<script>
    export default {
        props: ['items']
    }
</script>
