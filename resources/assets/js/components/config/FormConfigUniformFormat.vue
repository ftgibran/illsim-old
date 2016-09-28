<template>

	<ul class="collapsible popout" data-collapsible="accordion">

		<li class="card-panel blue-grey darken-2 white-text mt-0 mb-0 pt-s pb-s ta-l">
			Uniform Format
		</li>

		<li class="grey lighten-5">
			<div class="collapsible-header">Nível</div>
			<div class="collapsible-body pl pr">
				<ui-slider
					name="factory[level]"
					label="Nível"
					start="6"
					step="1"
					min="1"
					max="20"
					decimals="0"
				></ui-slider>	
			</div>
			</li>		
		</li>

		<li class="grey lighten-5">
		<div class="collapsible-header">Nós</div>
		<div class="collapsible-body pl pr">

			<div class="row">
				<div class="input-field s12 m6 col">
	          		<input type="hidden" name="factory[node][groups][0][ref]" value="i"/>
					<div class="fl-r">
	          			<input type="checkbox" class="filled-in" id="i-percent" name="factory[node][groups][0][percent]" />
						<label for="i-percent">%</label>
					</div>
					<div style="overflow: hidden;">
						<label class="active truncate" for="i-quant">
						<i class="fa fa-square red-text" aria-hidden="true"></i>
						Quantidade de infectados</label>
						<input type="number" id="i-quant" name="factory[node][groups][0][quant]" value="5" required>
					</div>
					
					<div class="cl-b"></div>
				</div>
					
				<div class="input-field s12 m6 col">
					<input type="hidden" name="factory[node][groups][1][ref]" value="v"/>
					<div class="fl-r">
	          			<input type="checkbox" class="filled-in" id="v-percent" name="factory[node][groups][1][percent]" checked="checked" />
						<label for="v-percent">%</label>
					</div>
					<div style="overflow: hidden;">
	          			<label class="active truncate" for="v-quant">
						<i class="fa fa-square green-text" aria-hidden="true"></i>
	          			Quantidade de vacinados</label>
						<input type="number" id="v-quant" name="factory[node][groups][1][quant]" value="10" required>
					</div>

					<div class="cl-b"></div>
				</div>
			</div>

			<ui-slider
				icon="fa-square red-text"
				name="factory[node][rate][infect]"
				label="Taxa de infecção"
				start="50"
				step="0.5"
				min="0"
				max="100"
				decimals="1"
				postfix="%"
			></ui-slider>

			<ui-slider
				icon="fa-square-o green-text"
				name="factory[node][rate][resist]"
				label="Taxa de resistência"
				start="0"
				step="0.5"
				min="0"
				max="100"
				decimals="1"
				postfix="%"
			></ui-slider>

			<ui-slider
				icon="fa-square yellow-text"
				name="factory[node][rate][recover]"
				label="Taxa de recuperação"
				start="10"
				step="0.5"
				min="0"
				max="100"
				decimals="1"
				postfix="%"
			></ui-slider>

			<ui-slider
				icon="fa-square black-text"
				name="factory[node][rate][death]"
				label="Taxa de mortalidade"
				start="30"
				step="0.5"
				min="0"
				max="100"
				decimals="1"
				postfix="%"
			></ui-slider>

		</div>
		</li>

		<li class="grey lighten-5">
		<div class="collapsible-header">Arestas</div>
		<div class="collapsible-body pl pr">

			<ui-slider
				icon="fa-square red-text"
				name="factory[edge][rate][infect]"
				label="Taxa de infecção"
				start="10"
				step="0.5"
				min="0"
				max="100"
				decimals="1"
				postfix="%"
			></ui-slider>

		</div>
		</li>
	</ul>

</template>

<script>
    export default {
        ready() {
        	$(this.$el).collapsible();
        }
    }
</script>