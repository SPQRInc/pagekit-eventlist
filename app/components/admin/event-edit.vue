<template>
    <div class="uk-grid pk-grid-large pk-width-sidebar-large uk-form-stacked" data-uk-grid-margin>
        <div class="pk-width-content">
            <div class="uk-form-row">
                <input class="uk-width-1-1 uk-form-large" type="text" name="title" :placeholder="'Enter Title' | trans"
                       v-model="event.title" v-validate:required>
                <p class="uk-form-help-block uk-text-danger"
                   v-show="form.title.invalid">{{ 'Title cannot be blank.' | trans }}</p>
            </div>
        </div>
        <div class="pk-width-sidebar">
            <div class="uk-panel">
                <div class="uk-form-row">
                    <label for="form-image" class="uk-form-label">{{ 'Image' | trans }}</label>
                    <div class="uk-form-controls">
                        <input-image-meta :image.sync="event.data.image" class="pk-image-max-height"></input-image-meta>
                    </div>
                </div>
                <div class="uk-form-row">
                    <label for="form-slug" class="uk-form-label">{{ 'Slug' | trans }}</label>
                    <div class="uk-form-controls">
                        <input id="form-slug" class="uk-width-1-1" type="text" v-model="event.slug">
                    </div>
                </div>
                <div class="uk-form-row">
                    <label for="form-status" class="uk-form-label">{{ 'Status' | trans }}</label>
                    <div class="uk-form-controls">
                        <select id="form-status" class="uk-width-1-1" v-model="event.status">
                            <option v-for="(id, status) in data.statuses" :value="id">{{status}}</option>
                        </select>
                    </div>
                </div>
                <div class="uk-form-row">
                    <label for="form-category" class="uk-form-label">{{ 'Category' | trans }}</label>
                    <div class="uk-form-controls">
                        <select id="form-category" class="uk-width-1-1" v-model="event.category_id">
                            <option value="">{{ '- Assign -' | trans }}</option>
                            <option v-for="(id, category) in data.categories" :value="id">{{category.title}}</option>
                        </select>
                    </div>
                </div>
                <div class="uk-form-row">
                    <span class="uk-form-label">{{ 'Date' | trans }}</span>
                    <div class="uk-form-controls">
                        <input-date :datetime.sync="event.date"></input-date>
                    </div>
                </div>
                <div class="uk-form-row">
                    <label for="form-price" class="uk-form-label">{{ 'Price' | trans }}</label>
                    <div class="uk-form-controls">
                        <price-selector id="form-price" :price.sync="event.price"></price-selector>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

module.exports = {

	props: ['event', 'data', 'form'],

	section: {
		label: 'Event'
	},

	components: {
		'price-selector': require ('../price-selector.vue')
	}

};

</script>