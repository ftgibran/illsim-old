
<template>
    <div class="input-field">
		<label class="truncate"><i class="fa {{icon}}" aria-hidden="true"></i> {{label}}</label>

		<div class="nouislider"></div>

		<div class="row mb-0">
			<div class="input-field mt-0 s6 col">
				<input type="text" v-model="value1" class="ta-l">
			</div>
			<div class="input-field mt-0 s6 col" v-show="range">
				<input type="text" v-model="value2" class="ta-r">
			</div>
		</div>
	</div>
</template>

<script>

    export default {

    	data() {
    		return {
    			value1: '',
    			value2: ''
    		};
    	},

        props: {
        	icon: {
        		type: String,
        		default: ''
        	},
        	label: {
        		type: String,
        		default: ''
        	},
        	range: {
        		type: Boolean,
        		default: false
        	},
        	val: {},
            valMin: {},
			valMax: {},
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

        watch: {
        	value1(newVal,oldVal) {
        		if(this.range) {
        			this.valMin = this.postfix == '%' ? this.per2num(newVal) : this.str2num(newVal);
        		} else {
        			this.val = this.postfix == '%' ? this.per2num(newVal) : this.str2num(newVal);
        		}
        	},
        	value2(newVal,oldVal) {
        		if(this.range) {
        			this.valMax = this.postfix == '%' ? this.per2num(newVal) : this.str2num(newVal);
        		}
        	},
        	val(newVal,oldVal) {
        		if(this.range) {
        			this.value1 = this.postfix == '%' ? this.num2per(newVal[0]) : this.num2str(newVal[0]);
        			this.value2 = this.postfix == '%' ? this.num2per(newVal[1]) : this.num2str(newVal[1]);
        		} else {
        			this.value1 = this.postfix == '%' ? this.num2per(newVal) : this.num2str(newVal);
        		}
        	}
        },

        methods: {
        	num2per(value) {
        		return new String (value * 100).replace(',','.') + '%';
        	},
        	per2num(value) {
        		return Number(value.replace('%','').replace(',','.')) / 100;
        	},
        	str2num(value) {
        		return Number(value.replace(',','.'));
        	},
        	num2str(value) {
        		return new String (value).replace(',','.');
        	}
        },

        ready() {
			var el = this.$el;

			var input = $(el).find('input');
			var label = $(el).find('label');
			var slider = $(el).find('.nouislider')[0];

			var start = [];

    		if(this.range) {
    			start[0] = this.valMin * (this.postfix == '%' ? 100 : 1);
    			start[1] = this.valMax * (this.postfix == '%' ? 100 : 1);
    		} else {
    			start[0] = this.val * (this.postfix == '%' ? 100 : 1);
    		}

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
        		if(this.range) {
					this.value1 = values[0];
					this.value2 = values[1];
        		} else {
					this.value1 = values[0];
        		}
			}.bind(this));

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