<template>

    <div v-if="config">

        <div class="input-field ta-l">
            <ui-select label="Método de Geração" :options="method" :val.sync="config.method"></ui-select>
        </div>

        <form-config-full-random
                v-show="config.method == 'fullRandom'"
                :factory="fullRandom"
        >
        </form-config-full-random>

        <form-config-uniform-format
                v-show="config.method == 'uniformFormat'"
                :factory="uniformFormat"
        >
        </form-config-uniform-format>

    </div>

</template>

<script>
    export default {

        data() {
            return {
                method: [
                    {"label": "Full Random", "val": "fullRandom"},
                    {"label": "Uniform Format", "val": "uniformFormat"}
                ],
                fullRandom: {},
                uniformFormat: {}
            }
        },

        props: {
            config: {
                "type": Object,
                "default": function () {
                    return {
                        method: "",
                        factory: {}
                    }
                }
            }
        },

        watch: {
            fullRandom(val) {
                if (this.config.method = 'fullRandom')
                    this.config.factory = val;
            },
            uniformFormat(val) {
                if (this.config.method = 'uniformFormat')
                    this.config.factory = val;
            }
        },

        ready() {
            this.$watch('config.method', function (method) {
                if (method == 'fullRandom') {
                    $.getJSON('api/getFactoryFullRandom',
                            (data) => this.config.factory = this.fullRandom = _.isEmpty(this.fullRandom) ? data : this.fullRandom
                    );
                }
                else if (method == 'uniformFormat') {
                    $.getJSON('api/getFactoryUniformFormat',
                            (data) => this.config.factory = this.uniformFormat = _.isEmpty(this.uniformFormat) ? data : this.uniformFormat
                    );
                }
            });
        }
    }
</script>