<template>
    <div>
        <nav class="blue-grey darken-2 white-text pl pr nav-extended">
            <div class="nav-wrapper">
                <div class="left">
                    <a @click="this.$parent.$parent.$refs.sidenav.hide()"
                       class="btn-floating btn-large waves-effect waves-light"><i
                            class="material-icons">settings</i></a>
                </div>
                <ul>
                    <li>
                        <a @click="load()">
                            <i class="fa fa-cloud-upload" aria-hidden="true"></i>
                            Carregar
                        </a>
                        <input id="file" type="file" style="display: none;"/>
                    </li>
                    <li>
                        <a @click="save(config, 'config.json')">
                            <i class="fa fa-cloud-download" aria-hidden="true"></i>
                            Salvar
                        </a>
                    </li>
                </ul>
                <div class="right">
                    <button type="button" @click="run()"
                            class="blue-grey lighten-5 black-text fw-b btn-large">
                        Simular
                    </button>
                </div>
            </div>

            <div class="nav-content">
                <ul class="tabs tabs-transparent tabs-fixed-width">
                    <li class="tab"><a href="#animation">Animação</a></li>
                    <li class="tab"><a href="#simulation" class="active">Simulação</a></li>
                    <li class="tab"><a href="#generator">Gerador</a></li>
                </ul>
            </div>
        </nav>

        <div id="animation" class="ml mr">
            <form-config-animation :config="config.animation"></form-config-animation>
        </div>
        <div id="simulation" class="ml mr">
            <form-config-simulation :config="config.simulation"></form-config-simulation>
        </div>
        <div id="generator" class="ml mr">
            <form-config-generator :config="config.generator"></form-config-generator>
        </div>

    </div>
</template>

<script>
    export default {

        data() {
            return {
                config: {}
            };
        },

        methods: {
            run() {
                this.$emit('submit', this.config);
            },

            onChange(event) {
                this.config = {};
                var reader = new FileReader();
                reader.onload = this.onReaderLoad;
                reader.readAsText(event.target.files[0]);
            },

            onReaderLoad(event){
                this.config = JSON.parse(event.target.result);
            },

            load() {
                var e = document.createEvent('MouseEvents');
                e.initMouseEvent('click', true, false, window, 0, 0, 0, 0, 0, false, false, false, false, 0, null);
                document.getElementById('file').dispatchEvent(e);
            },

            save(data, filename) {
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
            $('ul.tabs').tabs();
            document.getElementById('file').addEventListener('change', this.onChange);
            $.getJSON('api/getConfig', (data) => this.config = data);
        }
    }
</script>