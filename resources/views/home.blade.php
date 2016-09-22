@extends('tpl.main')

@section('title', 'IllSim')

@section('content')

	<nav class="blue-grey lighten-5 pos-a z-depth-0">
		<div class="nav-wrapper">
		  <div class="brand-logo right grey-text">
			<span class="illsim">IllSim</span> <small>by Felipe Gibran</small>
		  </div>
		</div>
	</nav>

	<div id="left-nav" class="card-panel grey lighten-5 pt-xs pb-xs pl pr">

		<div class="card-panel blue-grey darken-2 white-text pt-xs pb-xs pl-s pr-s">
			<i class="left material-icons">settings</i>
			Configurações
		</div>

		<form id="illForm" action="api/network" method="post" class="ta-c">

			<h4>Animação</h4>

			<ui-slider
				name="illsim[animation][scale]"
				label="Escala de tempo"
				start="0.5"
				step="0.1"
				min="0"
				max="2"
				decimals="1"
			></ui-slider>
			<ui-slider
				name="illsim[animation][shakeRadius]"
				label="Força de impacto"
				start="15"
				step="1"
				min="0"
				max="30"
				decimals="0"
			></ui-slider>

			<h4>Simulação</h4>

			<ui-slider
				name="illsim[simulation][step]"
				label="Intervalo de tempo"
				start="400"
				step="50"
				min="0"
				max="2000"
				decimals="0"
				postfix="ms"
			></ui-slider>

			<div class="input-field ta-l" id="infectBy">
				<select name="illsim[simulation][infectBy]">
					<option value="node">Nó</option>
					<option value="edge">Aresta</option>
					<option value="both">Nó e Aresta</option>
					<option value="special">Fórmula [1-exp(-n)]</option>
				</select>
				<label>Tipo de infecção</label>
			</div>

			<div class="ta-l mb-l">
				<input type="checkbox" class="filled-in" id="birthWhenDie" name="illsim[simulation][birthWhenDie]" checked="checked" />
				<label for="birthWhenDie">Nascimento após morte</label>
			</div>

			<h4>Nós</h4>

			<ui-slider
				name="factory[node]"
				label="Quantidade (mínimo e máximo)"
				start="80,100"
				step="1"
				min="0"
				max="200"
				decimals="0"
			></ui-slider>

			<ui-slider
				name="factory[node][maxEdges]"
				label="Máximo de arestas por nó"
				start="4"
				step="1"
				min="0"
				max="8"
				decimals="0"
			></ui-slider>

			<div class="row">
				<div class="input-field s12 m6 col">
	          		<input type="hidden" name="factory[node][groups][0][ref]" value="i"/>
					<div class="fl-r">
	          			<input type="checkbox" class="filled-in" id="i-percent" name="factory[node][groups][0][percent]" />
						<label for="i-percent">%</label>
					</div>
					<div style="overflow: hidden;">
						<label class="truncate" for="i-quant">
						<i class="fa fa-square red-text" aria-hidden="true"></i>
						Quantidade de infectados</label>
						<input type="text" id="i-quant" name="factory[node][groups][0][quant]" value="5">
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
	          			<label class="truncate" for="v-quant">
						<i class="fa fa-square green-text" aria-hidden="true"></i>
	          			Quantidade de vacinados</label>
						<input type="text" id="v-quant" name="factory[node][groups][1][quant]" value="20">
					</div>

					<div class="cl-b"></div>
				</div>
			</div>

			<ui-slider
				icon="fa-square red-text"
				name="factory[node][rate][infect]"
				label="Taxa de infecção"
				start="50,100"
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
				start="0,50"
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
				start="1,5"
				step="0.5"
				min="0"
				max="100"
				decimals="1"
				postfix="%"
			></ui-slider>

			<ui-slider
				icon="fa-square black-text"
				name="factory[node][rate][death]"
				label="Taxa de morte"
				start="1,5"
				step="0.5"
				min="0"
				max="100"
				decimals="1"
				postfix="%"
			></ui-slider>

			<h4>Arestas</h4>

			<ui-slider
				name="factory[edge]"
				label="Quantidade (mínimo e máximo)"
				start="120,150"
				step="3"
				min="0"
				max="300"
				decimals="0"
			></ui-slider>

			<ui-slider
				icon="fa-square red-text"
				name="factory[edge][rate][infect]"
				label="Taxa de infecção"
				start="50,100"
				step="0.5"
				min="0"
				max="100"
				decimals="1"
				postfix="%"
			></ui-slider>

			<button type="submit" class="btn-large mt mb">Gerar rede</button>

	  	</form>

    </div>

	<div class="centered loading">
		<i class="fa fa-spinner fa-spin fa-5x fa-fw"></i>
		<span class="sr-only">Loading...</span>
	</div>

	<div id="network" style="height:100vh;"><div>

@stop