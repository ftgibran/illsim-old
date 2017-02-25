
<template>
    <div class="stats card-panel blue-grey lighten-5 z-depth-0">
       
       <div class="ta-l">
            <div class="fl-r ml-s">
                {{ $parent.$parent.susceptible }}
            </div>
            <div style="overflow: hidden;">
                <label>
                    <i class="fa fa-square grey-text" aria-hidden="true"></i>
                    Suscetíveis:
                </label>
            </div>
        </div>

        <div class="ta-l">
            <div class="fl-r ml-s">
                {{ $parent.$parent.infected }}
            </div>
            <div style="overflow: hidden;">
                <label>
                    <i class="fa fa-square red-text" aria-hidden="true"></i>
                    Infectados:
                </label>
            </div>
        </div>

        <div class="ta-l">
            <div class="fl-r ml-s">
                {{ $parent.$parent.recovered }}
            </div>
            <div style="overflow: hidden;">
                <label>
                    <i class="fa fa-square yellow-text" aria-hidden="true"></i>
                    Recuperados:
                </label>
            </div>
        </div>

        <div class="ta-l">
            <div class="fl-r ml-s">
                {{ $parent.$parent.vaccinated }}
            </div>
            <div style="overflow: hidden;">
                <label>
                    <i class="fa fa-square green-text" aria-hidden="true"></i>
                    Vacinados:
                </label>
            </div>
        </div>

        <div class="ta-l">
            <div class="fl-r ml-s">
                {{ $parent.$parent.death }}
            </div>
            <div style="overflow: hidden;">
                <label>
                    <i class="fa fa-square black-text" aria-hidden="true"></i>
                    Mortos:
                </label>
            </div>
        </div>

        <div class="ta-l">
            <div class="fl-r ml-s">
                {{ $parent.$parent.population }}
            </div>
            <div style="overflow: hidden;">
                <label>
                    <i class="fa fa-users grey-text" aria-hidden="true"></i>
                    População:
                </label>
            </div>
        </div>

    </div>
</template>

<style>
    .stats {
        text-align: right;
        margin: 1em;
        position: absolute;
        top: 3em;
        right: 0em;
    }
</style>

<script>
    export default {
        ready() {	

        }
    }
</script>