<template>
    <form @submit.prevent="persistConfig" method="post" class="ta-c">

        <ul v-if="config" class="collapsible mt-0" data-collapsible="accordion">
            <li class="mt-0 mb-0 ta-l" style="padding: 0;">

                <nav class="blue-grey darken-2 white-text pl pr">
                    <div class="nav-wrapper">
                        <div class="brand-logo">
                            <i class="left material-icons">settings</i>
                        </div>
                        <ul id="nav-mobile" class="right hide-on-med-and-down">
                            <li>
                                <button type="button" @click="persistConfig()"
                                        class="blue-grey lighten-5 black-text fw-b btn-large">
                                    Simular
                                </button>
                            </li>
                        </ul>
                    </div>
                </nav>

            </li>

            <li class="grey lighten-5">
                <form-config-animation :config="config.animation"></form-config-animation>
            </li>

            <li class="grey lighten-5">
                <form-config-simulation :config="config.simulation"></form-config-simulation>
            </li>

            <li class="grey lighten-5">
                <form-config-factory :config="config.generator"></form-config-factory>
            </li>
        </ul>

    </form>
</template>

<script>
    export default {

        data() {
            return {
                config: {}
            };
        },

        methods: {
            persistConfig() {
                this.$emit('submit', this.config);
//				$.getJSON('api/persistConfig', $(this.$el).serialize(), function (data) {
//					$self.$emit('submit', data);
//				}).bind(this);
            }
        },

        ready() {
            $(this.$el).find('.collapsible').collapsible();
            $.getJSON('api/getConfig', (data) => this.config = data);
        }
    }
</script>