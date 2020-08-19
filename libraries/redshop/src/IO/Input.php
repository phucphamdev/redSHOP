<?php
/**
 * @package     RedShop
 * @subpackage  Workflow
 *
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Redshop\IO;

defined('_JEXEC') or die;

/**
 * Cart Workflow
 *
 * @since  __DEPLOY_VERION__
 */

class Input
{
    public static function get($name, $default = null, $filter = 'unknown') {
        return \Joomla\CMS\Factory::getApplication()->input->get($name, $default, $filter);
    }

    public static function getArray($type) {
        switch (strtolower($type)) {
            case 'post':
                return \Joomla\CMS\Factory::getApplication()->input->post->getArray();
            case 'get':
                return \Joomla\CMS\Factory::getApplication()->input->get->getArray();
            default:
                return \Joomla\CMS\Factory::getApplication()->input->request->getArray();
        }
    }
}