<template>

    <ul class="collapsible popout" data-collapsible="accordion">

        <li class="card-panel blue-grey darken-2 white-text mt-0 mb-0 pt-s pb-s ta-l">
            Full Random
        </li>

        <li class="grey lighten-5">
            <div class="collapsible-header">Nós</div>
            <div class="collapsible-body pl pr">

                <ui-slider
                        v-if="factory.node"
                        label="Quantidade (mínimo e máximo)"
                        :val-min.sync="factory.node.min"
                        :val-max.sync="factory.node.max"
                        step="1"
                        min="0"
                        max="200"
                        decimals="0"
                        :range="true"
                ></ui-slider>

                <div v-if="factory.node" class="row">
                    <div class="input-field s12 m6 col">
                        <div class="fl-r">
                            <input type="checkbox" class="filled-in" id="i-percent"
                                   v-model="groupInfect.percent"/>
                            <label for="i-percent">%</label>
                        </div>
                        <div style="overflow: hidden;">
                            <label class="active truncate" for="i-quant">
                                <i class="fa fa-square red-text" aria-hidden="true"></i>
                                Quantidade de infectados</label>
                            <input type="number" id="i-quant"
                                   v-model="groupInfect.quant" required>
                        </div>

                        <div class="cl-b"></div>
                    </div>

                    <div class="input-field s12 m6 col">
                        <div class="fl-r">
                            <input type="checkbox" class="filled-in" id="v-percent"
                                   v-model="groupVacinated.percent"/>
                            <label for="v-percent">%</label>
                        </div>
                        <div style="overflow: hidden;">
                            <label class="active truncate" for="v-quant">
                                <i class="fa fa-square green-text" aria-hidden="true"></i>
                                Quantidade de vacinados</label>
                            <input type="number" id="v-quant"
                                   v-model="groupVacinated.quant"
                                   required>
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

                <ui-slider
                        v-if="factory.edge"
                        name="factory[edge]"
                        label="Quantidade (mínimo e máximo)"
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
                        min="0"
                        max="20"
                        decimals="0"
                ></ui-slider>


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

</template>

<script>
    export default {
        props: ['factory'],

        computed: {
            groupInfect() {
                if(!this.factory.node) return {};

                for(var i in this.factory.node.groups)
                {
                    var group = this.factory.node.groups[i];
                    if(group.ref == 'i') return group;
                }
            },

            groupVacinated() {
                if(!this.factory.node) return {};

                for(var i in this.factory.node.groups)
                {
                    var group = this.factory.node.groups[i];
                    if(group.ref == 'v') return group;
                }
            }

        },

        ready() {
            $(this.$el).collapsible();
        }
    }
</script>