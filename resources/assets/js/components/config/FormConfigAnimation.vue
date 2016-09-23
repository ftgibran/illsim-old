
<template>

	<div class="collapsible-header">Animação</div>
	<div class="collapsible-body pl pr">
		<ui-slider
			name="animation[scale]"
			label="Escala de tempo"
			start="0.5"
			step="0.1"
			min="0"
			max="2"
			decimals="1"
		></ui-slider>
		<ui-slider
			name="animation[shakeRadius]"
			label="Força de impacto"
			start="15"
			step="1"
			min="0"
			max="30"
			decimals="0"
		></ui-slider>
	</div>

</template>

<script>
    export default {

        ready() {

			
        }
    }
</script>