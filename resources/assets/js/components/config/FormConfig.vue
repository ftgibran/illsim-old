<template>
    <ui-loader :on-load="init">
        <nav class="blue-grey darken-2 white-text pl pr nav-extended">
            <div class="nav-wrapper">
                <div class="left">
                    <a @click="$parent.hide()"
                       class="btn-floating btn-large blue-grey lighten-5 waves-effect waves-light"><i
                            class="black-text material-icons">arrow_back</i></a>
                </div>
                <ul>
                    <li>
                        <a @click="upload()">
                            <i class="material-icons left">file_upload</i>
                            <span class="fw-b">Carregar</span>
                        </a>
                        <input id="file" type="file" style="display: none;"/>
                    </li>
                    <li>
                        <a @click="download(config, 'config.json')">
                            <i class="material-icons left">file_download</i>
                            <span class="fw-b">Salvar</span>
                        </a>
                    </li>
                </ul>
                <div class="right">
                    <button type="button" @click="simulate()"
                            class="blue-grey lighten-5 black-text fw-b btn-large">
                        Simular
                    </button>
                </div>
            </div>

            <div class="nav-content">
                <ul class="tabs tabs-transparent tabs-fixed-width">
                    <li class="tab"><a href="#animation">
                        <i class="material-icons">timelapse</i>
                    </a></li>
                    <li class="tab"><a href="#simulation" class="active">
                        <i class="material-icons">tune</i>
                    </a></li>
                    <li class="tab"><a href="#generator">
                        <i class="material-icons">grain</i>
                    </a></li>
                    <li class="tab"><a href="#analytics">
                        <i class="material-icons">timeline</i>
                    </a></li>
                </ul>
            </div>
        </nav>

        <div id="animation" class="ml mr mt mb">
            <h2 class="mt-s ta-c sahitya">Animação</h2>

            <form-config-animation
                    :config="config.animation"
                    :mode.sync="config.simulation.mode"
            ></form-config-animation>
        </div>

        <div id="simulation" class="ml mr mt mb">
            <h2 class="mt-s ta-c sahitya">Simulação</h2>

            <form-config-simulation
                    :config="config.simulation"
            ></form-config-simulation>
        </div>

        <div id="generator" class="ml mr mt mb">
            <h2 class="mt-s ta-c sahitya">Gerador</h2>

            <form-config-generator
                    :config="config.generator"
                    :mode.sync="config.simulation.mode"
                    :infect-by.sync="config.simulation.infectBy"
            ></form-config-generator>
        </div>

        <div id="analytics" class="ml mr mt mb">
            <h2 class="mt-s ta-c sahitya">Analytics</h2>

            <form-config-analytics
                    :config="config.analytics"
            ></form-config-analytics>
        </div>

    </ui-loader>
</template>

<script>
    export default {

        data() {
            return {
                config: {}
            };
        },

        methods: {

            init() {
                $('ul.tabs').tabs();

                var onReaderLoad = (event) => {
                    this.config = JSON.parse(event.target.result);

                    this.$emit('load');

                    Materialize.toast('Configurações carregadas com sucesso!', 4000);
                };

                var onChange = (event) => {
                    this.$emit('reset');

                    var reader = new FileReader();
                    reader.onload = onReaderLoad;
                    reader.readAsText(event.target.files[0]);
                };

                document.getElementById('file').addEventListener('change', onChange);
            },

            simulate() {
                this.$root.$emit('simulate', this.config);
            },

            upload() {
                var e = document.createEvent('MouseEvents');
                e.initMouseEvent('click', true, false, window, 0, 0, 0, 0, 0, false, false, false, false, 0, null);
                document.getElementById('file').dispatchEvent(e);
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
            }
        },

        ready() {
            $.getJSON('api/getConfig', (data) => {
                this.config = data;
                this.$emit('load');
            });
        }
    }
</script>