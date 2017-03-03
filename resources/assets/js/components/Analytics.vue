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
                        <a @click="saveAsImage()"
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
                    <li v-for="item in variables" v-show="item" class="chip">
                        <i class="fa fa-square {{item.color}}-text" aria-hidden="true"></i>
                        {{item.label}}
                        <span class="badge">{{this[item.value]}}</span>
                    </li>
                </ul>
            </ui-loader>
        </div>

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

    import moment from 'moment';
    import 'moment/locale/pt-br';
    moment.locale('pt-BR');

    const Analytics = {
        data: new vis.DataSet(),
        currentDate: new Date(),
        graph2d: null,
        config: null
    };

    export default {
        data() {
            return {
                variables: []
            }
        },

        computed: {
            infected() {
                return this.$parent['infected'];
            },
            infected_n_recovered() {
                return this.$parent['infected'] + this.$parent['recovered'];
            },
            recovered() {
                return this.$parent['recovered'];
            },
            alive() {
                return this.$parent['infected'] + this.$parent['recovered'] + this.$parent['susceptible'] + this.$parent['vaccinated'];
            },
            susceptible() {
                return this.$parent['susceptible'];
            },
            susceptible_n_vaccinated() {
                return this.$parent['susceptible'] + this.$parent['vaccinated'];
            },
            susceptible_n_recovered() {
                return this.$parent['susceptible'] + this.$parent['recovered'];
            },
            vaccinated() {
                return this.$parent['vaccinated'];
            },
            healthy() {
                return this.$parent['recovered'] + this.$parent['susceptible'] + this.$parent['vaccinated'];
            },
            death() {
                return this.$parent['death'];
            }
        },

        methods: {
            open() {
                $('#bmodal').modal('open');
            },

            close() {
                $('#bmodal').modal('close');
            },

            saveAsImage() {
            },

            init(config) {
                Analytics.config = config;
            },

            normalize() {
                switch (Analytics.config.variables[0]) {
                    case 'infected':
                        this.variables[0] = {
                            color: 'red',
                            label: 'Infectados',
                            value: 'infected'
                        };
                        break;
                    case 'infected_n_recovered':
                        this.variables[0] = {
                            color: 'red',
                            label: 'Infectados & Recuperados',
                            value: 'infected_n_recovered'
                        };
                        break;
                }

                switch (Analytics.config.variables[1]) {
                    case 'recovered':
                        this.variables[1] = {
                            color: 'yellow',
                            label: 'Recuperados',
                            value: 'recovered'
                        };
                        break;
                    case 'alive':
                        this.variables[1] = {
                            color: 'yellow',
                            label: 'Vivos',
                            value: 'alive'
                        };
                        break;
                }

                switch (Analytics.config.variables[2]) {
                    case 'susceptible':
                        this.variables[2] = {
                            color: 'grey',
                            label: 'Suscetíveis',
                            value: 'susceptible'
                        };
                        break;
                    case 'susceptible_n_vaccinated':
                        this.variables[2] = {
                            color: 'grey',
                            label: 'Suscetíveis & Vacinados',
                            value: 'susceptible_n_vaccinated'
                        };
                        break;
                    case 'susceptible_n_recovered':
                        this.variables[2] = {
                            color: 'grey',
                            label: 'Suscetíveis & Recuperados',
                            value: 'susceptible_n_recovered'
                        };
                        break;
                }

                switch (Analytics.config.variables[3]) {
                    case 'vaccinated':
                        this.variables[3] = {
                            color: 'green',
                            label: 'Vacinados',
                            value: 'vaccinated'
                        };
                        break;
                    case 'healthy':
                        this.variables[3] = {
                            color: 'green',
                            label: 'Saudáveis',
                            value: 'healthy'
                        };
                        break;
                }

                switch (Analytics.config.variables[4]) {
                    case 'death':
                        this.variables[4] = {
                            color: 'black',
                            label: 'Falecidos',
                            value: 'death'
                        };
                        break;
                }
            },

            groups() {
                var groups = new vis.DataSet();

                if (this.variables[0])
                    groups.add({
                        id: 0,
                        content: this.variables[0].label,
                        className: 'infected',
                        options: {shaded: true}
                    });

                if (this.variables[1])
                    groups.add({
                        id: 1,
                        content: this.variables[1].label,
                        className: 'recovered'
                    });

                if (this.variables[2])
                    groups.add({
                        id: 2,
                        content: this.variables[2].label,
                        className: 'susceptible'
                    });

                if (this.variables[3])
                    groups.add({
                        id: 3,
                        content: this.variables[3].label,
                        className: 'vaccinated',
                        options: {shaded: true}
                    });

                if (this.variables[4])
                    groups.add({
                        id: 4,
                        content: this.variables[4].label,
                        className: 'death'
                    });

                return groups;
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
                if (this.variables[0])
                    Analytics.data.add({x: Analytics.currentDate, y: this[this.variables[0].value], group: 0});

                if (this.variables[1])
                    Analytics.data.add({x: Analytics.currentDate, y: this[this.variables[1].value], group: 1});

                if (this.variables[2])
                    Analytics.data.add({x: Analytics.currentDate, y: this[this.variables[2].value], group: 2});

                if (this.variables[3])
                    Analytics.data.add({x: Analytics.currentDate, y: this[this.variables[3].value], group: 3});

                if (this.variables[4])
                    Analytics.data.add({x: Analytics.currentDate, y: this[this.variables[4].value], group: 4});

                //Next x-axis
                Analytics.currentDate = moment(Analytics.currentDate).add(1, Analytics.config.step);
            }

        },

        events: {
            load() {
                this.normalize();

                var container = document.getElementById('graph');
                Analytics.graph2d = new vis.Graph2d(container, Analytics.data, this.groups(), this.options());
            },

            reset() {
                if (Analytics.graph2d)
                    Analytics.graph2d.destroy();

                Analytics.data.clear();
                Analytics.currentDate = new Date();
                Analytics.config = null;
                this.variables = [];

                this.$parent.$off('step');
            }
        },

        ready() {
            $('.modal').modal();
        }

    }
</script>