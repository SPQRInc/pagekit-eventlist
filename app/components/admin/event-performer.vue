<template>
    <form class="uk-form uk-form-stacked" v-validator="formPerformer"
    @submit.prevent = "add | valid">
    <div class="uk-form-row">
        <div class="uk-grid" data-uk-margin>
            <div class="uk-width-large-1-4">
                <input class="uk-input-large"
                       type="text"
                       placeholder="{{ 'Name' | trans }}"
                       name="name"
                       v-model="newPerformer.name"
                       v-validate:required>
                <p class="uk-form-help-block uk-text-danger" v-show="formPerformer.value.invalid">
                    {{ 'Invalid value.' | trans }}</p>
            </div>
            <div class="uk-width-large-1-4">
                <div class="uk-form-controls">
                    <performer-description :performer.sync="newPerformer" :data="data"></performer-description>
                </div>
            </div>
            <div class="uk-width-large-1-4">
                <div class="uk-form-controls">
                    <performer-social :performer.sync="newPerformer" :data="data"></performer-social>
                </div>
            </div>
            <div class="uk-width-large-1-4">
                <div class="uk-form-controls">
                        <span class="uk-align-right">
                            <button class="uk-button" @click.prevent = "add | valid">
                                {{ 'Add' | trans }}
                            </button>
                </span>
            </div>
        </div>
    </div>
</div>
        </form>
<hr/>
<div class="uk-alert"
     v-if="!data.event.performer.length">{{ 'You can add your first performer using the input field above. Go ahead!' | trans }}
</div>
<div class="uk-form-row" v-if="data.event.performer.length" v-for="performer in data.event.performer">
<div class="uk-grid" data-uk-margin>
    <div class="uk-width-large-1-4">
        <input id="form-performer{{$index}}" class="uk-input-large"
               type="text"
               placeholder="{{ 'Name' | trans }}"
               v-model="performer.name">
    </div>
    <div class="uk-width-large-1-4">
        <div class="uk-form-controls">
            <performer-description :performer.sync="performer" :data="data"></performer-description>
        </div>
    </div>
    <div class="uk-width-large-1-4">
        <div class="uk-form-controls">
            <performer-social :performer.sync="performer" :data="data"></performer-social>
        </div>
    </div>
    <div class="uk-width-large-1-4">
                <span class="uk-align-right">
                    <button class="uk-button uk-button-danger" @click.prevent = "remove(performer)">
                        <i class="uk-icon-remove"></i>
                    </button>
    </span>
</div>
</div>
        </div>
        </template>

<script>

module.exports = {

	section: {
		label: 'Performer',
		priority: 100
	},

	props: ['event', 'data', 'form'],

	data: function () {
		return {
			newPerformer: {
				'name': '',
				'description': '',
				'socialmedia': [],
			}
		}
	},

	methods: {
		add: function add (e) {

			e.preventDefault ();
			if (!this.newPerformer || !this.newPerformer.name) return;

			this.data.event.performer.push ({
				name: this.newPerformer.name,
				description: this.newPerformer.description,
				socialmedia: this.newPerformer.socialmedia
			});

			this.newPerformer = {
				name: '',
				description: '',
				socialmedia: [],
			};

		},
		remove: function (performer) {
			this.data.event.performer.$remove (performer);
		}
	},
	components: {
		'performer-description': require ('./performer-description.vue'),
		'performer-social': require ('./performer-social.vue')
	}

};

window.event.components['event-performer'] = module.exports;

</script>