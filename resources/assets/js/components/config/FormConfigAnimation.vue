<template>

    <div class="collapsible-header">Animação</div>
    <div class="collapsible-body pl pr">
        <ui-slider
                v-if="config"
                label="Escala de tempo"
                :val.sync="config.scale"
                step="0.1"
                min="0"
                max="2"
                decimals="1"
        ></ui-slider>

        <ul class="collapsible popout mt-0" data-collapsible="accordion">

            <li class="collection-item">

                <div class="collapsible-header">
                    <i class="fa fa-square red-text" aria-hidden="true"></i> Infecção
                </div>
                <div class="collapsible-body pl pr">

                    <ui-slider
                            v-if="config"
                            label="Tempo de pausa (s)"
                            :val.sync="config.infect.restTime"
                            step="0.1"
                            min="0"
                            max="10"
                            decimals="1"
                            postfix=""
                    ></ui-slider>

                    <ui-slider
                            v-if="config"
                            label="Duração de movimento (s)"
                            :val.sync="config.infect.dotMovimentTime"
                            step="0.1"
                            min="0"
                            max="3"
                            decimals="1"
                            postfix=""
                    ></ui-slider>

                    <ui-slider
                            v-if="config"
                            label="Duração de expansão (s)"
                            :val.sync="config.infect.expandTime"
                            step="0.1"
                            min="0"
                            max="3"
                            decimals="1"
                            postfix=""
                    ></ui-slider>

                    <ui-slider
                            v-if="config"
                            label="Atraso de expansão (s)"
                            :val.sync="config.infect.expandDelay"
                            step="0.1"
                            min="0"
                            max="3"
                            decimals="1"
                            postfix=""
                    ></ui-slider>

                    <ui-slider
                            v-if="config"
                            label="Escala de expansão"
                            :val.sync="config.infect.expandScale"
                            step="0.1"
                            min="1"
                            max="2"
                            decimals="1"
                            postfix=""
                    ></ui-slider>

                    <ui-slider
                            v-if="config"
                            label="Duração de retração (s)"
                            :val.sync="config.infect.retractTime"
                            step="0.1"
                            min="0"
                            max="3"
                            decimals="1"
                            postfix=""
                    ></ui-slider>

                    <ui-slider
                            v-if="config"
                            label="Raio de vibração"
                            :val.sync="config.infect.shakeRadius"
                            step="1"
                            min="0"
                            max="30"
                            decimals="0"
                            postfix=""
                    ></ui-slider>

                </div>

            </li>

            <li class="collection-item">

                <div class="collapsible-header">
                    <i class="fa fa-square yellow-text" aria-hidden="true"></i> Recuperação
                </div>
                <div class="collapsible-body pl pr">

                    <ui-slider
                            v-if="config"
                            label="Tempo de pausa (s)"
                            :val.sync="config.recover.restTime"
                            step="0.1"
                            min="0"
                            max="10"
                            decimals="1"
                            postfix=""
                    ></ui-slider>

                    <ui-slider
                            v-if="config"
                            label="Duração de expansão (s)"
                            :val.sync="config.recover.expandTime"
                            step="0.1"
                            min="0"
                            max="3"
                            decimals="1"
                            postfix=""
                    ></ui-slider>

                    <ui-slider
                            v-if="config"
                            label="Escala de expansão"
                            :val.sync="config.recover.expandScale"
                            step="0.1"
                            min="1"
                            max="2"
                            decimals="1"
                            postfix=""
                    ></ui-slider>

                    <ui-slider
                            v-if="config"
                            label="Duração de retração (s)"
                            :val.sync="config.recover.retractTime"
                            step="0.1"
                            min="0"
                            max="3"
                            decimals="1"
                            postfix=""
                    ></ui-slider>


                </div>

            </li>

            <li class="collection-item">

                <div class="collapsible-header">
                    <i class="fa fa-square black-text" aria-hidden="true"></i> Morte
                </div>
                <div class="collapsible-body pl pr">

                    <ui-slider
                            v-if="config"
                            label="Tempo de pausa (s)"
                            :val.sync="config.death.restTime"
                            step="0.1"
                            min="0"
                            max="10"
                            decimals="1"
                            postfix=""
                    ></ui-slider>

                    <ui-slider
                            v-if="config"
                            label="Escala de retração"
                            :val.sync="config.death.retractScale"
                            step="0.1"
                            min="0"
                            max="1"
                            decimals="1"
                            postfix=""
                    ></ui-slider>

                    <ui-slider
                            v-if="config"
                            label="Duração de retração (s)"
                            :val.sync="config.death.retractTime"
                            step="0.1"
                            min="0"
                            max="4"
                            decimals="1"
                            postfix=""
                    ></ui-slider>

                    <ui-slider
                            v-if="config"
                            label="Duração de retração (aresta) (s)"
                            :val.sync="config.death.edgeRetractTime"
                            step="0.1"
                            min="0"
                            max="3"
                            decimals="1"
                            postfix=""
                    ></ui-slider>

                    <ui-slider
                            v-if="config"
                            label="Tamanho de retração (aresta)"
                            :val.sync="config.death.edgeRetractSize"
                            step="0.1"
                            min="0"
                            max="3"
                            decimals="1"
                            postfix=""
                    ></ui-slider>

                    <ui-slider
                            v-if="config"
                            label="Atraso de retração (aresta) (s)"
                            :val.sync="config.death.edgeRetractDelay"
                            step="0.1"
                            min="0"
                            max="3"
                            decimals="1"
                            postfix=""
                    ></ui-slider>

                    <ui-slider
                            v-if="config"
                            label="Duração de expansão (nascimento) (s)"
                            :val.sync="config.death.birthExpandTime"
                            step="0.1"
                            min="0"
                            max="3"
                            decimals="1"
                            postfix=""
                    ></ui-slider>

                    <ui-slider
                            v-if="config"
                            label="Atraso de expansão (nascimento) (s)"
                            :val.sync="config.death.birthExpandDelay"
                            step="0.1"
                            min="0"
                            max="3"
                            decimals="1"
                            postfix=""
                    ></ui-slider>

                    <ui-slider
                            v-if="config"
                            label="Escala de expansão (nascimento)"
                            :val.sync="config.death.birthExpandScale"
                            step="0.1"
                            min="0"
                            max="3"
                            decimals="1"
                            postfix=""
                    ></ui-slider>

                    <ui-slider
                            v-if="config"
                            label="Duração de retração (nascimento) (s)"
                            :val.sync="config.death.birthRetractTime"
                            step="0.1"
                            min="0"
                            max="3"
                            decimals="1"
                            postfix=""
                    ></ui-slider>

                </div>

            </li>

        </ul>
    </div>

</template>

<script>
    export default {

        props: ['config'],

        ready() {
            $(this.$el).find('.collapsible').collapsible();
        }
    }
</script>