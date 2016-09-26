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
			<select name="simulation[infectBy]">
				<option value="node">Nó</option>
				<option value="edge">Aresta</option>
				<option value="both">Nó e Aresta</option>
				<option value="special">Fórmula [1-exp(-n)]</option>
			</select>
			<label>Tipo de infecção</label>
		</div>

		<ul class="collection z-depth-1 mb">
			<li class="collection-header" style="border-bottom: 1px solid #ccc;">
				<h5><i class="fa fa-square yellow-text" aria-hidden="true"></i> Recuperados</h5>
			</li>

			<li class="collection-item">
				<checkbox label='Podem infectar' name='simulation[r][mayInfect]' :checked='false' >
				</checkbox>				
			</li>
			<li class="collection-item">
				<checkbox label='Podem ser infectados' name='simulation[r][mayBeInfected]' :checked='false' ></checkbox>
			</li>
			<li class="collection-item">
				<checkbox label='Podem se tornar suscetíveis' name='simulation[r][mayGetSusceptible]' :checked='false' ></checkbox>
			</li>
			<li class="collection-item">
				<checkbox label='Podem falecer' name='simulation[r][mayDie]' :checked='false' ></checkbox>
			</li>
	    </ul>

	    <ul class="collection z-depth-1 mb">
			<li class="collection-header" style="border-bottom: 1px solid #ccc;">
				<h5><i class="fa fa-square green-text" aria-hidden="true"></i> Vacinados</h5>
			</li>

			<li class="collection-item">
				<checkbox label='Podem infectar' name='simulation[v][mayInfect]' :checked='false' ></checkbox>
			</li>

			<li class="collection-item">
				<checkbox label='Podem ser infectados' name='simulation[v][mayBeInfected]' :checked='false' ></checkbox>
			</li>

			<li class="collection-item">
				<checkbox label='Podem se tornar suscetíveis' name='simulation[v][mayGetSusceptible]' :checked='false' ></checkbox>
			</li>

			<li class="collection-item">
				<checkbox label='Podem falecer' name='simulation[v][mayDie]' :checked='false' ></checkbox>
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
  
        ready() {

			
        }
    }
</script>