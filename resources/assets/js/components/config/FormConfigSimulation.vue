<template>
    <div>

        <div v-if="config">

            <div class="input-field ta-l">
                <ui-select label="Modo de simulação" :options="mode" :val.sync="config.mode"></ui-select>
            </div>

            <ui-slider
                    v-show="config.mode == 'visual'"
                    label="Intervalo de tempo (ms)"
                    :val.sync="config.step"
                    step="50"
                    min="0"
                    max="2000"
                    decimals="0"
            ></ui-slider>

            <ul class="collection" style="overflow: visible;">

                <li class="collection-item">
                    <div class="input-field ta-l">
                        <ui-select label="Tipo de infecção" :options="infectBy" :val.sync="config.infectBy"></ui-select>
                    </div>

                    <ui-slider v-if="config.infectBy == 'special'"
                               label="Constante k"
                               :val.sync="config.k"
                               step="0.01"
                               min="0"
                               max="1"
                               decimals="0"
                    ></ui-slider>
                </li>

                <li class="collection-item">
                    <ui-checkbox :checked.sync="config.inoculation.active" name="config.inoculation.active"
                                 label='Ativar Inoculação'>
                        <div style="position: relative; margin-top: 1em;">
                            <ui-select label="Tipo de vacinação"
                                       :options="inoculationBy"
                                       :val.sync="config.inoculation.by">
                            </ui-select>

                            <div v-if="config.mode == 'visual'">
                                <ui-slider
                                        label="Número de distribuições / passo de tempo"
                                        :val.sync="config.inoculation.rate"
                                        step="1"
                                        min="1"
                                        max="10"
                                        decimals="0"
                                ></ui-slider>
                                <ui-slider
                                        label="Limite de distribuição"
                                        :val.sync="config.inoculation.limit"
                                        step="1"
                                        min="0"
                                        max="200"
                                ></ui-slider>
                            </div>

                            <div v-if="config.mode == 'scientific'">
                                <ui-slider
                                        label="Número de distribuições / passo de tempo"
                                        :val.sync="config.inoculation.rate"
                                        step="1"
                                        min="1"
                                        max="100"
                                        decimals="0"
                                ></ui-slider>
                                <ui-slider
                                        label="Limite de distribuição"
                                        :val.sync="config.inoculation.limit"
                                        step="50"
                                        min="0"
                                        max="20000"
                                ></ui-slider>
                            </div>

                        </div>
                    </ui-checkbox>
                </li>

            </ul>

        </div>

        <ul class="collapsible popout mt-0" data-collapsible="accordion">

            <li class="card-panel blue-grey darken-2 white-text mt-0 mb-0 pt-s pb-s ta-l">
                Comportamento
            </li>

            <li class="collection-item">
                <div class="collapsible-header">
                    <i class="fa fa-square red-text" aria-hidden="true"></i> Infectados
                </div>
                <ul v-if="config" class="collapsible-body pr-0 pt-0 pl-0 pb-0 collection">
                    <!--INFECTADOS-->

                    <li class="collection-item">
                        <ui-checkbox :checked.sync="config.i.mayInfect" name="config.i.mayInfect"
                                     label='Podem infectar'>
                            <ui-slider
                                    label="Taxa de infecção base"
                                    icon="fa-square red-text"
                                    :val.sync="config.i.base.infect"
                                    step="0.1"
                                    decimals="1"
                                    postfix="%"
                            ></ui-slider>
                        </ui-checkbox>
                    </li>

                    <li class="collection-item">
                        <ui-checkbox :checked.sync="config.i.mayRecover" name="config.i.mayRecover"
                                     label='Podem se recuperar'>
                            <ui-slider
                                    label="Taxa de recuperação base"
                                    icon="fa-square yellow-text"
                                    :val.sync="config.i.base.recover"
                                    step="0.1"
                                    decimals="1"
                                    postfix="%"
                            ></ui-slider>
                        </ui-checkbox>
                    </li>

                    <li class="collection-item">
                        <ui-checkbox :checked.sync="config.i.mayGetSusceptible" name="config.i.mayGetSusceptible"
                                     label='Podem se tornar suscetíveis'>
                            <ui-slider
                                    label="Taxa de suscetibilidade base"
                                    icon="fa-square grey-text"
                                    :val.sync="config.i.base.susceptible"
                                    step="0.1"
                                    decimals="1"
                                    postfix="%"
                            ></ui-slider>
                        </ui-checkbox>
                    </li>

                    <li class="collection-item">
                        <ui-checkbox :checked.sync="config.i.mayBeVaccinated" name="config.i.mayBeVaccinated"
                                     label='Podem ser vacinados'>
                        </ui-checkbox>
                    </li>

                    <li class="collection-item">
                        <ui-checkbox :checked.sync="config.i.mayDie" name="config.i.mayDie"
                                     label='Podem falecer'>
                            <ui-slider
                                    label="Taxa de falecimento base"
                                    icon="fa-square black-text"
                                    :val.sync="config.i.base.death"
                                    step="0.1"
                                    decimals="1"
                                    postfix="%"
                            ></ui-slider>
                        </ui-checkbox>
                    </li>

                </ul>
            </li>

            <li class="collection-item">
                <div class="collapsible-header">
                    <i class="fa fa-square yellow-text" aria-hidden="true"></i> Recuperados
                </div>
                <ul v-if="config" class="collapsible-body pl-0 pr-0 pt-0 pb-0 collection">
                    <!--RECUPERADOS-->

                    <li class="collection-item">
                        <ui-checkbox :checked.sync="config.r.mayInfect" name="config.r.mayInfect"
                                     label='Podem infectar'>
                            <ui-slider
                                    label="Taxa de infecção base"
                                    icon="fa-square red-text"
                                    :val.sync="config.r.base.infect"
                                    step="0.1"
                                    decimals="1"
                                    postfix="%"
                            ></ui-slider>
                        </ui-checkbox>
                    </li>

                    <li class="collection-item">
                        <ui-checkbox :checked.sync="config.r.mayBeInfected" name="config.r.mayBeInfected"
                                     label='Podem ser infectados'>
                            <ui-slider
                                    label="Taxa de resistência base"
                                    icon="fa-square-o green-text"
                                    :val.sync="config.r.base.resist"
                                    step="0.1"
                                    decimals="1"
                                    postfix="%"
                            ></ui-slider>
                        </ui-checkbox>
                    </li>

                    <li class="collection-item">
                        <ui-checkbox :checked.sync="config.r.mayGetSusceptible" name="config.r.mayGetSusceptible"
                                     label='Podem se tornar suscetíveis'>
                            <ui-slider
                                    label="Taxa de suscetibilidade base"
                                    icon="fa-square grey-text"
                                    :val.sync="config.r.base.susceptible"
                                    step="0.1"
                                    decimals="1"
                                    postfix="%"
                            ></ui-slider>
                        </ui-checkbox>
                    </li>

                    <li class="collection-item">
                        <ui-checkbox :checked.sync="config.r.mayBeVaccinated" name="config.r.mayBeVaccinated"
                                     label='Podem ser vacinados'>
                        </ui-checkbox>
                    </li>

                    <li class="collection-item">
                        <ui-checkbox :checked.sync="config.r.mayDie" name="config.r.mayDie"
                                     label='Podem falecer'>
                            <ui-slider
                                    label="Taxa de falecimento base"
                                    icon="fa-square black-text"
                                    :val.sync="config.r.base.death"
                                    step="0.1"
                                    decimals="1"
                                    postfix="%"
                            ></ui-slider>
                        </ui-checkbox>
                    </li>

                </ul>
            </li>

            <li class="collection-item">
                <div class="collapsible-header">
                    <i class="fa fa-square grey-text" aria-hidden="true"></i> Suscetíveis
                </div>
                <ul v-if="config" class="collapsible-body pl-0 pr-0 pt-0 pb-0 collection">
                    <!--SUSCETIVEIS-->

                    <li class="collection-item">
                        <ui-checkbox :checked.sync="config.s.mayInfect" name="config.s.mayInfect"
                                     label='Podem infectar'>
                            <ui-slider
                                    label="Taxa de infecção base"
                                    icon="fa-square red-text"
                                    :val.sync="config.s.base.infect"
                                    step="0.1"
                                    decimals="1"
                                    postfix="%"
                            ></ui-slider>
                        </ui-checkbox>
                    </li>

                    <li class="collection-item">
                        <ui-checkbox :checked.sync="config.s.mayBeInfected" name="config.s.mayBeInfected"
                                     label='Podem ser infectados'>
                            <ui-slider
                                    label="Taxa de resistência base"
                                    icon="fa-square-o green-text"
                                    :val.sync="config.s.base.resist"
                                    step="0.1"
                                    decimals="1"
                                    postfix="%"
                            ></ui-slider>
                        </ui-checkbox>
                    </li>

                    <li class="collection-item">
                        <ui-checkbox :checked.sync="config.s.mayRecover" name="config.s.mayRecover"
                                     label='Podem se recuperar'>
                            <ui-slider
                                    label="Taxa de recuperação base"
                                    icon="fa-square yellow-text"
                                    :val.sync="config.s.base.recover"
                                    step="0.1"
                                    decimals="1"
                                    postfix="%"
                            ></ui-slider>
                        </ui-checkbox>
                    </li>

                    <li class="collection-item">
                        <ui-checkbox :checked.sync="config.s.mayBeVaccinated" name="config.s.mayBeVaccinated"
                                     label='Podem ser vacinados'>
                        </ui-checkbox>
                    </li>

                    <li class="collection-item">
                        <ui-checkbox :checked.sync="config.s.mayDie" name="config.s.mayDie"
                                     label='Podem falecer'>
                            <ui-slider
                                    label="Taxa de falecimento base"
                                    icon="fa-square black-text"
                                    :val.sync="config.s.base.death"
                                    step="0.1"
                                    decimals="1"
                                    postfix="%"
                            ></ui-slider>
                        </ui-checkbox>
                    </li>

                </ul>
            </li>

            <li class="collection-item">
                <div class="collapsible-header">
                    <i class="fa fa-square green-text" aria-hidden="true"></i> Vacinados
                </div>
                <ul v-if="config" class="collapsible-body pl-0 pr-0 pt-0 pb-0 collection">
                    <!--VACINADOS-->

                    <li class="collection-item">
                        <ui-checkbox :checked.sync="config.v.mayInfect" name="config.v.mayInfect"
                                     label='Podem infectar'>
                            <ui-slider
                                    label="Taxa de infecção base"
                                    icon="fa-square red-text"
                                    :val.sync="config.v.base.infect"
                                    step="0.1"
                                    decimals="1"
                                    postfix="%"
                            ></ui-slider>
                        </ui-checkbox>
                    </li>

                    <li class="collection-item">
                        <ui-checkbox :checked.sync="config.v.mayBeInfected" name="config.v.mayBeInfected"
                                     label='Podem ser infectado'>
                            <ui-slider
                                    label="Taxa de resistência base"
                                    icon="fa-square-o green-text"
                                    :val.sync="config.v.base.resist"
                                    step="0.1"
                                    decimals="1"
                                    postfix="%"
                            ></ui-slider>
                        </ui-checkbox>
                    </li>

                    <li class="collection-item">
                        <ui-checkbox :checked.sync="config.v.mayRecover" name="config.v.mayRecover"
                                     label='Podem se recuperar'>
                            <ui-slider
                                    label="Taxa de recuperação base"
                                    icon="fa-square yellow-text"
                                    :val.sync="config.v.base.recover"
                                    step="0.1"
                                    decimals="1"
                                    postfix="%"
                            ></ui-slider>
                        </ui-checkbox>
                    </li>

                    <li class="collection-item">
                        <ui-checkbox :checked.sync="config.v.mayGetSusceptible" name="config.v.mayGetSusceptible"
                                     label='Podem se tornar suscetíveis'>
                            <ui-slider
                                    label="Taxa de suscetibilidade base"
                                    icon="fa-square grey-text"
                                    :val.sync="config.v.base.susceptible"
                                    step="0.1"
                                    decimals="1"
                                    postfix="%"
                            ></ui-slider>
                        </ui-checkbox>
                    </li>

                    <li class="collection-item">
                        <ui-checkbox :checked.sync="config.v.mayDie" name="config.v.mayDie"
                                     label='Podem falecer'>
                            <ui-slider
                                    label="Taxa de falecimento base"
                                    icon="fa-square black-text"
                                    :val.sync="config.v.base.death"
                                    step="0.1"
                                    decimals="1"
                                    postfix="%"
                            ></ui-slider>
                        </ui-checkbox>
                    </li>
                </ul>
            </li>

            <li class="collection-item">
                <div class="collapsible-header">
                    <i class="fa fa-square black-text" aria-hidden="true"></i> Falecidos
                </div>
                <ul v-if="config" class="collapsible-body pl-0 pr-0 pt-0 pb-0 collection">
                    <!--FALECIDOS-->

                    <li class="collection-item">
                        <ui-checkbox :checked.sync="config.d.birthWhenDie"
                                     label='Nascimento após morte' name='config.d.birthWhenDie'></ui-checkbox>
                    </li>
                </ul>
            </li>
        </ul>

    </div>

</template>

<script>
    export default {

        data() {
            return {
                mode: [
                    {"label": "Rede em tempo real (baixa escala de dados)", "val": "visual"},
                    {"label": "Científico (alta escala de dados)", "val": "scientific"},
                ],
                infectBy: [
                    {"label": "Nó", "val": "node"},
                    {"label": "Aresta", "val": "edge"},
                    {"label": "Nó e Aresta", "val": "both"},
                    {"label": "Fórmula [1-exp(-k*n)]", "val": "special"}
                ],
                inoculationBy: [
                    {"label": "Aleatório", "val": "random"},
                    {"label": "Maior Grau", "val": "grade"},
                    {"label": "Vizinhos de Maior Grau", "val": "neighbor"}
                ]
            };
        },

        props: ['config'],

        ready() {
            $(this.$el).find('.collapsible').collapsible();
        }
    }
</script>