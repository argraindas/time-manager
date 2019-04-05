<template>
    <ul class="nav flex-column" v-cloak>
        <menu-item title="Dashboard" :href="route('dashboard')" :active="isActive('dashboard')" icon="dashboard"></menu-item>
        <menu-item title="Categories" :href="route('categories')" :active="isActive('categories')" icon="storage"></menu-item>
        <menu-item title="Records" :href="route('records')" :active="isActive('records')" icon="timer"></menu-item>
    </ul>
</template>

<script>
    import MenuItem from "./MenuItem";

    export default {
        components: {MenuItem},
        props: ['activeRoute'],
        methods: {
            isActive(name) {
                return (name === this.activeRoute);
            }
        }

    };
</script>
