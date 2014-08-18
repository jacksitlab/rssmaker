<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
?>
<?php $base = JURI::base();
	$base=substr($base,0,strlen($base)-strlen("administrator/"));
?>
<?php foreach($this->items as $i => $item): ?>
        <tr class="row<?php echo $i % 2; ?>">
                <td>
                        <?php echo JHtml::_('grid.id', $i, $item->id); ?>
                </td>
                <td>
                        <?php echo $item->id; ?>
                </td> 
				<td>
                        <a href="<?php echo $base.'index.php?option=com_rssmaker&id='.$item->id; ?>"><?php echo $item->title; ?></a>
                </td>
                <td>
                        <?php echo $item->link; ?>
                </td>
				 <td>
                        <?php echo $item->desc; ?>
                </td>
				 <td>
                        <?php echo $item->cat_id; ?>
                </td> 
				<td>
                        <?php echo $item->nums; ?>
                </td>
        </tr>
<?php endforeach; ?>