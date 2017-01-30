<template>
    <div class="collapsible-header">Inoculação</div>
    <div v-if="config" class="collapsible-body pl pr">

    </div>
</template>

<script>
    export default {

        data() {
            return {
                method: [
                    {"label": "Método 1", "val": "method-1"},
                    {"label": "Método 2", "val": "method-2"},
                    {"label": "Método 3", "val": "method-3"},
                    {"label": "Método 4", "val": "method-4"}
                ]
            };
        },

        props: ['config'],

        ready() {
        }
    }
</script>