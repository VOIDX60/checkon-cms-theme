<div class="cms-mobile-menu-toggle-wrapper"></div>

<div class="fill-height cms-menu cms-panel cms-panel-layout" id="cms-menu" data-layout-type="border" aria-expanded="false">
	<div class="cms-menu__header">
		<a
			class="checkon-admin-brand"
			href="$BaseHref/admin/accounts"
			aria-label="CheckOn administration"
		>
			<span class="checkon-admin-brand__mark">
				<img src="$resourceURL('voidx60/checkon-cms-theme:client/images/logo.png')" alt="">
			</span>
			<span class="checkon-admin-brand__copy">
				<strong>CheckOn</strong>
				<small>Admin console</small>
			</span>
		</a>
		<% include SilverStripe\\Admin\\LeftAndMain_MenuStatus %>
	</div>

	<div class="flexbox-area-grow panel--scrollable panel--triple-toolbar cms-panel-content">
		<% include SilverStripe\\Admin\\LeftAndMain_MenuList %>
	</div>

	<div class="toolbar toolbar--south cms-panel-toggle">
		<% include SilverStripe\\Admin\\LeftAndMain_MenuToggle %>
	</div>
</div>

<button class="fill-height fill-width cms-menu-mobile-overlay" aria-controls="cms-menu" aria-expanded="false"></button>
