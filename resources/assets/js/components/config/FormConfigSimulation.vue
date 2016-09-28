<template>
	<div class="collapsible-header">Simulação</div>
	<div class="collapsible-body pl pr">

		<ui-slider
			name="simulation[step]"
			label="Intervalo de tempo (ms)"
			start="400"
			step="50"
			min="0"
			max="2000"
			decimals="0"
		></ui-slider>

		<div class="input-field ta-l">
			<select name="simulation[infectBy]" v-select="infectBy">
				<option value="node">Nó</option>
				<option value="edge">Aresta</option>
				<option value="both">Nó e Aresta</option>
				<option value="special">Fórmula [1-exp(-k*n)]</option>
			</select>
			<label>Tipo de infecção</label>
		</div>

		<ui-slider v-if="infectBy == 'special'"
			name="simulation[k]"
			label="Constante k"
			start="0.5"
			step="0.01"
			min="0"
			max="1"
			decimals="0"
		></ui-slider>

		<ul class="collection z-depth-1 mb">
			<li class="collection-header" style="border-bottom: 1px solid #ccc;">
				<h5><i class="fa fa-square yellow-text" aria-hidden="true"></i> Recuperados</h5>
			</li>

			<li class="collection-item">
				<checkbox label='Podem infectar' name='simulation[r][mayInfect]'>
					<ui-slider	
						name="simulation[r][base][infected]"
						label="Taxa de infecção base"
						icon="fa-square red-text"
						start="15"
						step="0.5"
						min="0"
						max="100"
						decimals="1"
						postfix="%"
					></ui-slider>
				</checkbox>				
			</li>
			<li class="collection-item">
				<checkbox label='Podem ser infectados' name='simulation[r][mayBeInfected]'>
					<ui-slider	
						name="simulation[r][base][resist]"
						label="Taxa de resistência base"
						icon="fa-square-o green-text"
						start="90"
						step="0.5"
						min="0"
						max="100"
						decimals="1"
						postfix="%"
					></ui-slider>
				</checkbox>
			</li>
			<li class="collection-item">
				<checkbox label='Podem se tornar suscetíveis' name='simulation[r][mayGetSusceptible]'>
					<ui-slider	
						name="simulation[r][base][recover]"
						label="Taxa de suscetibilidade base"
						icon="fa-square grey-text"
						start="10"
						step="0.5"
						min="0"
						max="100"
						decimals="1"
						postfix="%"
					></ui-slider>
				</checkbox>
			</li>
			<li class="collection-item">
				<checkbox label='Podem falecer' name='simulation[r][mayDie]'>
					<ui-slider	
						name="simulation[r][base][death]"
						label="Taxa de mortalidade base"
						icon="fa-square black-text"
						start="5"
						step="0.5"
						min="0"
						max="100"
						decimals="1"
						postfix="%"
					></ui-slider>
				</checkbox>
			</li>
	    </ul>

	    <ul class="collection z-depth-1 mb">
			<li class="collection-header" style="border-bottom: 1px solid #ccc;">
				<h5><i class="fa fa-square green-text" aria-hidden="true"></i> Vacinados</h5>
			</li>

			<li class="collection-item">
				<checkbox label='Podem infectar' name='simulation[v][mayInfect]'>
					<ui-slider	
						name="simulation[v][base][infect]"
						label="Taxa de infecção base"
						icon="fa-square red-text"
						start="0"
						step="0.5"
						min="0"
						max="100"
						decimals="1"
						postfix="%"
					></ui-slider>
				</checkbox>
			</li>

			<li class="collection-item">
				<checkbox label='Podem ser infectados' name='simulation[v][mayBeInfected]'>
					<ui-slider	
						name="simulation[v][base][resist]"
						label="Taxa de resistência base"
						icon="fa-square-o green-text"
						start="99.5"
						step="0.5"
						min="0"
						max="100"
						decimals="1"
						postfix="%"
					></ui-slider>
				</checkbox>
			</li>

			<li class="collection-item">
				<checkbox label='Podem se tornar suscetíveis' name='simulation[v][mayGetSusceptible]'>
					<ui-slider	
						name="simulation[v][base][recover]"
						label="Taxa de suscetibilidade base"
						icon="fa-square grey-text"
						start="1"
						step="0.5"
						min="0"
						max="100"
						decimals="1"
						postfix="%"
					></ui-slider>
				</checkbox>
			</li>

			<li class="collection-item">
				<checkbox label='Podem falecer' name='simulation[v][mayDie]'>
					<ui-slider	
						name="simulation[v][base][death]"
						label="Taxa de mortalidade base"
						icon="fa-square black-text"
						start="0"
						step="0.5"
						min="0"
						max="100"
						decimals="1"
						postfix="%"
					></ui-slider>
				</checkbox>
			</li>
	    </ul>

	    <ul class="collection z-depth-1 mb">
			<li class="collection-header" style="border-bottom: 1px solid #ccc;">
				<h5><i class="fa fa-square black-text" aria-hidden="true"></i> Mortos</h5>
			</li>

			<li class="collection-item">
				<checkbox label='Nascimento após morte' name='simulation[birthWhenDie]' :checked='false' ></checkbox>
			</li>
	    </ul>

	</div>
</template>

<script>
    export default {

    	data() {
    		return {
    			infectBy: 'node'
    		}
    	},

    	directives: {
		  'select': {
		    twoWay: true,
		    params: ['options'],
		    bind: function () {
		      var self = this
		      $(this.el).select().on('change', function() {
		        self.set($(self.el).val())
		      })
		    },
		    update: function (value) {
		      $(this.el).val(value).trigger('change')
		    },
		  },
		},
  
        ready() {

			
        }
    }
</script>