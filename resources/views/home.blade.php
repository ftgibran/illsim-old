@extends('tpl.main')

@section('title', 'IllSim')

@section('content')

	<nav class="blue-grey lighten-5 pos-a z-depth-0">
		<div class="nav-wrapper">
			
			<ul class="left">
		        <li>
		        	<a @click="sideNav('show')" class="btn-floating btn-large waves-effect waves-light"><i class="material-icons">settings</i></a>
		        </li>
		        <li>
		        	<a @click="$refs.config.api()" class="pos-a" style="color:black; z-index: 1;"><i class="material-icons left">replay</i> <span class="fw-b">Simular</span></a>
		        </li>
		    </ul>

			<div class="brand-logo right grey-text">
				<span class="illsim">IllSim</span> <small>by Felipe Gibran</small>
			</div>

		</div>
	</nav>

	<div id="config-nav" class="card-panel blue-grey lighten-4">
		<form-config v-ref:config></form-config>
    </div>

	<div class="centered loading">
		<i class="fa fa-spinner fa-spin fa-5x fa-fw"></i>
		<span class="sr-only">Loading...</span>
	</div>

	<div @click="sideNav('hide')" id="network" style="height:100vh;"><div>

@stop