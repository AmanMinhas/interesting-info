<?php

class ArticleController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        // action body
    }

    public function newAction()
    {
        // action body
        $this->view->headScript()->appendFile($this->view->baseUrl().'/js/new-article.js');
        $this->view->headLink()->appendStylesheet($this->view->baseUrl().'/css/new-article.css');

        $newArticle = new Application_Form_NewArticleForm();
        
        $request = $this->getRequest();
        if($request->isPost()) {
            if($newArticle->isValid($_POST)) {
                $params = $request->getPost();
                $this->_forward('create',null,null,array('params'=> $params));
            }
        }

        $this->view->newArticle = $newArticle;
    }

    public function createAction()
    {
        $params = $this->_getParam('params');
        $userInfo = Zend_Auth::getInstance()->getStorage()->read();

        $article = new Application_Model_DbTable_Article;

        $row = array();
        foreach ($params as $key=>$val) {
            switch($key) {
                case "titleElement" :
                    $row["title"] = $val; 
                    break;
                case "descriptionElement" :
                    $row["article_text"] = $val; 
                    break;
                default :
                    break;
            }
        }

        $row["uid"] = $userInfo->id; // get from session
        $row["published"] = 1;
        $row["user_published"] = $userInfo->id ;

        $article->insert($row);
    }


}





