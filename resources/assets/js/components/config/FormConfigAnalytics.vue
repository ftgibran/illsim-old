<template>
    <div>

        <ui-select label="Unidade por passo de tempo" :options="step" :val.sync="config.step"></ui-select>

        <div class="row">
            <div class="input-field col s6">
                <label class="active truncate" for="value">Duração máxima</label>
                <input type="number" id="value"
                       min="0"
                       max="100"
                       v-model="config.length.value">
            </div>

            <ui-select class="col s6" label="Unidade" :options="unit" :val.sync="config.length.unit"></ui-select>
        </div>

        <ul class="collection" style="overflow: visible;">
            <li class="row collection-item">

                <ui-checkbox :checked.sync="config.inoculationDelay.enabled"
                             name="config.inoculationDelay.enabled"
                             label='Atrasar inoculação'
                >
                    <div class="row">
                        <div class="input-field col s6">
                            <label class="active truncate" for="delay"></label>
                            <input type="number" id="delay"
                                   min="0"
                                   max="100"
                                   v-model="config.inoculationDelay.value">
                        </div>

                        <ui-select class="col s6" label="Unidade" :options="unit" :val.sync="config.inoculationDelay.unit"></ui-select>
                    </div>
                </ui-checkbox>

            </li>
        </ul>

        <ul class="collection with-header" style="overflow: visible;">

            <li class="collection-header"><h4 class="mt-0 mb-0 ta-c">Variáveis</h4></li>

            <li class="row collection-item">

                <ui-checkbox :checked.sync="config.variables.relative.enabled"
                             name="config.variables.relative.enabled"
                             label='Exibição relacional (%)'
                >
                    <ui-slider v-if="config.variables"
                               label="Precisão"
                               :val.sync="config.variables.relative.precision"
                               step="1"
                               min="2"
                               max="5"
                    ></ui-slider>
                </ui-checkbox>

            </li>

            <li class="row collection-item">
                <ui-select class="col s6"
                           icon="fa-square red-text"
                           label="Variável 1"
                           :options="variable1"
                           :val.sync="config.variables.display[0]">
                </ui-select>
                <ui-select class="col s6"
                           icon="fa-square yellow-text"
                           label="Variável 2"
                           :options="variable2"
                           :val.sync="config.variables.display[1]">
                </ui-select>
                <ui-select class="col s6"
                           icon="fa-square grey-text"
                           label="Variável 3"
                           :options="variable3"
                           :val.sync="config.variables.display[2]">
                </ui-select>
                <ui-select class="col s6"
                           icon="fa-square green-text"
                           label="Variável 4"
                           :options="variable4"
                           :val.sync="config.variables.display[3]">
                </ui-select>
                <ui-select class="col s6"
                           icon="fa-square black-text"
                           label="Variável 5"
                           :options="variable5"
                           :val.sync="config.variables.display[4]">
                </ui-select>
            </li>
        </ul>

    </div>
</template>

<script>
    export default {

        data() {
            return {
                step: [
                    {"label": "Segundos", "val": "seconds"},
                    {"label": "Minutos", "val": "minutes"},
                    {"label": "Horas", "val": "hours"},
                    {"label": "Dias", "val": "days"},
                    {"label": "Semanas", "val": "weeks"},
                    {"label": "Mêses", "val": "months"}
                ],
                unit: [
                    {"label": "Segundos", "val": "seconds"},
                    {"label": "Minutos", "val": "minutes"},
                    {"label": "Horas", "val": "hours"},
                    {"label": "Dias", "val": "days"},
                    {"label": "Semanas", "val": "weeks"},
                    {"label": "Mêses", "val": "months"},
                    {"label": "Anos", "val": "years"}
                ],
                variable1: [
                    {"label": "Vazio", "val": "none"},
                    {"label": "Infectados", "val": "infected"}, //Infected
                    {"label": "Infectados & Recuperados", "val": "infected_n_recovered"} //Infected + Recovered
                ],
                variable2: [
                    {"label": "Vazio", "val": "none"},
                    {"label": "Recuperados", "val": "recovered"}, //Recovered
                    {"label": "Vivos", "val": "alive"} //Susceptible + Vaccinated + Recovered + Infected
                ],
                variable3: [
                    {"label": "Vazio", "val": "none"},
                    {"label": "Suscetíveis", "val": "susceptible"}, //Susceptible
                    {"label": "Suscetíveis & Vacinados", "val": "susceptible_n_vaccinated"}, //Susceptible + Vaccinated
                    {"label": "Suscetíveis & Recuperados", "val": "susceptible_n_recovered"}, //Susceptible + Recovered
                ],
                variable4: [
                    {"label": "Vazio", "val": "none"},
                    {"label": "Vacinados", "val": "vaccinated"}, //Vaccinated
                    {"label": "Saudáveis", "val": "healthy"}, //Susceptible + Vaccinated + Recovered
                ],
                variable5: [
                    {"label": "Vazio", "val": "none"},
                    {"label": "Falecidos", "val": "death"} //Death
                ]
            };
        },

        props: ['config'],

        ready() {
        }
    }
</script>