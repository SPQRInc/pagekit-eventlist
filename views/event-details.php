<?php $view->script('event-details',
    'spqr/eventlist:app/bundle/event-details.js', 'vue'); ?>
<div id="event-details">
	<div class="uk-grid" data-uk-grid-margin="">
		<div class="uk-width-medium-2-3 uk-row-first">
			<article class="uk-article">
                <?php if ($image = $event->get('image')): ?>
					<img class="uk-responsive-height"
					     src="<?= $view->url()->getStatic($image['src']) ?>"
					     alt="<?= $image['alt'] ?>">
                <?php endif; ?>
				<h1 class="uk-article-title">{{ event.title }}</h1>
				<p class="uk-article-meta">{{ event.date | date }}</p>
				<div v-html="event.content"></div>
			</article>
		</div>
		<div class="uk-width-medium-1-3">
			<div class="uk-panel" v-bind:class="config.sidebar_class">
				<h3 class="uk-panel-title">{{ 'Info' | trans }}</h3>
				<div class="uk-form-row">
					<span class="uk-form-label">{{ 'Price' | trans }}: </span>
					{{ event.price }}€
				</div>
				<div class="uk-form-row">
					<span class="uk-form-label">{{ 'Date' | trans }}: </span>
					{{ event.date | date }}
				</div>
			</div>
		</div>
	</div>
</div>