
<template>
    <div class="input-field">
		<label class="truncate"><i class="fa {{icon}}" aria-hidden="true"></i> {{label}}</label>
		
		<div class="nouislider"></div>

		<div class="row mb-0">
			<div class="input-field mt-0 s6 col">
				<input type="text" name="{{name}}[min]" class="ta-l">
			</div>
			<div class="input-field mt-0 s6 col" v-show="range">
				<input type="text" name="{{name}}[max]" class="ta-r">
			</div>
		</div>
	</div>
</template>

<script>
    export default {

    	data() {
    		return {
    			range: false
    		};
    	},

        props: {
        	icon: {
        		type: String,
        		default: ''
        	},
        	name: {
        		type: String,
        		required: true
        	},
        	label: {
        		type: String,
        		default: ''
        	},
        	start: {
        		type: String,
        		default: '0'
        	},
        	step: {
        		type: String,
        		default: '1'
        	},
        	min: {
        		type: String,
        		default: '0'
        	},
        	max: {
        		type: String,
        		default: '100'
        	},
        	decimals: {
        		type: String,
        		default: '0'
        	},
        	prefix: {
        		type: String,
        		default: ''
        	},
        	postfix: {
        		type: String,
        		default: ''
        	}
        },

        ready() {

			var el = this.$el;

			var input = $(el).find('input');
			var label = $(el).find('label');
			var slider = $(el).find('.nouislider')[0];

			var start = this.start.split(",");
			this.$set('range', start.length > 1);

			$(el).css('margin-top', '2em');
			label.css('top', '-2em');
			label.css('left', '0');
			label.css('font-size', '14px');
			input.css('border', 'none');
			input.css('margin-bottom', '0');

			if(!this.range)
			{
				$(input[0]).attr('id', this.name);
				$(input[0]).attr('name', this.name);
				$(input[1]).remove();
			}

			noUiSlider.create(slider, {
				start: start,
				connect: this.range ? true : 'lower',
				step: Number(this.step),
				range: {
					'min': Number(this.min),
					'max': Number(this.max)
				},
				format: wNumb({
					decimals: Number(this.decimals),
					postfix: this.postfix,
					prefix: this.prefix
				})
			});

			slider.noUiSlider.on('update', function(values, handle) {
				input.each(function(index) {
					$(this).val(values[index]);
				});
			});

			input.each(function(index) {
				$(this).on('change', function() {
					if (index)
						slider.noUiSlider.set([null, this.value]);
					else
						slider.noUiSlider.set([this.value, null]);
				}).
				on('click', function () {
					$(this).select();
				});
			});
        }
    }
</script>