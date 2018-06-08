<?php $view->script('admin-category-index',
    'spqr/eventlist:app/bundle/admin/category-index.js', ['vue']); ?>

<div id="categories" class="uk-form" v-cloak>
	<div class="uk-margin uk-flex uk-flex-space-between uk-flex-wrap"
	     data-uk-margin>
		<div class="uk-flex uk-flex-middle uk-flex-wrap" data-uk-margin>
			<h2 class="uk-margin-remove"
			    v-if="!selected.length">{{ '{0} %count% Categories|{1} %count% Category|]1,Inf[ %count% Categories' | transChoice count {count:count} }}</h2>
			<template v-else>
				<h2 class="uk-margin-remove">{{ '{1} %count% Category selected|]1,Inf[ %count% Categories selected' | transChoice selected.length {count:selected.length} }}</h2>
				<div class="uk-margin-left">
					<ul class="uk-subnav pk-subnav-icon">
						<li>
							<a class="pk-icon-check pk-icon-hover"
							   title="{{ Publish | trans }}"
							   data-uk-tooltip="{delay: 500}"
							   @click="status(2)"></a>
						</li>
						<li>
							<a class="pk-icon-block pk-icon-hover"
							   title="{{ Unpublish | trans }}"
							   data-uk-tooltip="{delay: 500}"
							   @click="status(3)"></a>
						</li>
						<li>
							<a class="pk-icon-copy pk-icon-hover"
							   title="Copy"
							   data-uk-tooltip="{delay: 500}"
							   @click="copy"></a>
						</li>
						<li>
							<a class="pk-icon-delete pk-icon-hover"
							   title="Delete"
							   data-uk-tooltip="{delay: 500}"
							   @click="remove"
							   v-confirm="'Delete Categories?'"></a>
						</li>
					</ul>
				</div>
			</template>
			<div class="pk-search">
				<div class="uk-search">
					<input class="uk-search-field" type="text"
					       v-model="config.filter.search" debounce="300">
				</div>
			</div>
		</div>
		<div data-uk-margin>
			<a class="uk-button uk-button-primary"
			   :href="$url.route('admin/eventlist/category/edit')">{{ 'Add Category' | trans }}</a>
		</div>
	</div>
	<div class="uk-overflow-container">
		<table class="uk-table uk-table-hover uk-table-middle">
			<thead>
			<tr>
				<th class="pk-table-width-minimum">
					<input type="checkbox"
					       v-check-all:selected.literal="input[name=id]" number>
				</th>
				<th class="pk-table-min-width-200"
				    v-order:title="config.filter.order">{{ 'Title' | trans }}
				</th>
				<th class="pk-table-width-100 uk-text-center">
					<input-filter :title="$trans('Status')"
					              :value.sync="config.filter.status"
					              :options="statusOptions"></input-filter>
				</th>
			</tr>
			</thead>
			<tbody>
			<tr class="check-item" v-for="category in categories"
			    :class="{'uk-active': active(category)}">
				<td><input type="checkbox" name="id" :value="category.id"></td>
				<td>
					<a :href="$url.route('admin/eventlist/category/edit', { id: category.id })">{{ category.title }}</a>
				</td>
				<td class="uk-text-center">
					<a :title="getStatusText(category)" :class="{
                                'pk-icon-circle': category.status == 0,
                                'pk-icon-circle-warning': category.status == 1,
                                'pk-icon-circle-success': category.status == 2 && category.published,
                                'pk-icon-circle-danger': category.status == 3,
                                'pk-icon-schedule': category.status == 2 && !category.published
                            }" @click="toggleStatus(category)"></a>
				</td>
			</tr>
			</tbody>
		</table>
	</div>

	<h3 class="uk-h1 uk-text-muted uk-text-center"
	    v-show="categories && !categories.length">{{ 'No Categories found.' | trans }}</h3>
	<v-pagination :page.sync="config.page" :pages="pages"
	              v-show="pages > 1 || page > 0"></v-pagination>
</div>