<?php $view->script('admin-settings',
    'spqr/eventlist:app/bundle/admin/settings.js',
    ['vue']); ?>

<div id="settings" class="uk-form uk-form-horizontal" v-cloak>
	<div class="uk-grid pk-grid-large" data-uk-grid-margin>
		<div class="pk-width-sidebar">
			<div class="uk-panel">
				<ul class="uk-nav uk-nav-side pk-nav-large"
				    data-uk-tab="{ connect: '#tab-content' }">
					<li>
						<a><i class="pk-icon-large-settings uk-margin-right"></i> {{ 'General' | trans }}</a>
					</li>
					<li>
						<a><i class="pk-icon-large-settings uk-margin-right"></i> {{ 'Social Media' | trans }}</a>
					</li>
				</ul>
			</div>
		</div>
		<div class="pk-width-content">
			<ul id="tab-content" class="uk-switcher uk-margin">
				<li>
					<div class="uk-margin uk-flex uk-flex-space-between uk-flex-wrap"
					     data-uk-margin>
						<div data-uk-margin>
							<h2 class="uk-margin-remove">{{ 'General' | trans }}</h2>
						</div>
						<div data-uk-margin>
							<button class="uk-button uk-button-primary"
							        @click.prevent="save">{{ 'Save' | trans }}
							</button>
						</div>
					</div>
					<div class="uk-form-row">
						<label class="uk-form-label">{{ 'Sidebar-Class' | trans }}</label>
						<div class="uk-form-controls uk-form-controls-text">
							<p class="uk-form-controls-condensed">
								<input type="text"
								       v-model="config.sidebar_class">
							</p>
						</div>
					</div>
				</li>
				<li>
					<div class="uk-margin uk-flex uk-flex-space-between uk-flex-wrap"
					     data-uk-margin>
						<div data-uk-margin>
							<h2 class="uk-margin-remove">{{ 'Social Media' | trans }}</h2>
						</div>
						<div data-uk-margin>
							<button class="uk-button uk-button-primary"
							        @click.prevent="save">{{ 'Save' | trans }}
							</button>
						</div>
					</div>
					<form class="uk-form uk-form-stacked"
					      v-validator="formSocialmediaIcons"
					      @submit.prevent="add | valid">
						<div class="uk-form-row">
							<div class="uk-grid" data-uk-margin>
								<div class="uk-width-large-1-4">
									<input class="uk-input-large"
									       type="text"
									       placeholder="{{ 'Title' | trans }}"
									       name="title"
									       v-model="newSocialmediaicon.title"
									       v-validate:required>
									<p class="uk-form-help-block uk-text-danger"
									   v-show="formSocialmediaIcons.title.invalid">
										{{ 'Invalid value.' | trans }}</p>
								</div>
								<div class="uk-width-large-1-4">
									<input class="uk-input-large"
									       type="text"
									       placeholder="{{ 'Icon' | trans }}"
									       name="icon"
									       v-model="newSocialmediaicon.icon"
									       v-validate:required>
									<p class="uk-form-help-block uk-text-danger"
									   v-show="formSocialmediaIcons.icon.invalid">{{ 'Invalid value.' | trans }}</p>
								</div>
								<div class="uk-width-large-1-4">
									<input class="uk-input-large"
									       type="text"
									       placeholder="{{ 'Link Class' | trans }}"
									       name="href_class"
									       v-model="newSocialmediaicon.href_class"
									       v-validate:required>
									<p class="uk-form-help-block uk-text-danger"
									   v-show="formSocialmediaIcons.href_class.invalid">{{ 'Invalid value.' | trans }}</p>
								</div>
								<div class="uk-width-large-1-4">
									<span class="uk-align-right">
									   <button class="uk-button"
									           @click.prevent="add | valid">
													{{ 'Add' | trans }}
										</button>
									</span>
								</div>
							</div>
						</div>
					</form>
					<hr>
					<div class="uk-alert"
					     v-if="!config.socialmedia_icons.length">{{ 'You can add your first Social Media icon using the input field above. Go ahead!' | trans }}
					</div>
					<div class="uk-form-row"
					     v-if="config.socialmedia_icons.length"
					     v-for="socialmedia_icon in config.socialmedia_icons">
						<div class="uk-grid" data-uk-margin>
							<div class="uk-width-large-1-4">
								<input id="form-value{{$index}}"
								       class="uk-input-large"
								       type="text"
								       placeholder="{{ 'Title' | trans }}"
								       v-model="socialmedia_icon.title">
							</div>
							<div class="uk-width-large-1-4">
								<input id="form-value{{$index}}"
								       class="uk-input-large"
								       type="text"
								       placeholder="{{ 'Icon' | trans }}"
								       v-model="socialmedia_icon.icon">
							</div>
							<div class="uk-width-large-1-4">
								<input id="form-value{{$index}}"
								       class="uk-input-large"
								       type="text"
								       placeholder="{{ 'Link Class' | trans }}"
								       v-model="socialmedia_icon.href_class">
							</div>
							<div class="uk-width-large-1-4">
				                <span class="uk-align-right">
				                    <button class="uk-button uk-button-danger"
				                            @click.prevent="remove(socialmedia_icon)">
				                        <i class="uk-icon-remove"></i>
				                    </button>
				                </span>
							</div>
						</div>
					</div>
				</li>
			</ul>
		</div>
	</div>
</div>