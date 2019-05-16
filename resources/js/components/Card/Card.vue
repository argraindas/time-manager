<template>
    <div class="card mb-4 shadow-sm">
        <div class="card-header">
            <div class="d-flex">
                <h4 class="flex-fill" v-text="card.name"></h4>
                <small class="text-muted text-right text-nowrap">{{ card.created_at | ago }}</small>
            </div>
            <div class="small-text">
                <span class="text-muted text-red">Created by: </span>
                <a class="text-blue" href="#" v-text="card.creator.name"></a>
            </div>
        </div>
        <div class="card-body">
            <div class="card-text text-muted" v-text="card.description"></div>
            <tasks :items="card.tasks" :cardId="card.id"></tasks>
        </div>
        <div class="card-footer">
            <participants :items="card.participants"></participants>
        </div>
    </div>
</template>

<script>
    import Tasks from './Tasks.vue';
    import Participants from './Participants.vue';
    import moment from 'moment';

    export default {
        props: ['card'],
        components: {Tasks, Participants},

        filters: {
            ago(date) {
                return moment(date).fromNow();
            }
        }
    }
</script>

<style>
    .card-body{
        padding-top: .75rem;
        padding-bottom: .75rem;
    }

    .card-text{
        font-style: italic;
        font-size: .75rem;
        line-height: 1.3;
        margin-bottom: 0.75rem;
    }
</style>
