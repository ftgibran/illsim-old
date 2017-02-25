<template>
    <div class="pos-r" style="height: 100%;">
        <div v-if="!done && !lazy" class="loader">
            <div class="preloader-wrapper big active">
                <div class="spinner-layer">
                    <div class="circle-clipper left">
                        <div class="circle"></div>
                    </div>
                    <div class="gap-patch">
                        <div class="circle"></div>
                    </div>
                    <div class="circle-clipper right">
                        <div class="circle"></div>
                    </div>
                </div>
            </div>
        </div>
        <slot v-if="done"></slot>
    </div>
</template>

<style>
    .loader {
        position: absolute;
        top: calc(50% - 29px);
        left: calc(50% - 29px);
        z-index: 99;
    }
</style>

<script>

    export default {

        props: ['onLoad', 'lazy'],

        data() {
            return {
                done: false
            }
        },

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

        watch: {
            done(val) {
                if(val && this.onLoad) this.onLoad();
            }
        },

        ready() {
            this.$parent.$on('load', () => {
                this.done = true;
            });
            this.$parent.$on('reset', () => {
                this.done = false;
                this.lazy = false;
            });
        }
    }
</script>