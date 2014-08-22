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
        $id = $this->getRequest()->getParam('id');
        if(!isset($id)){
            $this->_redirect('/Article/new');
        }

        $articles = new Application_Model_DbTable_Article;

        $select = $articles->select()->where('id = ?',$id);
        $row    = $articles->fetchRow("id = ".$id);

        if(is_null($row)){
            $this->view->article = false;
        } else {
            $this->view->article = $row;
        }
    }

    public function newAction()
    {
        // action body
        $this->view->headScript()->appendFile($this->view->baseUrl().'/js/new-article.js');
        $this->view->headLink()->appendStylesheet($this->view->baseUrl().'/css/new-article.css');

        // $newArticle = new Application_Form_NewArticleForm();
        $request = $this->getRequest();
        if($request->isPost()) {
            echo "here";
            $params = $this->getRequest()->getPost();
            print_r($params);
            $this->_forward('create',null,null,array('params'=> $params));
        }

        // $this->view->newArticle = $newArticle;
    }

    public function createAction()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);

        $params     = $this->_getParam('params');
        $userInfo   = Zend_Auth::getInstance()->getStorage()->read();
        $tags       = array();

        $articles               = new Application_Model_DbTable_Article;
        $articleAttributeMap    = new Application_Model_DbTable_ArticleAttributeMap;

        $row = array();
        foreach ($params as $key=>$val) {
            switch($key) {
                case "title" :
                    $row["title"] = $val; 
                    break;
                case "description" :
                    $row["article_text"] = $val; 
                    break;
                case "tags" :
                    $tags = $val;
                    break;
                default :
                    break;
            }
        }

        $row["uid"] = $userInfo->id; // get from session
        $row["published"] = 1;
        $row["user_published"] = $userInfo->id ;

        $id = $articles->insert($row); // $id will be inserted row's id.

        foreach ($tags as $tag) {
            $row = array(
                    "attr_id"           => 1 , // attr_id 1 is for tag
                    "article_id"        => $id,
                    "value"             => $tag,
                    "published"         => 1 ,
                    "date_published"    => new Zend_Db_Expr("NOW()")       
                );

            $articleAttributeMap->insert($row);
        }
    }


}





