<template>

    <div class="input-field">
        <select :name="name" v-select="val">
            <option v-for="opt in options" :value="opt.val">{{opt.label}}</option>
        </select>
        <label><i class="fa {{icon}}" aria-hidden="true"></i> {{label}}</label>
    </div>

</template>

<script>
    export default {

        props: {
            icon: {
                type: String,
                default: ''
            },
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
            },
            options: {
                type: Array,
                default: function () {
                    return [{
                        "label": "Select",
                        "val": "0"
                    }]
                }
            }
        },

        directives: {
            'select': {
                twoWay: true,
                params: ['options'],
                bind: function () {
                    $(this.el).material_select();
                    var self = this;
                    $(this.el).select().on('change', function () {
                        self.set($(self.el).val());
                    })
                },
                update: function (value) {
                    $(this.el).val(value).trigger('change');
                }
            }
        },

        watch: {
            options() {
                $('select').material_select();
            }
        },

        ready() {
            $('select').material_select();
        }
    }
</script>