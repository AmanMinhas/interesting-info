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
        $this->view->headScript()->appendFile($this->view->baseUrl().'/js/new-article.js');
        $this->view->headLink()->appendStylesheet($this->view->baseUrl().'/css/new-article.css');

        $id = $this->getRequest()->getParam('id');
        if(!isset($id)){
            $this->_redirect('/Article/new');
        }


        $articles               = new Application_Model_DbTable_Article;
        $articleAttributeMap    = new Application_Model_DbTable_ArticleAttributeMap;

        $select     = $articles->select()->where('id = ?',$id);
        $article    = $articles->fetchRow("id = ".$id);

        if(is_null($article)){
            $this->view->article = false;
        } else {

            $select = $articleAttributeMap->select()
                        ->where('article_id = ?', $article->id)
                        ->where('published = ?',1) ;
            
            $articleAttributes   = $articleAttributeMap->fetchAll($select);
            
            $this->view->article            = $article;
            $this->view->articleAttributes  = $articleAttributes;
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

    public function ajaxAddCommentAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);

        $userInfo       = Zend_Auth::getInstance()->getStorage()->read();
        $article_id     = $this->getRequest()->getPost('article_id'); 
        $new_comment    = $this->getRequest()->getPost('comment'); 

        $comment = new Application_Model_DbTable_Comments;

        $row = array(
                "article_id"        => $article_id,
                "comment"           => $new_comment,
                "published"         => 1,
                "user_published"    => $userInfo->id,
                "date_published"    => new Zend_Db_Expr("NOW()")
            );

        echo $comment->insert($row);
        
    }

    public function getArticleCommentsAction(){
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);

        $article_id     = $this->getRequest()->getParam('article_id');

        $comment        = new Application_Model_DbTable_Comments;

        $select         = $comment->select()
                                    ->where("article_id = ? ", $article_id)
                                    ->where("published = ?",1)
                                    ->order("date_published DESC");

        $article_comments   = $comment->fetchAll($select);

        echo json_encode($article_comments->toArray());

    }

    public function getArticleCommentByIdAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);

        $comment_id = $this->getRequest()->getParam('comment_id');

        $comment    = new Application_Model_DbTable_Comments;

        $select     = $comment->select()
                                ->where("id = ?", $comment_id)
                                ->where("published = ?",1);

        $comment_row    = $comment->fetchRow($select);

        echo json_encode($comment_row->toArray());
    }

    public function testAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);
        
        $userInfo = Zend_Auth::getInstance()->getStorage()->read();
        
        print_r($userInfo);              
    }
}