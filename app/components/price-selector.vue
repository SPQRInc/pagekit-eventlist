<template>
    <input class="uk-width-1-1 uk-form-large" type="text"
           v-model="displayValue" @blur="isInputActive = false" @focus="isInputActive = true"/>
</template>

<script>

let currency = require ('currency.js');

module.exports = {

	props: ['price'],

	data () {
		return {
			isInputActive: false
		}
	},

	computed: {
		displayValue: {
			get: function () {
				if (this.isInputActive) {
					return this.price.toString ()
				} else {
					var euro = value => currency (value, {
						separator: ".",
						decimal: ",",
						symbol: "€",
						formatWithSymbol: true
					});
					return euro (this.price).format ();
				}
			},
			set: function (modifiedValue) {
				var euro = value => currency (value, {
					separator: ".",
					decimal: ",",
					symbol: "€",
					formatWithSymbol: false
				});
				this.price = euro (modifiedValue);
			}
		}
	}

};

</script>