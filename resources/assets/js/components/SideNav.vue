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
        z-index: 10000;
    }

</style>

<script>

    export default {

        props: {
            time: {
                type: Number,
                default: 1
            },
        },

        events: {
            shown() {
                $(document).on('click', (e) => {
                    if ($(e.target).closest(this.$el).length === 0) {
                        this.hide();
                    }
                });
            },
            hidden() {
                $(document).off('click');
            }
        },

        data() {
            return {
                hidden: false
            }
        },

        methods: {
            show() {
                var el = $(this.$el);

                TweenMax.to(el, this.time, {
                    x: 0,
                    ease: Power3.easeOut
                });
            },

            hide() {
                var el = $(this.$el);

                TweenMax.to(el, this.time, {
                    x: -el.width(),
                    ease: Power3.easeOut
                });
            }
        },

        ready() {
            var el = $(this.$el);

            $(el).hover(
                    (e) => {
                        TweenMax.to(el, this.time, {
                            opacity: 1,
                            ease: Power3.easeOut
                        });
                    },
                    (e) => {
                        TweenMax.to(el, this.time, {
                            opacity: 0.4,
                            ease: Power1.easeOut
                        });
                    }
            );

            this.$root.$on('reset', () => {
                this.hide();
            });
        }
    }
</script>