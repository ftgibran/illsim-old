<template>

	<div class="ta-l">
        <input v-if="checked" type="checkbox" class="filled-in" id="{{name}}" name="{{name}}" checked="checked"/>
		<input v-else type="checkbox" class="filled-in" id="{{name}}" name="{{name}}"/>
		<label class="truncate" for="{{name}}">{{label}}</label>
	</div>

</template>

<script>
    export default {

    	props: {
			name: {
        		type: String,
        		required: true
        	},
        	label: {
        		type: String,
        		default: ''
        	},
        	checked: {
        		type: Boolean,
        		default: false
        	}
    	},

        ready() {
        }
    }
</script>