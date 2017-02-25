
<template>
    <nav class="blue-grey lighten-5 pos-a z-depth-0">
        <div class="nav-wrapper">
            <ul class="left">
                <li>
                    <a @click="this.$parent.$refs.sidenav.show()"
                        class="btn-floating btn-large waves-effect waves-light"><i class="material-icons">settings</i></a>
                </li>
                <li>
                    <a @click="this.$parent.$refs.config.simulate()"
                        class="pos-a"
                        style="color:black; z-index: 1;"><i class="material-icons left">replay</i>
                        <span class="fw-b">Simular</span>
                    </a>
                </li>
            </ul>
            <div class="brand-logo right grey-text">
                <span class="illsim">ILLSIM</span>
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