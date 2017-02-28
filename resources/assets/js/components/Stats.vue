<template>

    <ul class="stats">
        <li class="chip">
            <i class="fa fa-square red-text" aria-hidden="true"></i>
            Infectados
            <span class="badge">
                {{ $parent.$parent.infected }}
            </span>
        </li>
        <li class="chip">
            <i class="fa fa-square yellow-text" aria-hidden="true"></i>
            Recuperados
            <span class="badge">
                {{ $parent.$parent.recovered }}
            </span>
        </li>
        <li class="chip">
            <i class="fa fa-square grey-text" aria-hidden="true"></i>
            Suscetíveis
            <span class="badge">
                {{ $parent.$parent.susceptible }}
            </span>
        </li>
        <li class="chip">
            <i class="fa fa-square green-text" aria-hidden="true"></i>
            Vacinados
            <span class="badge">
                {{ $parent.$parent.vaccinated }}
            </span>
        </li>
        <li class="chip">
            <i class="fa fa-square black-text" aria-hidden="true"></i>
            Falecidos
            <span class="badge">
                {{ $parent.$parent.death }}
            </span>
        </li>
        <li class="chip">
            População
            <span class="badge">
                {{ $parent.$parent.population }}
            </span>
        </li>
    </ul>

</template>

<style lang="sass" rel="stylesheet/scss" scoped>
    .stats {
        display: flex;
        flex-direction: column;
        margin: .5em;
        position: absolute;
        top: 64px;
        right: 0;

        .badge {
            margin: 4px auto;
        }
    }
</style>

<script>
    export default {
        ready() {

        }
    }
</script>