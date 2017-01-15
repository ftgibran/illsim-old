@extends('tpl.main')
@section('title', 'IllSim')
@section('content')
    
    <ui-nav></ui-nav>

	<div class="ta-l pos-a fl-r" 
	style="background:#eee; width: 300px; top: 1em; bottom: 1em; right: 1em; z-index:99999; font-size: 12px; overflow-y: auto;">
		<pre>
			@{{ $refs.config.config | json}}
		</pre>
	</div>
    
    <ui-sidenav v-ref:sidenav>
        <form-config v-ref:config></form-config>
    </ui-sidenav>

    <ui-stats></ui-stats>

    <ui-loader v-ref:loader></ui-loader>

    <div @click="this.$refs.sidenav.hide()" id="network" style="height:100vh;"></div>
@stop