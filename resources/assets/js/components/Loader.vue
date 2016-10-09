
<template>
    <div class="loader">
        <i class="fa fa-spinner fa-spin fa-5x fa-fw"></i>
        <span class="sr-only">Loading...</span>
    </div>
</template>

<style>
    .loader {
        color: #666;
        position: absolute;
        top: 50%;
        left: 50%;
        /* bring your own prefixes */
        transform: translate(-50%, -50%);
    }
</style>

<script>

    export default {

        methods: {
            show() {
                var el = $(this.$el);
                el.show();
            },

            hide() {
                var el = $(this.$el);
                el.hide();
            }
        },

        ready() {
            
        }
    }
</script>