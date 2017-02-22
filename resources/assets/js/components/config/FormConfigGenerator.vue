<template>

    <div v-if="config">

        <div class="input-field ta-l">
            <ui-select label="Método de Geração" :options="methods" :val.sync="config.method"></ui-select>
        </div>

        <form-config-full-random
                v-show="config.method == 'fullRandom'"
                :factory="config.factory.fullRandom"
        >
        </form-config-full-random>

        <form-config-uniform-format
                v-show="config.method == 'uniformFormat'"
                :factory="config.factory.uniformFormat"
        >
        </form-config-uniform-format>

    </div>

</template>

<script>
    export default {

        data() {
            return {
                methods: [
                    {
                        "label": "Full Random",
                        "val": "fullRandom"
                    },
                    {
                        "label": "Uniform Format",
                        "val": "uniformFormat"
                    }
                ]
            }
        },

        props: ['config'],

        ready() {
        }
    }
</script>