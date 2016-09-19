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

	<div class="panel card lighten-5 pos-a">
        <div class="card-content">
          <span class="card-title grey-text">
          <ui-icon icon="settings"></ui-icon> 
          Ajustes</span>

          	<hr>

          	<form action="/">
          		
          		<h2>Animação</h2>

				<div class="range-field">
					<label for="scale">Escala de tempo</label>
						<input type="range" id="scale" name="scale" min="0" max="1" step="0.1" value="1" />
				</div>

				<div class="range-field"> 
					<label for="shakeRadius">Força de impacto (infecção)</label>
	      			<input type="range" id="shakeRadius" name="shakeRadius" min="0" max="30" step="5" value="15" />
	    		</div>

	    		<h2>Simulação</h2>

				<div class="range-field">
					<label for="step">Intervalo de tempo (ms)</label>
					<input type="range" id="step" name="step" min="0" max="2000" step="100" value="1000" />
				</div>

				<div class="input-field">
				    <select>
				      <option value="node">Nó</option>
				      <option value="edge">Aresta</option>
				      <option value="both">Ambos</option>
				    </select>
				    <label>Tipo de infecção</label>
				  </div>
	          	</form>  

	          	<input type="checkbox"  class="filled-in" id="birthWhenDie" checked="checked" />
	      		<label for="birthWhenDie">Nascimento após uma morte</label>  

				<h2>Nós</h2>

				<div class="row">
			        <div class="input-field col s6">
			          <input id="min" type="number" value="80">
			          <label for="min">Quantidade Mínima</label>
			        </div>
			        <div class="input-field col s6">
			          <input id="max" type="number" value="120">
			          <label for="max">Quantidade Máxima</label>
			        </div>
			        <div class="range-field col s12">
						<label for="step">Máximo de arestas por nó</label>
						<input type="range" id="step" name="step" min="2" max="10" step="1" value="4" />
			      	</div>

			      	<div class="range-field col s12">
			      		<h4>Taxas</h4>
						
						<div class="row">
							<div class="range-field col s12">
								<label for="step">Infecção</label>
								<input type="range" id="step" name="step" min="0" max="100" step="1" value="50" />
					      	</div>
					      	<div class="range-field col s12">
								<label for="step">Resistência</label>
								<input type="range" id="step" name="step" min="0" max="100" step="1" value="50" />
					      	</div>
					      	<div class="range-field col s12">
								<label for="step">Recuperação</label>
								<input type="range" id="step" name="step" min="0" max="100" step="1" value="50" />
					      	</div>
					      	<div class="range-field col s12">
								<label for="step">Morte</label>
								<input type="range" id="step" name="step" min="0" max="100" step="1" value="50" />
					      	</div>
						</div>
			      	</div>
			    </div>

			      <h2>Aresta</h2>

			      <div class="row">
			        <div class="input-field col s6">
			          <input id="min" type="number" value="80">
			          <label for="min">Quantidade Mínima</label>
			        </div>
			        <div class="input-field col s6">
			          <input id="max" type="number" value="120">
			          <label for="max">Quantidade Máxima</label>
			        </div>

			      	<div class="range-field col s12">
			      		<h4>Taxas</h4>
						
						<div class="row">
							<div class="range-field col s12">
								<label for="step">Infecção</label>
								<input type="range" id="step" name="step" min="0" max="100" step="1" value="50" />
					      	</div>
					   
						</div>
			      	</div>
			    </div>

        </div>
    </div>

	<div class="centered loading">
		<i class="fa fa-spinner fa-spin fa-5x fa-fw"></i>
		<span class="sr-only">Loading...</span>
	</div>

	<div id="network" style="height:100vh;"><div>

@stop