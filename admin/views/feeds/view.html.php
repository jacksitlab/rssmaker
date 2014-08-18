<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla view library
jimport('joomla.application.component.view');
 
/**
 * HelloWorlds View
 */
class RSSMakerViewFeeds extends JViewLegacy
{
        /**
         * HelloWorlds view display method
         * @return void
         */
        function display($tpl = null) 
        {
		        // Get data from the model
                $items = $this->get('Items');
                $pagination = $this->get('Pagination');
 
                // Check for errors.
                if (count($errors = $this->get('Errors'))) 
                {
                        JError::raiseError(500, implode('<br />', $errors));
                        return false;
                }
                // Assign data to the view
                $this->items = $items;
                $this->pagination = $pagination;
 
                // Display the template
				$this->addToolBar();
        		$this->setDocument();
                parent::display($tpl);
				
		}
		  protected function addToolBar() 
        {
                JToolBarHelper::title(JText::_('RSSMaker - NewsFeeds'));
                  JToolBarHelper::addNew('feed.add');
                JToolBarHelper::editList('feed.edit');
             JToolBarHelper::deleteList('','feed.delete');
        }
		 /**
         * Method to set up the document properties
         *
         * @return void
         */
        protected function setDocument() 
        {
                $document = JFactory::getDocument();
                $document->setTitle(JText::_('NewsFeeds'));
        }
}