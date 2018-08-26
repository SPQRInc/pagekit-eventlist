<template>
    <div>
        <a class="uk-button" @click.prevent="editSocialmedia()"><i
            class="uk-icon-list"></i> {{ 'Social Media' | trans }}
    </a>
    <v-modal v-ref:socialmedia>
        <form class="uk-form uk-form-stacked" v-validator="formSocialmedia"
        @submit.prevent="addLink | valid">
        <div class="uk-modal-header">
            <h2>{{ 'Edit Social Media' | trans }}</h2>
        </div>
        <div class="uk-form-row">
            <div class="uk-grid" data-uk-margin>
                <div class="uk-width-large-1-4">
                    <div class="uk-form-controls">
                        <select class="uk-form-width-small" v-model="newSocialmedialink.type">
                            <option value="">{{ '- Choose -' | trans }}</option>
                            <option v-for="type in data.config.socialmedia_icons"
                                    :value="type.title">{{ type.title | trans }}
                            </option>
                        </select>
                    </div>
                </div>
                <div class="uk-width-large-2-4">
                    <input class="uk-input-large"
                           type="text"
                           placeholder="{{ 'URL' | trans }}"
                           name="url"
                           v-model="newSocialmedialink.url"
                           v-validate:required>
                    <p class="uk-form-help-block uk-text-danger"
                       v-show="formSocialmedia.url.invalid">{{ 'Invalid value.' | trans }}</p>
                </div>
                <div class="uk-width-large-1-4">
                            <span class="uk-align-right">
                               <button class="uk-button" @click.prevent="addLink | valid">
                                            {{ 'Add' | trans }}
                                </button>
                </span>
            </div>
        </div>
    </div>
</form>
<hr/>
<div class="uk-alert"
     v-if="!performer.socialmedia.length">{{ 'You can add your first Social Media link using the input field above. Go ahead!' | trans }}
</div>
<div class="uk-form uk-form-stacked">
<div class="uk-form-row" v-if="performer.socialmedia.length" v-for="link in performer.socialmedia">
    <div class="uk-grid" data-uk-margin>
        <div class="uk-width-large-1-4">
            <input id="form-value{{$index}}" class="uk-input-large"
                   type="text"
                   placeholder="{{ 'Type' | trans }}"
                   v-model="link.type">
        </div>
        <div class="uk-width-large-2-4">
            <input id="form-value{{$index}}" class="uk-input-large"
                   type="text"
                   placeholder="{{ 'URL' | trans }}"
                   v-model="link.url">
        </div>
        <div class="uk-width-large-1-4">
                            <span class="uk-align-right">
                                <button class="uk-button uk-button-danger" @click.prevent = "removeLink(link)">
                                    <i class="uk-icon-remove"></i>
                                </button>
        </span>
    </div>
</div>
</div>
        </div>

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

	data: function () {
		return {
			newSocialmedialink: {
				'type': '',
				'url': ''
			}
		}
	},

	methods: {
		editSocialmedia: function () {
			this.$refs.socialmedia.open ();
		},
		addLink: function (e) {
			e.preventDefault ();
			if (!this.newSocialmedialink || !this.newSocialmedialink.type || !this.newSocialmedialink.url) return;

			this.performer.socialmedia.push ({
				type: this.newSocialmedialink.type,
				url: this.newSocialmedialink.url,
			});

			this.newSocialmedialink = {
				type: '',
				url: '',
			};
		},
		removeLink: function (socialmedia) {
			this.performer.socialmedia.$remove (socialmedia);
		}
	}
}
</script>