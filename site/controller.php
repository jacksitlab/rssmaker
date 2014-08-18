<?php
/**
* @package Joomla.Administrator
* @subpackage com_helloworld
*
* @copyright Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
* @license GNU General Public License version 2 or later; see LICENSE.txt
*/
 
// No direct access to this file
defined('_JEXEC') or die;
 
// import Joomla controller library
jimport('joomla.application.component.controller');
require_once(JPATH_ADMINISTRATOR.'/'.'components'.'/'.'com_rssmaker'.'/'.'classes'.'/'.'FeedTypes.php');
require_once(JPATH_ADMINISTRATOR.'/'.'components'.'/'.'com_rssmaker'.'/'.'classes'.'/'.'class.content.php');
 
/**
 * Hello World Component Controller
 *
 * @since   0.0.1
 */
class RSSMakerController extends JControllerLegacy
{
	public function show()
	{
		$mainframe =&JFactory::getApplication('site');
		$id = intval(JRequest::getVar('id',0));
		if($id>0)
		{
	
			$cfg = $this->getConfig($id);
			if($cfg!=null)
			{
				$link = JURI::base().$_SERVER['REQUEST_URI'];
				$rss=new RSS2FeedWriter();
				$rss->setTitle($cfg->title);
				if(strlen($cfg->link)<=0)
					$cfg->link = JURI::base().'index.php?option=com_rssmaker&id='.$id;
				$rss->setLink($cfg->link);
				$rss->setDescription($cfg->desc);
				  
				//Image title and link must match with the 'title' and 'link' channel elements for RSS 2.0
				//$rss->setImage('Testing the RSS writer class','http://www.ajaxray.com/projects/rss','http://www.rightbrainsolution.com/_resources/img/logo.png');
				  
				//Use core setChannelElement() function for other optional channels
				$rss->setChannelElement('language', 'de');
				$rss->setChannelElement('pubDate', date(DATE_RSS, time()));
				
				$articles=Content::GetContent($cfg->cat_id,$cfg->nums);
				if(count($articles)>0)
				{
					foreach($articles as $article)
					{
						$newItem = $rss->createNewItem();
  
						//Add elements to the feed item
						//Use wrapper functions to add common feed elements
						$newItem->setTitle($article->title);
						$newItem->setLink($article->link);
						//The parameter is a timestamp for setDate() function
						$newItem->setDate($article->publish_up);
						$newItem->setDescription( $article->text);
						//$newItem->setEncloser('http://www.attrtest.com', '1283629', 'audio/mpeg');
						//Use core addElement() function for other supported optional elements
						//$newItem->addElement('author', 'admin@ajaxray.com (Anis uddin Ahmad)');
						//Attributes have to passed as array in 3rd parameter
						$newItem->addElement('guid', $article->link,array('isPermaLink'=>'true'));
						$rss->addItem($newItem);
					}
				}
				//header('Content-type: application/xml');
				$rss->generateFeed();
			}
		}
		$mainframe->close();
	}
	public function getConfig($id =0)
	{
		$db = &JFactory::getDBO();
		$query=$db->getQuery(true);
		$query='SELECT * FROM #__rssmaker_feeds WHERE id = '.$id;
		$db->setQuery($query);
		$data= $db->LoadObjectList();
		if(is_array($data) && count($data)>0)
			return $data[0];
		else
			return null;
	}

}