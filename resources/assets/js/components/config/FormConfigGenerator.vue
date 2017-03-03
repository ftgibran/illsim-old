<template>

    <ui-loader>

        <div class="input-field ta-l">
            <ui-select label="Método de Geração" :options.sync="methods" :val.sync="config.method"></ui-select>
        </div>

        <form-config-full-random
                v-show="config.method == 'fullRandom'"
                :factory="config.factory.fullRandom"
                :mode.sync="mode"
                :infect-by.sync="infectBy"
        ></form-config-full-random>

        <form-config-uniform-format
                v-show="config.method == 'uniformFormat'"
                :factory="config.factory.uniformFormat"
                :mode.sync="mode"
                :infect-by.sync="infectBy"
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

        props: ['config', 'mode', 'infectBy'],

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