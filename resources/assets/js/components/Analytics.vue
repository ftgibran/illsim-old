<template>
    <div id="bmodal" class="modal">
        <nav class="blue-grey darken-2 white-text pl pr nav-extended">
            <div class="nav-wrapper">
                <ul>
                    <li>
                        <a @click="$root.$refs.sidenav.show()"
                           class="btn-floating btn-large blue-grey lighten-5 waves-effect waves-light">
                            <i class="black-text material-icons">settings</i>
                        </a>
                    </li>
                    <li v-if="$root.$refs.network">
                        <a v-if="$root.$refs.network.state == null"
                           @click="$root.$refs.config.simulate()"
                        >
                            <i class="material-icons left">play_arrow</i>
                            <span class="fw-b">Simular</span>
                        </a>
                        <a v-if="$root.$refs.network.state != null"
                           @click="$root.$refs.config.simulate()"
                        >
                            <i class="material-icons left">refresh</i>
                            <span class="fw-b">Reset</span>
                        </a>
                    </li>
                    <li>
                        <a v-if="$parent.state == 'paused'" @click="$parent.play()">
                            <i class="material-icons left">play_arrow</i>
                            <span class="fw-b">Play</span>
                        </a>
                        <a v-if="$parent.state == 'playing'" @click="$parent.stop()">
                            <i class="material-icons left">pause</i>
                            <span class="fw-b">Pausar</span>
                        </a>
                    </li>
                    <li v-if="$root.$refs.network">
                        <a @click="saveAsCsv()"
                           v-if="$root.$refs.network.state == 'playing' || $root.$refs.network.state == 'paused'">
                            <i class="material-icons left">file_download</i>
                            <span class="fw-b">Salvar dados</span>
                        </a>
                    </li>
                </ul>
                <div class="right">
                    <a class="modal-action modal-close btn blue-grey lighten-5 black-text waves-effect waves-light">
                        Network
                    </a>
                </div>
            </div>
        </nav>

        <div class="variables">
            <ui-loader :lazy="true">
                <ul class="flex">
                    <li v-for="item in display" v-show="item" class="chip">
                        <i class="fa fa-square {{item.color}}-text" aria-hidden="true"></i>
                        {{item.label}}
                        <span class="badge" style="width: 4em;">
                            {{this[item.value]}}
                        </span>
                    </li>
                </ul>
            </ui-loader>
        </div>

        <div id="editor"></div>
        <div id="graph"></div>

    </div>
</template>

<style lang="sass" rel="stylesheet/scss">

    .variables {
        height: 10em;

        .flex {
            display: flex;
            justify-content: space-around;
            flex-wrap: nowrap;
            margin: 10px auto;
            .chip {
                .badge {
                    margin: 4px auto;
                }
            }
        }
    }

    .modal {
        top: 0 !important;
        bottom: 0;
        width: 100%;
        max-height: 100%;
    }

    .vis-timeline {
        position: absolute;
        top: 115px;
        bottom: 0;
        left: 0;
        right: 0;
    }

    .infected {
        fill: #D46A6A;
        fill-opacity: 0;
        stroke-width: 2px;
        stroke: #D46A6A;
    }

    .recovered {
        fill: #ECC13E;
        fill-opacity: 0;
        stroke-width: 2px;
        stroke: #ECC13E;
    }

    .susceptible {
        fill: #b0bec5;
        fill-opacity: 0;
        stroke-width: 2px;
        stroke: #b0bec5;
    }

    .vaccinated {
        fill: #85BC5E;
        fill-opacity: 0;
        stroke-width: 2px;
        stroke: #85BC5E;
    }

    .death {
        fill: #666666;
        fill-opacity: 0;
        stroke-width: 2px;
        stroke: #666666;
    }

</style>

<script>

    import _ from 'lodash';
    import csv from '../functions/csv';
    import moment from 'moment';
    import 'moment/locale/pt-br';
    moment.locale('pt-BR');

    const Analytics = {
        data: new vis.DataSet(),
        group: new vis.DataSet(),
        currentDate: null,
        inoculateDate: null,
        population: null,
        graph2d: null,
        config: null
    };

    export default {
        data() {
            return {
                display: []
            }
        },

        computed: {
            infected() {
                var display = this.$parent['infected'];
                return this.displayValue(display);
            },
            infected_n_recovered() {
                var display = this.$parent['infected'] +
                    this.$parent['recovered'];
                return this.displayValue(display);
            },
            recovered() {
                var display = this.$parent['recovered'];
                return this.displayValue(display);
            },
            recovered_n_vaccinated() {
                var display = this.$parent['recovered'] +
                    this.$parent['vaccinated'];
                return this.displayValue(display);
            },
            alive() {
                var display = this.$parent['infected'] +
                    this.$parent['recovered'] +
                    this.$parent['susceptible'] +
                    this.$parent['vaccinated'];
                return this.displayValue(display);
            },
            susceptible() {
                var display = this.$parent['susceptible'];
                return this.displayValue(display);
            },
            susceptible_n_vaccinated() {
                var display = this.$parent['susceptible'] +
                    this.$parent['vaccinated'];
                return this.displayValue(display);
            },
            susceptible_n_recovered() {
                var display = this.$parent['susceptible'] +
                    this.$parent['recovered'];
                return this.displayValue(display);
            },
            vaccinated() {
                var display = this.$parent['vaccinated'];
                return this.displayValue(display);
            },
            healthy() {
                var display = this.$parent['recovered'] +
                    this.$parent['susceptible'] +
                    this.$parent['vaccinated'];
                return this.displayValue(display);
            },
            death() {
                var display = this.$parent['death'];
                return this.displayValue(display);
            }
        },

        methods: {
            open() {
                $('#bmodal').modal('open');
            },

            close() {
                $('#bmodal').modal('close');
            },

            saveAsCsv() {
                this.$parent.stop();

                var data = {};

                Analytics.group.forEach((group) => {
                    var content = Analytics.data.get({filter: (item) => item.group === group.id});
                    data[group.type] = _.map(content, (item) => item['y']);
                });

                var list = _.zip(..._.values(data));

                list = list.map(item => {
                    var result = {};
                    Analytics.group.forEach((group, index) => {
                        result[group.type] = item[index];
                    });
                    return result;
                });

                csv('graph.csv', list, {});
            },

            saveAsJson() {
                this.$parent.stop();

                var data = {};

                Analytics.group.forEach((group) => {
                    var content = Analytics.data.get({filter: (item) => item.group === group.id});
                    data[group.type] = _.map(content, (item) => item['y']);
                });

                this.download(data, 'graph.json');
            },

            download(data, filename) {
                if (!data) {
                    console.error('No data');
                    return;
                }

                if (!filename) filename = 'console.json';

                if (typeof data === "object") {
                    data = JSON.stringify(data, undefined, 4)
                }

                var blob = new Blob([data], {type: 'text/json'}),
                    e = document.createEvent('MouseEvents'),
                    a = document.createElement('a');

                a.download = filename;
                a.href = window.URL.createObjectURL(blob);
                a.dataset.downloadurl = ['text/json', a.download, a.href].join(':');
                e.initMouseEvent('click', true, false, window, 0, 0, 0, 0, 0, false, false, false, false, 0, null);
                a.dispatchEvent(e);
            },

            init(config) {
                Analytics.config = config;
            },

            normalize() {
                switch (Analytics.config.variables.display[0]) {
                    case 'infected':
                        this.display[0] = {
                            color: 'red',
                            label: 'Infectados',
                            value: 'infected'
                        };
                        break;
                    case 'infected_n_recovered':
                        this.display[0] = {
                            color: 'red',
                            label: 'Infectados & Recuperados',
                            value: 'infected_n_recovered'
                        };
                        break;
                }

                switch (Analytics.config.variables.display[1]) {
                    case 'recovered':
                        this.display[1] = {
                            color: 'yellow',
                            label: 'Recuperados',
                            value: 'recovered'
                        };
                        break;
                    case 'recovered_n_vaccinated':
                        this.display[1] = {
                            color: 'yellow',
                            label: 'Recuperados & Vacinados',
                            value: 'recovered_n_vaccinated'
                        };
                        break;
                    case 'alive':
                        this.display[1] = {
                            color: 'yellow',
                            label: 'Vivos',
                            value: 'alive'
                        };
                        break;
                }

                switch (Analytics.config.variables.display[2]) {
                    case 'susceptible':
                        this.display[2] = {
                            color: 'grey',
                            label: 'Suscetíveis',
                            value: 'susceptible'
                        };
                        break;
                    case 'susceptible_n_vaccinated':
                        this.display[2] = {
                            color: 'grey',
                            label: 'Suscetíveis & Vacinados',
                            value: 'susceptible_n_vaccinated'
                        };
                        break;
                    case 'susceptible_n_recovered':
                        this.display[2] = {
                            color: 'grey',
                            label: 'Suscetíveis & Recuperados',
                            value: 'susceptible_n_recovered'
                        };
                        break;
                }

                switch (Analytics.config.variables.display[3]) {
                    case 'vaccinated':
                        this.display[3] = {
                            color: 'green',
                            label: 'Vacinados',
                            value: 'vaccinated'
                        };
                        break;
                    case 'healthy':
                        this.display[3] = {
                            color: 'green',
                            label: 'Saudáveis',
                            value: 'healthy'
                        };
                        break;
                }

                switch (Analytics.config.variables.display[4]) {
                    case 'death':
                        this.display[4] = {
                            color: 'black',
                            label: 'Falecidos',
                            value: 'death'
                        };
                        break;
                }
            },

            setGroups() {
                if (this.display[0])
                    Analytics.group.add({
                        id: 0,
                        type: this.display[0].value,
                        content: this.display[0].label,
                        className: 'infected',
                        options: {shaded: true}
                    });

                if (this.display[1])
                    Analytics.group.add({
                        id: 1,
                        type: this.display[1].value,
                        content: this.display[1].label,
                        className: 'recovered'
                    });

                if (this.display[2])
                    Analytics.group.add({
                        id: 2,
                        type: this.display[2].value,
                        content: this.display[2].label,
                        className: 'susceptible'
                    });

                if (this.display[3])
                    Analytics.group.add({
                        id: 3,
                        type: this.display[3].value,
                        content: this.display[3].label,
                        className: 'vaccinated',
                        options: {shaded: true}
                    });

                if (this.display[4])
                    Analytics.group.add({
                        id: 4,
                        type: this.display[4].value,
                        content: this.display[4].label,
                        className: 'death'
                    });

            },

            options() {
                var start = moment();
                var end = moment().add(Analytics.config.length.value, Analytics.config.length.unit);
                return {
                    start: start.format('YYYY-MM-DD HH:mm:ss'),
                    end: end.format('YYYY-MM-DD HH:mm:ss'),
                    legend: {
                        enabled: true,
                        left: {
                            position: 'top-right'
                        }
                    },
                    autoResize: true,
                    drawPoints: false,
                    moveable: false,
                    zoomable: false,
                    height: 'auto',
                    moment: moment
                };
            },

            step() {
                if (this.display[0])
                    Analytics.data.add({x: Analytics.currentDate, y: this[this.display[0].value], group: 0});

                if (this.display[1])
                    Analytics.data.add({x: Analytics.currentDate, y: this[this.display[1].value], group: 1});

                if (this.display[2])
                    Analytics.data.add({x: Analytics.currentDate, y: this[this.display[2].value], group: 2});

                if (this.display[3])
                    Analytics.data.add({x: Analytics.currentDate, y: this[this.display[3].value], group: 3});

                if (this.display[4])
                    Analytics.data.add({x: Analytics.currentDate, y: this[this.display[4].value], group: 4});

                //Next x-axis
                Analytics.currentDate = moment(Analytics.currentDate).add(1, Analytics.config.step);

                this.checkInoculateDelay();
            },

            checkInoculateDelay() {
                if (!Analytics.config.inoculationDelay.enabled)
                    return;

                if (!this.$parent.mayInoculate)
                    if (Analytics.inoculateDate.isBefore(Analytics.currentDate))
                        this.$parent.mayInoculate = true;
            },

            displayValue(value) {
                if (this.isRelative())
                    return _.round(Number(value) / Number(Analytics.population),
                        Analytics.config.variables.relative.precision);

                return value;
            },

            isRelative() {
                if (Analytics.config && Analytics.config.variables.relative.enabled)
                    return true;
                return false;
            }

        },

        events: {
            load(population) {
                this.normalize();
                this.setGroups();

                Analytics.currentDate = moment();
                Analytics.inoculateDate = moment();
                Analytics.population = population;

                if (Analytics.config.inoculationDelay.enabled) {
                    this.$parent.mayInoculate = false;
                    Analytics.inoculateDate = moment().add(Analytics.config.inoculationDelay.value, Analytics.config.inoculationDelay.unit);
                }

                var container = document.getElementById('graph');
                Analytics.graph2d = new vis.Graph2d(container, Analytics.data, Analytics.group, this.options());
            },

            reset() {
                if (Analytics.graph2d)
                    Analytics.graph2d.destroy();

                Analytics.data.clear();
                Analytics.group.clear();
                Analytics.currentDate = null;
                Analytics.inoculateDate = null;
                Analytics.population = null;
                Analytics.config = null;
                this.display = [];

                this.$parent.$off('step');
            }
        },

        ready() {
            $('.modal').modal();
        }

    }
</script>