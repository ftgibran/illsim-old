<template>

    <ui-loader>

        <div class="input-field ta-l">
            <ui-select label="Método de Geração" :options.sync="methods" :val.sync="config.method"></ui-select>
        </div>

        <form-config-full-random
                v-show="config.method == 'fullRandom'"
                :factory="config.factory.fullRandom"
                :mode.sync="mode"
        ></form-config-full-random>

        <form-config-uniform-format
                v-show="config.method == 'uniformFormat'"
                :factory="config.factory.uniformFormat"
                :mode.sync="mode"
        ></form-config-uniform-format>

    </ui-loader>

</template>

<script>
    export default {

        data() {
            return {
                methods: null,

                fullRandom: {
                    "label": "Full Random",
                    "val": "fullRandom"
                },

                uniformFormat: {
                    "label": "Uniform Format",
                    "val": "uniformFormat"
                }
            }
        },

        props: ['config', 'mode'],

        watch: {
            mode(val) {
//                if (val == 'scientific') {
//                    this.methods = [
//                        this.uniformFormat
//                    ];
//                    this.config.method = 'uniformFormat';
//                } else {
//                    this.init();
//                }
                this.init();
            }
        },

        methods: {
            init() {
                this.methods = [
                    this.fullRandom,
                    this.uniformFormat
                ];
            }
        },

        ready() {
            this.init();
            this.$emit('load');
        }
    }
</script>