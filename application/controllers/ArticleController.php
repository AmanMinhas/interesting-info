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

    public function newTestAction() {
        $this->view->headScript()->appendFile($this->view->baseUrl().'/js/new-article.js');
        $this->view->headScript()->appendFile($this->view->baseUrl().'/js/FileUpload/jquery.form.min.js');
        $this->view->headLink()->appendStylesheet($this->view->baseUrl().'/css/new-article.css');

        if($this->getRequest()->isPost) {

        if(isset($_FILES["FileInput"]) && $_FILES["FileInput"]["error"]== UPLOAD_ERR_OK)
        {
            ############ Edit settings ##############
            // $UploadDirectory    = '/home/website/file_upload/uploads/'; //specify upload directory ends with / (slash)
            $UploadDirectory    = APPLICATION_PATH.'/../upload/'; //specify upload directory ends with / (slash)
            // $UploadDirectory    = "/img/";
            ##########################################
            
            /*
            Note : You will run into errors or blank page if "memory_limit" or "upload_max_filesize" is set to low in "php.ini". 
            Open "php.ini" file, and search for "memory_limit" or "upload_max_filesize" limit 
            and set them adequately, also check "post_max_size".
            */
            
            //check if this is an ajax request
            if (!isset($_SERVER['HTTP_X_REQUESTED_WITH'])){
                die('HTTP_X_REQUESTED_WITH not set');
            }
            
            
            //Is file size is less than allowed size.
            if ($_FILES["FileInput"]["size"] > 5242880) {
                die("File size is too big!");
            }
            
            //allowed file type Server side check
            switch(strtolower($_FILES['FileInput']['type']))
                {
                    //allowed file types
                    case 'image/png': 
                    case 'image/gif': 
                    case 'image/jpeg': 
                    case 'image/pjpeg':
                    case 'text/plain':
                    case 'text/html': //html file
                    case 'application/x-zip-compressed':
                    case 'application/pdf':
                    case 'application/msword':
                    case 'application/vnd.ms-excel':
                    case 'video/mp4':
                        break;
                    default:
                        die('Unsupported File!'); //output error
            }
            
            $File_Name          = strtolower($_FILES['FileInput']['name']);
            $File_Ext           = substr($File_Name, strrpos($File_Name, '.')); //get file extention
            $Random_Number      = rand(0, 9999999999); //Random number to be added to name.
            $NewFileName        = $Random_Number.$File_Ext; //new file name
            
            if(move_uploaded_file($_FILES['FileInput']['tmp_name'], $UploadDirectory.$NewFileName ))
               {
                // do other stuff 
                       die('Success! File Uploaded.');
            }else{
                die('error uploading File!');
            }
            
        }
        else
        {
            die('Something wrong with upload! Is "upload_max_filesize" set correctly?');
        }
        }
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
        $userActivityLog        = new Application_Model_DbTable_UserActivityLog;

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

        $article_id = $articles->insert($row); // $id will be inserted row's id.

        //Add Tags to article attribute map
        foreach ($tags as $tag) {
            $row = array(
                    "attr_id"           => 1 , // attr_id 1 is for tag
                    "article_id"        => $article_id,
                    "value"             => $tag,
                    "published"         => 1 ,
                    "date_published"    => new Zend_Db_Expr("NOW()")       
                );

            $articleAttributeMap->insert($row);
        }

        //Add row to Activity Log table
        $insert_arr = array(
                "user_id"           => $userInfo->id,
                "activity_on"       => "Article",
                "activity_url"      => "/Article?id=".$article_id,
                "activity_text"     => "Created New Article.",
                "published"         => 1,
                "date_published"    => new Zend_Db_Expr("NOW()")
            );
        $userActivityLog->insert($insert_arr);

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

    public function searchArticlesByTagsAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);

        $article            = new Application_Model_DbTable_Article;
        $userTagSearchMap   = new Application_Model_DbTable_UserTagSearchMap;

        $curr_user  = Zend_Auth::getInstance()->getStorage()->read();

        $tags       = $this->getRequest()->getPost('tags'); // received as comma separated text.
        if(empty($tags)) {
            $tags   = array();
        } else {
            $tags   = explode(",",$tags);
        }
        //Create Record in UserTagSearchMap
        
        //Get Tags that were searched last time as well
        $lastSearched       = $userTagSearchMap->getLastSearchedByUser($curr_user->id);
        $lastSearchedTags   = array();
        foreach($lastSearched as $ls) {
            $lastSearchedTags[]     = $ls->tag;
        }
        //Unpublish rows that are not searched.
        if(!empty($lastSearchedTags)) {
            $where[]    = $userTagSearchMap->getAdapter()->quoteInto("published = ?",1);
            if(!empty($tags)) {
                $where[]    = $userTagSearchMap->getAdapter()->quoteInto("tag NOT IN (?)",$tags);
            }

            $userTagSearchMap->unpublish($where);
        }

        //Insert New Rows
        foreach($tags as $tag) {
            if(in_array($tag, $lastSearchedTags)) continue;

            $row    = array(
                "user_id"           => $curr_user->id,
                "tag"               => $tag,
                "published"         => 1,
                "date_published"    => new Zend_Db_Expr('NOW()'),
            );

            $userTagSearchMap->insert($row);
        }

        //Get Articles
        $articles   = $article->getArticlesByTags($tags);

        echo json_encode($articles->toArray());
    }

    /*Remove this code and use Zend File Upload. Still getting cannot write to destination error. */
    public function testAction() {
        $this->_helper->layout->disableLayout();
        // $this->_helper->viewRenderer->setNoRender(TRUE);
        
        // $userInfo = Zend_Auth::getInstance()->getStorage()->read();
        
        // print_r($userInfo);   
        // <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
        // $this->view->headScript()->appendFile("http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js");
        // $this->view->headScript()->appendFile($this->view->baseUrl().'/js/FileUpload/jquery.form.min.js');
        if($this->getRequest()->isPost()) {
        
            if(isset($_FILES["FileInput"]) && $_FILES["FileInput"]["error"]== UPLOAD_ERR_OK)
            {

                ############ Edit settings ##############
                // $UploadDirectory    = 'F:/Websites/file_upload/uploads/'; //specify upload directory ends with / (slash)
                $UploadDirectory    = APPLICATION_PATH.'/../upload/'; //specify upload directory ends with / (slash)
                echo $UploadDirectory;
                ##########################################
                
                /*
                Note : You will run into errors or blank page if "memory_limit" or "upload_max_filesize" is set to low in "php.ini". 
                Open "php.ini" file, and search for "memory_limit" or "upload_max_filesize" limit 
                and set them adequately, also check "post_max_size".
                */
                
                //check if this is an ajax request
                if (!isset($_SERVER['HTTP_X_REQUESTED_WITH'])){
                    die();
                }
                
                
                //Is file size is less than allowed size.
                if ($_FILES["FileInput"]["size"] > 5242880) {
                    die("File size is too big!");
                }
                
                //allowed file type Server side check
                switch(strtolower($_FILES['FileInput']['type']))
                    {
                        //allowed file types
                        case 'image/png': 
                        case 'image/gif': 
                        case 'image/jpeg': 
                        case 'image/pjpeg':
                        case 'text/plain':
                        case 'text/html': //html file
                        case 'application/x-zip-compressed':
                        case 'application/pdf':
                        case 'application/msword':
                        case 'application/vnd.ms-excel':
                        case 'video/mp4':
                            break;
                        default:
                            die('Unsupported File!'); //output error
                }
                
                $File_Name          = strtolower($_FILES['FileInput']['name']);
                $File_Ext           = substr($File_Name, strrpos($File_Name, '.')); //get file extention
                $Random_Number      = rand(0, 9999999999); //Random number to be added to name.
                $NewFileName        = $Random_Number.$File_Ext; //new file name
                
                if(move_uploaded_file($_FILES['FileInput']['tmp_name'], $UploadDirectory.$NewFileName ))
                   {
                    die('Success! File Uploaded.');
                }else{
                    die('error uploading File!');
                }
                
            }
            else
            {
                die('Something wrong with upload! Is "upload_max_filesize" set correctly?');
            }          
        }
    }
}