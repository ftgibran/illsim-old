<template>

	<div class="ta-l mt-s">
        <input v-model="checked" type="checkbox" class="filled-in" id="{{name}}" name="{{name}}"/>
		<label class="truncate" for="{{name}}">{{label}}</label>
	</div>

    <slot v-if="checked"></slot>

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