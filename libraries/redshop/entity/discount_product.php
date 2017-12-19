<?php
/**
 * @package     Redshop.Library
 * @subpackage  Entity
 *
 * @copyright   Copyright (C) 2012 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die;

/**
 * Discount Product Entity
 *
 * @package     Redshop.Library
 * @subpackage  Entity
 * @since       __DEPLOY_VERSION__
 */
class RedshopEntityDiscount_Product extends RedshopEntity
{
	/**
	 * @var RedshopEntitiesCollection
	 */
	protected $shopperGroups;

	/**
	 * @var RedshopEntitiesCollection
	 */
	protected $categories;

	/**
	 * Method for get shopper groups associate with this discount
	 *
	 * @return  RedshopEntitiesCollection
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function getShopperGroups()
	{
		if (null === $this->shopperGroups)
		{
			$this->loadShopperGroups();
		}

		return $this->shopperGroups;
	}

	/**
	 * Method for get categories associate with this discount
	 *
	 * @return  RedshopEntitiesCollection
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function getCategories()
	{
		if (null === $this->categories)
		{
			$this->loadCategories();
		}

		return $this->categories;
	}

	/**
	 * Method for load categories associate with this discount
	 *
	 * @return  self
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	protected function loadCategories()
	{
		$this->categories = new RedshopEntitiesCollection;

		if (!$this->hasId() || empty($this->get('category_ids')))
		{
			return $this;
		}

		$categoryIds = explode(',', $this->get('category_ids'));

		foreach ($categoryIds as $categoryId)
		{
			$this->categories->add(RedshopEntityCategory::getInstance($categoryId));
		}

		return $this;
	}
}
