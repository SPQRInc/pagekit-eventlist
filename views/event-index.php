<?php $view->script('event-index', 'spqr/eventlist:app/bundle/event-index.js',
    ['vue']); ?>

<div id="eventlist-index" class="uk-form" v-cloak>
	<div class="uk-margin uk-flex uk-flex-space-between uk-flex-wrap"
	     data-uk-margin>
		<div class="uk-flex uk-flex-middle uk-flex-wrap" data-uk-margin>
			<h2 class="uk-margin-remove">{{ 'Events' | trans }}</h2>
		</div>
	</div>
	<div class="uk-overflow-container">
		<table class="uk-table uk-table-hover uk-table-striped uk-table-middle uk-table-condensed">
			<thead>
			<tr>
				<th class="pk-table-min-width-200">{{ 'Title' | trans }}
				</th>
				<th class="pk-table-width-100">{{ 'Date' | trans }}</th>
			</tr>
			</thead>
			<tbody>
			<tr class="check-item" v-for="event in events">
				<td>
					<a :href="$url.route(event.url)">{{ event.title }}</a>
				</td>
				<td>
					{{ event.date | date }}
				</td>
			</tr>
			</tbody>
		</table>
	</div>
	<h3 class="uk-h1 uk-text-muted uk-text-center"
	    v-show="events && !events.length">{{ 'No Events found.' | trans }}</h3>
</div>