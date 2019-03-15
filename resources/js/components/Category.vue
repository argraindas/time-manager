<template>
    <div class="d-flex align-items-center">
        <div class="color mr-2"></div>

        <h6 v-text="category.name" class="flex-fill mb-0"></h6>

        <button type="submit" class="btn btn-sm btn-primary mr-2">Edit</button>
        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
    </div>
</template>

<script>
    export default {
        props: ['category'],

        data() {
            return {

            }
        }
    }
</script>

<style scoped>
    .color{
        width: 20px;
        height: 20px;
        border-radius: 4px;
        margin-left: -10px;

        background-color: yellow;
    }
</style>
