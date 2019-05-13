<template>
    <div class="card mb-4 shadow-sm">
        <div class="card-header">
            <h4 class="mb-0">{{ card.name }}</h4>
        </div>
        <div class="card-body">
            <div class="text-muted">{{ card.description }}</div>
            <ul class="list-unstyled mt-3">
                <li class="my-1">
                    <div class="custom-control custom-checkbox mr-sm-2">
                        <input type="checkbox" class="custom-control-input" id="1">
                        <label class="custom-control-label" for="1">Item 1</label>
                    </div>
                </li>
                <li class="my-1">
                    <div class="custom-control custom-checkbox mr-sm-2">
                        <input type="checkbox" class="custom-control-input" id="2">
                        <label class="custom-control-label" for="2">Item 2</label>
                    </div>
                </li>
                <li class="my-1">
                    <div class="custom-control custom-checkbox mr-sm-2">
                        <input type="checkbox" class="custom-control-input" id="3">
                        <label class="custom-control-label" for="3">Item 3</label>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</template>

<script>

    export default {
        props: ['card'],

    }
</script>
