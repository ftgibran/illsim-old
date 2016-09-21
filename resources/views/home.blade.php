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

	<div id="left-nav" class="card-panel grey lighten-5 pt-xs pb-xs pl-s pr-s">

		<div class="card-panel grey darken-1 white-text pt-xs pb-xs pl-s pr-s">
			<i class="left material-icons">settings</i>
			Configurações
		</div>

		<form action="/submit" class="ta-c">

			<div class="row">
				<h4>Animação</h4>

				<div class="s12 col">
					<ui-slider></ui-slider>
				</div>

				<div class="s12 col">
					<ui-slider></ui-slider>
				</div>

				<div class="input-field col s12 mt-0">
				    <select>
						<option value="node">Nó</option>
						<option value="edge">Aresta</option>
						<option value="both">Nó e Aresta</option>
						<option value="special">Fórmula</option>
				    </select>
			    	<label>Tipo de infecção</label>
				</div>

			</div>

			<div class="row">
				<h4>Simulação</h4>

				<div class="s12 col">
					<ui-ranger></ui-ranger>
				</div>
			</div>

			<button type="submit" class="btn-large">Gerar rede</button>

	  	</form>

    </div>

	<!--div class="centered loading">
		<i class="fa fa-spinner fa-spin fa-5x fa-fw"></i>
		<span class="sr-only">Loading...</span>
	</div-->

	<div id="network" style="height:100vh;"><div>

@stop