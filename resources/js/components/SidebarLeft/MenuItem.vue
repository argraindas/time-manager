<template>
    <li class="nav-item">
        <a class="nav-link" :class="activeClass" :href="href">
            <i class="material-icons">{{ icon }}</i>
            {{ title }}
        </a>
    </li>
</template>

<script>
    export default {
        props: ['title', 'href', 'active', 'icon'],

        computed: {
            activeClass() {
                return this.active ? 'active' : '';
            }
        },
    };
</script>
