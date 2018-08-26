<template>
    <div>
        <a class="uk-button" @click.prevent="editDescription()"><i class="uk-icon-list"></i> {{ 'Description' | trans }}
    </a>
    <v-modal v-ref:description>
        <form class="uk-form uk-form-stacked" v-validator="formDescription">
            <div class="uk-modal-header">
                <h2>{{ 'Edit Description' | trans }}</h2>
            </div>
            <div class="uk-width-large-1-1">
                <label class="uk-form-label">{{ 'Description' | trans }}</label>
                <div class="uk-form-controls">
                    <v-editor :value.sync="performer.description"
                              :options="{markdown : data.event.data.markdown, height: 250}"></v-editor>
                </div>
            </div>
        </form>
        <div class="uk-modal-footer uk-text-right">
            <button class="uk-button uk-button-link uk-modal-close"
                    type="button">{{ 'Close' | trans }}
            </button>
        </div>
    </v-modal>
</div>
        </template>

<script>

module.exports = {
	props: ['data', 'performer'],

	methods: {
		editDescription: function () {
			this.$refs.description.open ();
		}
	}
}
</script>