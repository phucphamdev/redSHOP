<?php
/**
 * @copyright Copyright (C) 2010 redCOMPONENT.com. All rights reserved.
 * @license   GNU/GPL, see license.txt or http://www.gnu.org/copyleft/gpl.html
 *            Developed by email@recomponent.com - redCOMPONENT.com
 *
 * redSHOP can be downloaded from www.redcomponent.com
 * redSHOP is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License 2
 * as published by the Free Software Foundation.
 *
 * You should have received a copy of the GNU General Public License
 * along with redSHOP; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */
defined('_JEXEC') or die('Restricted access');

$option    = JRequest::getVar('option', '', 'request', 'string');
$redhelper = new redhelper();
?>
<form action="<?php echo 'index.php?option=' . $option; ?>" method="post" name="adminForm" id="adminForm">
    <div id="editcell">
        <table class="adminlist">
            <thead>
            <tr>
                <th width="5%">
                    <?php echo JText::_('COM_REDSHOP_NUM'); ?>
                </th>
                <th width="5%">
                    <input type="checkbox" name="toggle" value=""
                           onclick="checkAll(<?php echo count($this->orderstatus); ?>);"/>
                </th>
                <th width="35%">
                    <?php echo JHTML::_('grid.sort', 'COM_REDSHOP_ORDERSTATUS_CODE', 'order_status_code', $this->lists['order_Dir'], $this->lists['order']); ?>
                </th>
                <th width="35%">
                    <?php echo JHTML::_('grid.sort', 'COM_REDSHOP_ORDERSTATUS_NAME', 'order_status_name', $this->lists['order_Dir'], $this->lists['order']); ?>
                </th>
                <th width="10%" nowrap="nowrap">
                    <?php echo JHTML::_('grid.sort', 'COM_REDSHOP_PUBLISHED', 'published', $this->lists['order_Dir'], $this->lists['order']); ?>
                </th>
                <th width="10%" nowrap="nowrap">
                    <?php echo JHTML::_('grid.sort', 'COM_REDSHOP_ID', 'order_status_id', $this->lists['order_Dir'], $this->lists['order']); ?>
                </th>

            </tr>
            </thead>
            <?php

            $k = 0;
            for ($i = 0, $n = count($this->orderstatus); $i < $n; $i++)
            {
                $row       = &$this->orderstatus[$i];
                $row->id   = $row->order_status_id;
                $link      = 'index.php?option=' . $option . '&view=orderstatus_detail&task=edit&cid[]=' . $row->order_status_id;
                $link      = $redhelper->sslLink($link);
                $published = JHtml::_('jgrid.published', $row->published, $i, '', 1);

                ?>
                <tr class="<?php echo "row$k"; ?>">
                    <td align="center">
                        <?php echo $this->pagination->getRowOffset($i); ?>
                    </td>
                    <td align="center">
                        <?php echo JHTML::_('grid.id', $i, $row->id); ?>
                    </td>
                    <td>
                        <a href="<?php echo $link; ?>"
                           title="<?php echo JText::_('COM_REDSHOP_EDIT_ORDERSTATUS'); ?>"><?php echo $row->order_status_code; ?></a>
                    </td>
                    <td>
                        <?php echo $row->order_status_name; ?>
                    </td>
                    <td align="center"><?php echo $published;?></td>
                    <td align="center"><?php echo $row->order_status_id; ?></td>
                </tr>
                <?php
                $k = 1 - $k;
            }
            ?>

            <tfoot>
            <td colspan="9">
                <?php echo $this->pagination->getListFooter(); ?>
            </td>
            </tfoot>
        </table>
    </div>

    <input type="hidden" name="view" value="orderstatus"/>
    <input type="hidden" name="task" value=""/>
    <input type="hidden" name="boxchecked" value="0"/>
    <input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>"/>
    <input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>"/>
</form>
