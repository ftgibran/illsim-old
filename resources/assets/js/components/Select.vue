<template>

    <select :name="name" v-select="val">
                <option value="node">Nó</option>
                <option value="edge">Aresta</option>
                <option value="both">Nó e Aresta</option>
                <option value="special">Fórmula [1-exp(-k*n)]</option>
    </select>
    <label>{{label}}</label>

</template>

<script>
    export default {

    	props: {
			name: {
        		type: String
        	},
        	label: {
        		type: String,
        		default: ''
        	},
        	val: {
        		type: String,
                default: ''
        	}
    	},

        directives: {
          'select': {
            twoWay: true,
            params: ['options'],
            bind: function () {
                $(this.el).material_select();
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