<?php

class YtController extends Zend_Controller_Action
{

    public function init()
    {
    	Zend_Loader_Autoloader::getInstance();
    	/* Initialize action controller here */
    }

    public function indexAction()
    {
        // action body
    }

		public function searchAction() {
			$form = $this->getSearchForm();
			$this->view->form = $form;
		}
		
		public function showAction() {
			$form = $this->getSearchForm();
			
			if($form->isValid($_POST)){ // OR if ($this->_request->isPost())
			
				$yt = new Zend_Gdata_YouTube();
			
				$search = $form->getValue("search"); 
				$query = $yt->newVideoQuery();
				$query->setQuery($search);
			
				$this->view->feed = $yt->getVideoFeed($query);
				
				$feed = $yt->getVideoFeed($query);
				
				$feeds = array();
				
				foreach ($feed as $k=>$v) {
					$feeds[$k]['videoId']		= $v->getVideoId();
					$feeds[$k]['thumbnail'] = $v->mediaGroup->thumbnail[0]->url;
					$feeds[$k]['title'] 		= (string) $v->mediaGroup->title;
					$feeds[$k]['desc'] 			= (string) $v->mediaGroup->description;
		
					foreach ($v->mediaGroup->content as $c) {
						if($c->type = "application/x-shockwave-flash"){
							$feeds[$k]['flashUrl'] = $c->url; break;
						}
					}
				}
				$this->view->feeds = $feeds;	
			} else {
				$this->view->error = "invalid Form";
			}
		}
		
		public function getSearchForm() {
			$form = new Zend_Form;
			$form->setAction("show");
			$form->setMethod("post");
			$form->setName("searchForm");
		
			$searchElement = new Zend_Form_Element_Text("search");	
			$searchElement->setLabel("Search : ");
			$searchElement->setRequired(true);
		
			$submitElement = new Zend_Form_Element_Submit("submit");
			$submitElement->setLabel("Search");
			
			$form->addElement($searchElement);
			$form->addElement($submitElement);
			
			return $form;
		}

}

