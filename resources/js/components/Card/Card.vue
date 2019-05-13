<template>
    <div class="card mb-4 shadow-sm">
        <div class="card-header d-flex align-items-center">
            <div class="flex-fill">
                <h4 class="mb-1" v-text="card.name"></h4>
                <div class="small-text">
                    <span class="text-muted text-red">Created by: </span>
                    <a class="text-blue" href="#" v-text="card.creator.name"></a>
                </div>
            </div>
            <small class="text-muted text-right text-nowrap">{{ card.created_at | ago }}</small>
        </div>
        <div class="card-body">
            <div class="text-muted" v-text="card.description"></div>
            <tasks :items="card.tasks"></tasks>
        </div>
        <div class="card-footer">
            <div class="small-text">
                <span class="text-muted text-red">Participants: </span>
                <div v-for="user in card.participants">
                    <a class="text-blue" href="#" v-text="user.name"></a>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Tasks from './Tasks.vue';
    import moment from 'moment';

    export default {
        props: ['card'],
        components: {Tasks},

        filters: {
            ago(date) {
                return moment(date).fromNow();
            }
        }
    }
</script>

<style scoped lang="scss">
    .small-text{
        font-size: .75rem;
    }
</style>
