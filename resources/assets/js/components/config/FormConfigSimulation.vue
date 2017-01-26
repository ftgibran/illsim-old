<template>
    <div class="collapsible-header">Simulação</div>
    <div v-if="config" class="collapsible-body pl pr">

        <ui-slider
                label="Intervalo de tempo (ms)"
                :val.sync="config.step"
                step="50"
                min="0"
                max="2000"
                decimals="0"
        ></ui-slider>

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

        <ul class="collection z-depth-1 mb">
            <li class="collection-header" style="border-bottom: 1px solid #ccc;">
                <h5><i class="fa fa-square red-text" aria-hidden="true"></i> Infectados</h5>
            </li>

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
        <ul class="collection z-depth-1 mb">
            <li class="collection-header" style="border-bottom: 1px solid #ccc;">
                <h5><i class="fa fa-square yellow-text" aria-hidden="true"></i> Recuperados</h5>
            </li>

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
                             label='Podem ser infectado'>
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

        <ul class="collection z-depth-1 mb">
            <li class="collection-header" style="border-bottom: 1px solid #ccc;">
                <h5><i class="fa fa-square grey-text" aria-hidden="true"></i> Suscetíveis</h5>
            </li>

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
                             label='Podem ser infectado'>
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

        <ul class="collection z-depth-1 mb">
            <li class="collection-header" style="border-bottom: 1px solid #ccc;">
                <h5><i class="fa fa-square green-text" aria-hidden="true"></i> Vacinados</h5>
            </li>

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

        <ul class="collection z-depth-1 mb">
            <li class="collection-header" style="border-bottom: 1px solid #ccc;">
                <h5><i class="fa fa-square black-text" aria-hidden="true"></i> Mortos</h5>
            </li>

            <li class="collection-item">
                <ui-checkbox :checked.sync="config.d.birthWhenDie"
                             label='Nascimento após morte' name='config.d.birthWhenDie'></ui-checkbox>
            </li>
        </ul>

    </div>
</template>

<script>
    export default {

        data() {
            return {
                infectBy: [
                    {"label": "Nó", "val": "node"},
                    {"label": "Aresta", "val": "edge"},
                    {"label": "Nó e Aresta", "val": "both"},
                    {"label": "Fórmula [1-exp(-k*n)]", "val": "special"}
                ]
            };
        },

        props: ['config'],

        ready() {
        }
    }
</script>