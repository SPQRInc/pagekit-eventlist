<template>

    <div class="uk-form-row">
        <label for="form-link-eventlist" class="uk-form-label">{{ 'View' | trans }}</label>
        <div class="uk-form-controls">
            <select id="form-link-eventlist" class="uk-width-1-1" v-model="link">
                <option value="@eventlist">{{ 'Overview' | trans }}</option>
            </select>
        </div>
    </div>

</template>

<script>

module.exports = {

	link: {
		label: 'Eventlist'
	},

	props: ['link'],

	ready: function () {
		this.$set ('link', '@eventlist');
	}

};

window.Links.components['eventlist'] = module.exports;

</script>