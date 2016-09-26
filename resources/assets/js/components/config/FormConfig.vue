<template>
    <form @submit.prevent="api" method="post" class="ta-c">

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

			<li class="grey lighten-5">
				<form-config-factory></form-config-factory>
			</li>
		</ul>
		<button type="submit" class="btn-large mt mb">Simular</button>

  	</form>
</template>

<script>
    export default {

    	data() {
    		return {
    			action: 'api/config',
    			default: '',
    			data: ''
    		};
    	},

    	methods: {
			api() {
				var $self = this;
				$.getJSON(this.action, $(this.$el).serialize(), function (data) {
					$self.$set('data', data);
				});
			}
		},

        ready() {	
        }
    }
</script>