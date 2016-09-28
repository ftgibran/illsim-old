<template>

	<div class="collapsible-header">Gerador</div>
	<div class="collapsible-body pl pr">

		<div class="input-field ta-l">
			<select name="factory[method]" v-select="factory">
				<option value="full-random">Full Random</option>
				<option value="uniform-format">Uniform Format</option>
			</select>
			<label>Método de Geração</label>
		</div>

		<form-config-full-random v-if="factory == 'full-random'"></form-config-full-random>
		<form-config-uniform-format v-if="factory == 'uniform-format'"></form-config-uniform-format>

	</div>

</template>

<script>
    export default {

    	data() {
    		return {
    			factory: 'full-random'
    		}
    	},

    	directives: {
		  'select': {
		    twoWay: true,
		    params: ['options'],
		    bind: function () {
		      var self = this
		      $(this.el).select().on('change', function() {
		        self.set($(self.el).val())
		      })
		    },
		    update: function (value) {
		      $(this.el).val(value).trigger('change')
		    },
		  },
		},

        ready() {

			
        }
    }
</script>