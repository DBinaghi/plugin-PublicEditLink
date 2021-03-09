<?php

class PublicEditLinkPlugin extends Omeka_Plugin_AbstractPlugin
{
    protected $_hooks = array (
		'initialize'
	);

	protected $_filters = array (
		'public_navigation_admin_bar'
	);

	/**
     * Add the translations.
     */
    public function hookInitialize()
    {
        add_translation_source(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'languages');
    }
 
	/**
     * Adds item edit link if user logged in.
    */
	public function filterPublicNavigationAdminBar($navLinks) 
	{
		$user = current_user();
		if (isset($user) && isset(get_view()->item)) {
			$acl = get_acl();
			if ($acl->isAllowed($user, get_view()->item, 'edit')) {
				// Saves copy of last menu item - normally, the logout one - then removes it
				$lastLink = $navLinks;
				array_splice($lastLink, 0, -1);
				array_splice($navLinks, -1);
				
				// Creates new menu item, then adds it and finalize with saved last one
				$element = array(
					'label' => __('Edit current Item'),
					'uri' => admin_url('/items/edit/' . metadata('item', 'id'))
				);
				$navLinks[] = $element;
				$navLinks = array_merge($navLinks, $lastLink);
			}
		}
		return $navLinks;
	}
}