(function($) {
	
	// Toggle advanced settings
	$('#wp-admin-bar-client_admin_advanced_settings_toggle').click(function(e) {
		
		e.preventDefault();
		
		var data = {
			'action': 'client_admin_toggle_advanced_setting'
		};
	
		jQuery.post(ajaxurl, data, false);
		
		$('body').toggleClass('client_admin_show_advanced_settings');
		$('body').toggleClass('client_admin_hide_advanced_settings');
		
	});
	
	// Hide Yoast from Yoast SEO metabox
	$('#wpseo_meta > h2 > span, #wpseo_meta > h3 > span').html('SEO');
	
	// Menu Button
	var menuItem = document.getElementById('toplevel_page_nav-menus');
	if (menuItem) {
		var menuLink = menuItem.getElementsByTagName('a')[0];
	}
	
	var appearanceItem = document.getElementById('menu-appearance');
	if (appearanceItem){
		var appearanceLink = appearanceItem.getElementsByTagName('a')[0];
	}
	
	var url = window.location.href;

	if (url.indexOf('nav-menus.php') > -1) {
		appearanceItem.className = appearanceItem.className.replace(/(?:^|\s)wp-has-current-submenu(?!\S)/g, '');
		appearanceLink.className = appearanceLink.className.replace(/(?:^|\s)wp-has-current-submenu(?!\S)/g, '');
		appearanceItem.className = appearanceItem.className + ' wp-not-current-submenu';
		
		menuItem.className = menuItem.className + ' current';
		menuLink.className = menuItem.className + ' current';
	}
	
	// Open admin bar site link in new tab
	var siteName = document.getElementById('wp-admin-bar-site-name');
	if (siteName) {
		var homeLink = siteName.getElementsByTagName('a')[0].target = '_blank';
	}

})(jQuery);
