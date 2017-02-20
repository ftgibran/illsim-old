<template>
    <div class="sidenav card-panel grey lighten-5">
        <slot></slot>
    </div>
</template>

<style lang="sass" rel="stylesheet/scss" scoped>

    .sidenav {
        overflow-y: auto;
        overflow-x: hidden;
        position: absolute;
        margin: 0;
        padding: 0;
        left: 0;
        top: 0;
        bottom: 0;
        width: 500px;
        z-index: 100;
    }

</style>

<script>

    export default {

        data() {
            return {
                time: 1
            }
        },

        methods: {
            show() {
                var el = $(this.$el);
                var $self = this;

                TweenMax.to(el, $self.time, {
                    x: 0,
                    ease: Power3.easeOut
                });
            },

            hide() {
                var el = $(this.$el);
                var $self = this;

                TweenMax.to(el, $self.time, {
                    x: -el.width(),
                    ease: Power3.easeOut
                });
            }
        },

        ready() {
            var el = $(this.$el);
            var $self = this;

            $(el).hover(
                    function () {
                        TweenMax.to(el, $self.time, {
                            opacity: 1,
                            ease: Power3.easeOut
                        });
                    },
                    function () {
                        TweenMax.to(el, $self.time, {
                            opacity: 0.4,
                            ease: Power1.easeOut
                        });
                    }
            )
        }
    }
</script>