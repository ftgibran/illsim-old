<template>
    <nav class="blue-grey lighten-5 pos-a z-depth-0">
        <div class="nav-wrapper">
            <ul class="left">
                <li>
                    <a @click="$parent.$refs.sidenav.show()"
                       class="btn-floating btn-large waves-effect waves-light"><i
                            class="material-icons">settings</i></a>
                </li>
                <li v-if="$parent.$refs.network">
                    <a v-if="$parent.$refs.network.state == null"
                       @click="$parent.$refs.config.simulate()"
                       class="pos-r"
                       style="color:black; z-index: 1;">
                        <i class="material-icons left">play_arrow</i>
                        <span class="fw-b">Simular</span>
                    </a>
                    <a v-if="$parent.$refs.network.state != null"
                       @click="$parent.$refs.config.simulate()"
                       class="pos-r"
                       style="color:black; z-index: 1;">
                        <i class="material-icons left">refresh</i>
                        <span class="fw-b">Reset</span>
                    </a>
                </li>
                <li v-if="$parent.$refs.network">
                    <a v-if="$parent.$refs.network.state == 'paused'"
                       @click="$parent.$refs.network.play()"
                       class="pos-r"
                       style="color:black; z-index: 1;"
                    >
                        <i class="material-icons left">play_arrow</i>
                        <span class="fw-b">Play</span>
                    </a>
                    <a v-if="$parent.$refs.network.state == 'playing'"
                       @click="$parent.$refs.network.stop()"
                       class="pos-r"
                       style="color:black; z-index: 1;"
                    >
                        <i class="material-icons left">pause</i>
                        <span class="fw-b">Pausar</span>
                    </a>
                </li>
            </ul>

            <ul class="right hide-on-small-only">
                <li>
                    <a href="#bmodal"
                       class="btn waves-effect waves-light">
                        <i class="material-icons left">timeline</i>
                        Analytics
                    </a>
                </li>
            </ul>

            <div class="brand-logo center grey-text">
                <span class="sahitya">ILLSIM</span>
            </div>
        </div>
    </nav>
</template>

<script>

    export default {

        ready() {
        }
    }
</script>