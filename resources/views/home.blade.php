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
			<ui-icon icon="settings"></ui-icon> 
			Ajustes
		</div>

		<form action="/">
	  		
			<div class="row">
				<div class="input-field s12 col">
					<div class="ui-slider" start="0.5" min="0" max="1" step="0.1" decimals="1"></div>
				</div>
				<div class="input-field s12 col">
					<div class="ui-slider" start="15" min="0" max="30" step="1" decimals="0"></div>
				</div>
			</div>

	  	</form>

    </div>

	<!--div class="centered loading">
		<i class="fa fa-spinner fa-spin fa-5x fa-fw"></i>
		<span class="sr-only">Loading...</span>
	</div-->

	<div id="network" style="height:100vh;"><div>

@stop