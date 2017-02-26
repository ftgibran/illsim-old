<template>
    <div id="bmodal" class="modal">
        <nav class="blue-grey darken-2 white-text pl pr nav-extended">
            <div class="nav-wrapper">
                <ul>
                    <li>
                        <a @click="$root.$refs.config.simulate()">
                            <i class="material-icons left">replay</i>
                            Simular
                        </a>
                    </li>
                    <li>
                        <a>
                            <i class="fa fa-pause" aria-hidden="true"></i>
                            Pausar
                        </a>
                    </li>
                    <li>
                        <a>
                            <i class="fa fa-cloud-download" aria-hidden="true"></i>
                            Salvar Gráfico
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

        <ui-loader :lazy="true"></ui-loader>
        <div id="graph"></div>
    </div>
</template>

<style lang="sass" rel="stylesheet/scss">
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

    .modal {
        top: 0 !important;
        bottom: 0;
        width: 100%;
        max-height: 100%;
    }

    .vis-timeline {
        position: absolute;
        top: 4em;
        bottom: 0;
        left: 0;
        right: 0;
    }
</style>

<script>

    import moment from 'moment';
    import 'moment/locale/pt-br';
    moment.locale('pt-BR');

    const Analytics = {
        data: new vis.DataSet(),
        currentDate: new Date()
    };

    export default {
        data() {
            return {}
        },

        methods: {
            open() {
                $('#bmodal').modal('open');
            },

            close() {
                $('#bmodal').modal('close');
            },

            init() {
                $('.modal').modal();

                Date.prototype.addDays = function (days) {
                    var dat = new Date(this.valueOf());
                    dat.setDate(dat.getDate() + days);
                    return dat;
                };
            },

            groups() {
                var groups = new vis.DataSet();
                groups.add({
                    id: 1,
                    content: 'Infectados',
                    className: 'infected',
                    options: {
                        shaded: true
                    }
                });
                groups.add({
                    id: 2,
                    content: 'Recuperados',
                    className: 'recovered',
                    options: {}
                });
                groups.add({
                    id: 3,
                    content: 'Suscetíveis',
                    className: 'susceptible',
                    options: {}
                });
                groups.add({
                    id: 4,
                    content: 'Vacinados',
                    className: 'vaccinated',
                    options: {
                        shaded: true
                    }
                });
                groups.add({
                    id: 5,
                    content: 'Falecidos',
                    className: 'death',
                    options: {}
                });
                return groups;
            },

            options() {
                var start = moment().format('YYYY-MM-DD');
                var end = moment().add(2, 'years').format('YYYY-MM-DD');
                return {
                    start: start,
                    end: end,
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

            add(date, status) {
                Analytics.data.add({
                    x: date,
                    y: status.infected,
                    group: 1
                });
                Analytics.data.add({
                    x: date,
                    y: status.recovered,
                    group: 2
                });
                Analytics.data.add({
                    x: date,
                    y: status.susceptible + status.vaccinated,
                    group: 3
                });
//                Analytics.data.add({
//                    x: date,
//                    y: status.vaccinated,
//                    group: 4
//                });
                Analytics.data.add({
                    x: date,
                    y: status.death,
                    group: 5
                });
            },

            loaded() {
                var container = document.getElementById('graph');
                new vis.Graph2d(container, Analytics.data, this.groups(), this.options());

                this.$parent.$on('step', (status) => {
                    this.add(Analytics.currentDate, status);
                    Analytics.currentDate = Analytics.currentDate.addDays(1);
                });

                this.$emit('load');
            },

            reseted() {
                Analytics.data.clear();
                Analytics.currentDate = new Date();
                document.getElementById('graph').innerHTML = "";
                this.$parent.$on('step', () => {});

                this.$emit('reset');
            }
        },

        ready() {
            this.init();

            this.$parent.$on('load', this.loaded);
            this.$parent.$on('reset', this.reseted);
        }
    }
</script>