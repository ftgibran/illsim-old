<template>

	<div class="collapsible-header">Gerador</div>
	<div class="collapsible-body pl pr">

		<div class="input-field ta-l">
			<select name="factory[method]">
				<option value="fullRandom">Full Random</option>
				<option value="Uniform">Uniforme</option>
			</select>
			<label>Método de Geração</label>
		</div>

		<form-config-full-random></form-config-full-random>

	</div>

</template>

<script>
    export default {
        ready() {

			
        }
    }
</script>