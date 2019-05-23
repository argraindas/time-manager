<template>

    <div class="tasks">
        <new-task :cardId="cardId" @added="add"></new-task>
        <span class="someone-is-typing text-red" v-if="typingUser" v-text="typingUser.name + ' is typing...'"></span>

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
                tasks: this.items,
                typingUser: null,
                typingTimer: null,
            };
        },

        computed: {
            channel() {
                return window.Echo.private('tasks.' + this.cardId);
            }
        },

        created() {
            this.channel
                .listen('TaskCreated', ({task}) => this.add(task))
                .listenForWhisper('TypingOnTask', this.notifySomeoneIsTyping);
        },

        methods: {
            notifySomeoneIsTyping(e) {
                this.typingUser = e;

                if (this.typingTimer) {
                    clearTimeout(this.typingTimer);
                }

                this.typingTimer = setTimeout(() => {
                    this.typingUser = null;
                }, 2000);
            },

            add(item) {
                item.isNew = true;
                this.tasks.unshift(item);
                this.typingUser = null;
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

    .someone-is-typing{
        font-size: .75rem;
        font-style: italic;
        margin-top: 5px;
    }
</style>
