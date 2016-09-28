<template>
    <form @submit.prevent="api" method="post" class="ta-c">

		<ul class="collapsible mt-0" data-collapsible="accordion">
			<li class="mt-0 mb-0 ta-l" style="padding: 0;">
				
				<nav class="blue-grey darken-2 white-text pl pr">
					<div class="nav-wrapper">
						<div class="brand-logo">
							<i class="left material-icons">settings</i>
						</div>
						<ul id="nav-mobile" class="right hide-on-med-and-down">
							<li><button type="submit" class="blue-grey lighten-5 black-text fw-b btn-large">
							Simular
							</button></li>
						</ul>
					</div>
				</nav>

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
        	$(this.$el).find('.collapsible').collapsible();
        }
    }
</script>