<?php

class PublicEditLinkPlugin extends Omeka_Plugin_AbstractPlugin
{
	protected $_filters = array (
		'public_navigation_admin_bar'
	);
 
	/**
     * Adds item edit link if user logged in.
    */
	public function filterPublicNavigationAdminBar($navLinks) 
	{
		$user = current_user();
		if (isset($user)) {
			$acl = get_acl();
			if (isset(get_view()->item)) {
				if ($acl->isAllowed($user, get_view()->item, 'edit')) {
					$navLinks = $this->updateNavlinks($navLinks, 'Item');
				}
			} elseif (isset(get_view()->collection)) {
				if ($acl->isAllowed($user, get_view()->collection, 'edit')) {
					$navLinks = $this->updateNavlinks($navLinks, 'Collection');
				} 	
			} elseif (isset(get_view()->file)) {
				if ($acl->isAllowed($user, get_view()->file, 'edit')) {
					$navLinks = $this->updateNavlinks($navLinks, 'File');
				}
			}
		}
		return $navLinks;
	}
	
	public function updateNavlinks($navLinks, $type)
	{
		// Saves copy of last menu item - normally, the logout one - then removes it
		$lastLink = $navLinks;
		array_splice($lastLink, 0, -1);
		array_splice($navLinks, -1);
		
		// Creates new menu item, then adds it and finalize with saved last one
		$element = array(
			'label' => __('Edit') . ' ' . __($type),
			'uri' => admin_url('/' . strtolower($type) . 's/edit/' . metadata(strtolower($type), 'id'))
		);
		$navLinks[] = $element;
		return array_merge($navLinks, $lastLink);
	}
}
