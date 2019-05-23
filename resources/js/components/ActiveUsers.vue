<template>

    <ul class="active-users">
        <li v-for="user in activeUsers">
            <a href="#" class="text-blue" v-text="user.name"></a>
        </li>
    </ul>

</template>

<script>

    export default {
        data() {
            return {
                activeUsers: []
            }
        },

        computed: {
            channel() {
                return window.Echo.join('users');
            }
        },

        created() {
            this.channel
                .here(users => this.activeUsers = users)
                .joining(user => this.add(user))
                .leaving(user => this.remove(user));
        },

        methods: {
            add(user) {
                this.activeUsers.unshift(user);
            },

            remove(user) {
                this.activeUsers.splice(this.activeUsers.indexOf(user), 1);
            }
        }
    }
</script>

<style lang="scss">
    @import "../../sass/variables";

    .active-users {
        list-style: none;
        line-height: 1;

        li::before {
            content: "\2022";
            color: $green;
            font-weight: bold;
            display: inline-block;
            width: 15px;
            margin-left: -10px;
            font-size: 25px;
            line-height: 22px;
        }
    }

</style>
