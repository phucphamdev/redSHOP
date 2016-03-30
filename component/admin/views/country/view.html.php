<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

jimport('joomla.html.pagination');

class RedshopViewCountry extends RedshopView
{
	public function display($tpl = null)
	{
		global $context;

		$context  = 'country_id';
		$app      = JFactory::getApplication();
		$document = JFactory::getDocument();
		$uri      = JFactory::getURI();

		$document->setTitle(JText::_('COM_REDSHOP_COUNTRY'));

		JToolBarHelper::title(JText::_('COM_REDSHOP_COUNTRY_MANAGEMENT'), 'redshop_country_48');
		JToolbarHelper::addNew();
		JToolbarHelper::EditList();
		JToolbarHelper::deleteList();
		
		$state		  = $this->get('State');
		$filter_order     = $app->getUserStateFromRequest($context . 'filter_order', 'filter_order', 'country_id');
		$filter_order_Dir = $app->getUserStateFromRequest($context . 'filter_order_Dir', 'filter_order_Dir', '');

		$lists['order']     = $filter_order;
		$lists['order_Dir'] = $filter_order_Dir;

		$fields             = $this->get('Data');
		$pagination         = $this->get('Pagination');

		$this->user         = JFactory::getUser();
		$this->pagination   = $pagination;
		$this->fields       = $fields;
		$this->lists        = $lists;
		$this->request_url  = $uri->toString();
		$this->filter       = $state->get('filter');

		parent::display($tpl);
	}
}
