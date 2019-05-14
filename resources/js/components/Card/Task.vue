<template>
    <div class="custom-control custom-checkbox mr-sm-2">
        <input type="checkbox" class="custom-control-input" :id="task.id" @click="update">
        <label class="custom-control-label" :for="task.id" v-text="task.name"></label>
    </div>
</template>

<script>
    export default {
        props: ['task'],

        methods: {
            update() {
                axios.patch(this.route('api.taskStatus.update', {id: this.task.id}), {status: 'done'})
                    .then(({data}) => {
                        this.$emit('updated', data);
                        flash(data);
                    })
                    .catch(() => flash());
            }
        }

    }
</script>
