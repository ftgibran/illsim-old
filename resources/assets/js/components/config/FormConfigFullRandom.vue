<template>
    <div>

        <ul class="collapsible popout" data-collapsible="accordion">

            <li class="card-panel blue-grey darken-2 white-text mt-0 mb-0 pt-s pb-s ta-l">
                Full Random
            </li>

            <li class="grey lighten-5">
                <div class="collapsible-header">Grupo</div>
                <div class="collapsible-body pl pr">

                    <div v-if="mode == 'visual'">
                        <ui-slider
                                v-if="factory.group"
                                label="Quantidade de grupos"
                                :val.sync="factory.group.quant"
                                step="1"
                                min="1"
                                max="5"
                        ></ui-slider>
                        <ui-slider
                                v-if="factory.group"
                                label="Conexões entre os grupos (mínimo e máximo)"
                                :val-min.sync="factory.group.connections.min"
                                :val-max.sync="factory.group.connections.max"
                                step="1"
                                min="0"
                                max="6"
                                :range="true"
                        ></ui-slider>

                        <ul class="collection" style="overflow: visible;">
                            <li class="collection-item">
                                <ui-checkbox :checked.sync="factory.group.startingValuesByGroup.enabled"
                                             name="factory.group.startingValuesByGroup.enabled"
                                             label='Valores iniciais por grupo'>
                                    <ui-slider
                                            v-if="factory.group"
                                            label="Quantidade de grupos"
                                            :val.sync="factory.group.startingValuesByGroup.quant"
                                            step="1"
                                            min="1"
                                            max="5"
                                    ></ui-slider>
                                </ui-checkbox>
                            </li>
                        </ul>

                    </div>
                    <div v-if="mode == 'scientific'">
                        <ui-slider
                                v-if="factory.group"
                                label="Quantidade de grupos"
                                :val.sync="factory.group.quant"
                                step="1"
                                min="1"
                                max="100"
                        ></ui-slider>
                        <ui-slider
                                v-if="factory.group"
                                label="Conexões entre os grupos (mínimo e máximo)"
                                :val-min.sync="factory.group.connections.min"
                                :val-max.sync="factory.group.connections.max"
                                step="1"
                                min="0"
                                max="30"
                                :range="true"
                        ></ui-slider>

                        <ul class="collection" style="overflow: visible;">
                            <li class="collection-item">
                                <ui-checkbox :checked.sync="factory.group.startingValuesByGroup.enabled"
                                             name="factory.group.startingValuesByGroup.enabled"
                                             label='Valores iniciais por grupo'>
                                    <ui-slider
                                            v-if="factory.group"
                                            label="Quantidade de grupos"
                                            :val.sync="factory.group.startingValuesByGroup.quant"
                                            step="1"
                                            min="1"
                                            max="100"
                                    ></ui-slider>
                                </ui-checkbox>
                            </li>
                        </ul>

                    </div>
                </div>
            </li>

            <li class="grey lighten-5">
                <div class="collapsible-header">Nós</div>
                <div class="collapsible-body pl pr">

                    <div v-if="mode == 'visual'">
                        <ui-slider
                                v-if="factory.node"
                                label="Quantidade (mínimo e máximo) por grupo"
                                :val-min.sync="factory.node.min"
                                :val-max.sync="factory.node.max"
                                step="1"
                                min="0"
                                max="200"
                                decimals="0"
                                :range="true"
                        ></ui-slider>
                    </div>
                    <div v-if="mode == 'scientific'">
                        <ui-slider
                                v-if="factory.node"
                                label="Quantidade (mínimo e máximo) por grupo"
                                :val-min.sync="factory.node.min"
                                :val-max.sync="factory.node.max"
                                step="1"
                                min="0"
                                max="1000"
                                decimals="0"
                                :range="true"
                        ></ui-slider>
                    </div>

                    <div v-if="factory.node" class="row">
                        <div class="input-field s12 m6 col">
                            <div class="fl-r">
                                <ui-checkbox :checked.sync="groupInfected.percent"
                                             name="groupInfected"
                                             label='%'
                                ></ui-checkbox>
                            </div>
                            <div style="overflow: hidden;">
                                <label class="active truncate" for="i-quant">
                                    <i class="fa fa-square red-text" aria-hidden="true"></i>
                                    Quantidade de infectados</label>
                                <input type="number" id="i-quant"
                                       v-model="groupInfected.quant">
                            </div>

                            <div class="cl-b"></div>
                        </div>

                        <div class="input-field s12 m6 col">
                            <div class="fl-r">
                                <ui-checkbox :checked.sync="groupVaccinated.percent"
                                             name="groupVaccinated"
                                             label='%'
                                ></ui-checkbox>
                            </div>
                            <div style="overflow: hidden;">
                                <label class="active truncate" for="v-quant">
                                    <i class="fa fa-square green-text" aria-hidden="true"></i>
                                    Quantidade de vacinados</label>
                                <input type="number" id="v-quant"
                                       v-model="groupVaccinated.quant"
                                       >
                            </div>

                            <div class="cl-b"></div>
                        </div>
                    </div>

                    <ui-slider
                            v-if="factory.node"
                            icon="fa-square red-text"
                            label="Taxa de infecção"
                            :val-min.sync="factory.node.rate.infect.min"
                            :val-max.sync="factory.node.rate.infect.max"
                            step="0.5"
                            min="0"
                            max="100"
                            decimals="1"
                            postfix="%"
                            :range="true"
                    ></ui-slider>

                    <ui-slider
                            v-if="factory.node"
                            icon="fa-square-o green-text"
                            label="Taxa de resistência"
                            :val-min.sync="factory.node.rate.resist.min"
                            :val-max.sync="factory.node.rate.resist.max"
                            step="0.5"
                            min="0"
                            max="100"
                            decimals="1"
                            postfix="%"
                            :range="true"
                    ></ui-slider>

                    <ui-slider
                            v-if="factory.node"
                            icon="fa-square yellow-text"
                            label="Taxa de recuperação"
                            :val-min.sync="factory.node.rate.recover.min"
                            :val-max.sync="factory.node.rate.recover.max"
                            step="0.5"
                            min="0"
                            max="100"
                            decimals="1"
                            postfix="%"
                            :range="true"
                    ></ui-slider>

                    <ui-slider
                            v-if="factory.node"
                            icon="fa-square grey-text"
                            label="Taxa de suscetibilidade"
                            :val-min.sync="factory.node.rate.susceptible.min"
                            :val-max.sync="factory.node.rate.susceptible.max"
                            step="0.5"
                            min="0"
                            max="100"
                            decimals="1"
                            postfix="%"
                            :range="true"
                    ></ui-slider>

                    <ui-slider
                            v-if="factory.node"
                            icon="fa-square black-text"
                            label="Taxa de falecimento"
                            :val-min.sync="factory.node.rate.death.min"
                            :val-max.sync="factory.node.rate.death.max"
                            step="0.5"
                            min="0"
                            max="100"
                            decimals="1"
                            postfix="%"
                            :range="true"
                    ></ui-slider>

                </div>
            </li>

            <li class="grey lighten-5">
                <div class="collapsible-header">Arestas</div>
                <div class="collapsible-body pl pr">

                    <div v-if="mode == 'visual'">
                        <ui-slider
                                v-if="factory.edge"
                                name="factory[edge]"
                                label="Quantidade (mínimo e máximo) por grupo"
                                :val-min.sync="factory.edge.min"
                                :val-max.sync="factory.edge.max"
                                step="1"
                                min="0"
                                max="400"
                                decimals="0"
                                :range="true"
                        ></ui-slider>
                        <ui-slider
                                v-if="factory.edge"
                                label="Máximo de arestas por nó"
                                :val.sync="factory.edge.density"
                                step="1"
                                min="2"
                                max="12"
                                decimals="0"
                        ></ui-slider>
                    </div>

                    <div v-if="mode == 'scientific'">
                        <ui-slider
                                v-if="factory.edge"
                                name="factory[edge]"
                                label="Quantidade (mínimo e máximo)"
                                :val-min.sync="factory.edge.min"
                                :val-max.sync="factory.edge.max"
                                step="1"
                                min="0"
                                max="2000"
                                decimals="0"
                                :range="true"
                        ></ui-slider>
                        <ui-slider
                                v-if="factory.edge"
                                label="Máximo de arestas por nó"
                                :val.sync="factory.edge.density"
                                step="1"
                                min="3"
                                max="15"
                                decimals="0"
                        ></ui-slider>
                    </div>

                    <ui-slider
                            v-if="factory.edge"
                            icon="fa-square red-text"
                            name="factory[edge][rate][infect]"
                            label="Taxa de infecção"
                            :val-min.sync="factory.edge.rate.infect.min"
                            :val-max.sync="factory.edge.rate.infect.max"
                            step="0.5"
                            min="0"
                            max="100"
                            decimals="1"
                            postfix="%"
                            :range="true"
                    ></ui-slider>

                </div>
            </li>
        </ul>

    </div>

</template>

<script>
    export default {
        props: ['factory', 'mode'],

        computed: {
            groupInfected() {
                if (!this.factory.node) return {};

                for (var i in this.factory.node.groups) {
                    var group = this.factory.node.groups[i];
                    if (group.ref == 'i') return group;
                }
            },

            groupVaccinated() {
                if (!this.factory.node) return {};

                for (var i in this.factory.node.groups) {
                    var group = this.factory.node.groups[i];
                    if (group.ref == 'v') return group;
                }
            }

        },

        ready() {
            $(this.$el).find('.collapsible').collapsible();
        }
    }
</script>