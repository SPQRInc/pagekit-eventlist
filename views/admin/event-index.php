<?php $view->script('admin-event-index',
    'spqr/eventlist:app/bundle/admin/event-index.js', ['vue']); ?>

<div id="events" class="uk-form" v-cloak>
	<div class="uk-margin uk-flex uk-flex-space-between uk-flex-wrap"
	     data-uk-margin>
		<div class="uk-flex uk-flex-middle uk-flex-wrap" data-uk-margin>
			<h2 class="uk-margin-remove"
			    v-if="!selected.length">{{ '{0} %count% Events|{1} %count% Event|]1,Inf[ %count% Events' | transChoice count {count:count} }}</h2>
			<template v-else>
				<h2 class="uk-margin-remove">{{ '{1} %count% Event selected|]1,Inf[ %count% Events selected' | transChoice selected.length {count:selected.length} }}</h2>
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
							   @click="remove" v-confirm="'Delete Events?'"></a>
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
			   :href="$url.route('admin/eventlist/event/edit')">{{ 'Add Event' | trans }}</a>
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
				<th class="pk-table-width-100"
				    v-order:date="config.filter.order">{{ 'Date' | trans }}
				</th>
				<th class="pk-table-width-200 pk-table-min-width-200">{{ 'URL' | trans }}</th>
			</tr>
			</thead>
			<tbody>
			<tr class="check-item" v-for="event in events"
			    :class="{'uk-active': active(event)}">
				<td><input type="checkbox" name="id" :value="event.id"></td>
				<td>
					<a :href="$url.route('admin/eventlist/event/edit', { id: event.id })">{{ event.title }}</a>
				</td>
				<td class="uk-text-center">
					<a :title="getStatusText(event)" :class="{
                                'pk-icon-circle': event.status == 0,
                                'pk-icon-circle-warning': event.status == 1,
                                'pk-icon-circle-success': event.status == 2 && event.published,
                                'pk-icon-circle-danger': event.status == 3,
                                'pk-icon-schedule': event.status == 2 && !event.published
                            }" @click="toggleStatus(event)"></a>
				</td>
				<td>
					{{ event.date | date }}
				</td>
				<td class="pk-table-text-break">
					<a target="_blank" v-if="event.accessible && event.url"
					   :href="this.$url.route(event.url.substr(1))">{{ decodeURI(event.url) }}</a>
					<span v-if="!event.accessible && event.url">{{ decodeURI(event.url) }}</span>
					<span v-if="!event.url">{{ 'Disabled' | trans }}</span>
				</td>
			</tr>
			</tbody>
		</table>
	</div>

	<h3 class="uk-h1 uk-text-muted uk-text-center"
	    v-show="events && !events.length">{{ 'No Events found.' | trans }}</h3>
	<v-pagination :page.sync="config.page" :pages="pages"
	              v-show="pages > 1 || page > 0"></v-pagination>
</div>