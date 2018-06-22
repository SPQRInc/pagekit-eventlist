<?php $view->script('event-details',
    'spqr/eventlist:app/bundle/event-details.js', 'vue'); ?>
<div id="event-details">
	<div class="uk-grid" data-uk-grid-margin="">
		<div class="uk-width-medium-2-3 uk-row-first">
			<h1>{{event.title}}</h1>
			<div v-html="event.content"></div>
		</div>
	</div>
</div>