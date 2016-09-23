
<template>
    <form action="api/network" method="post" class="ta-c">

		<ul class="collapsible mt-0" data-collapsible="accordion">
			<li class="card-panel blue-grey darken-2 white-text mt-0 mb-0 ta-l">
				<i class="left material-icons">settings</i>
				Configurações
			</li>

			<li class="grey lighten-5">
				<form-config-animation></form-config-animation>
			</li>

			<li class="grey lighten-5">
				<form-config-simulation></form-config-simulation>
			</li>
		</ul>

		<button type="submit" class="btn-large mt mb">Gerar rede</button>

  	</form>
</template>

<script>
    export default {
        ready() {

			
        }
    }
</script>