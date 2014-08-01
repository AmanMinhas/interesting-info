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
        $newArticle = new Application_Form_NewArticleForm();
        $this->view->newArticle = $newArticle;
    }

}



