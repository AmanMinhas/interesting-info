<?php

class Application_Model_DbTable_Article extends Zend_Db_Table_Abstract
{

    protected $_name = 'Article';

    public function getAll() {
      $select   = $this ->select()
                        ->where("published = ? ", 1)
                        ->order("date_published DESC");
      
      $articles   = $this->fetchAll($select);

      return $articles;                        
    }

    public function getArticlesByTags($tags = array()) {
    	
    	$select 	= $this->select()
    					->setIntegrityCheck(false)
   						->from(array("a"=>$this->_name))
   						->join(array("aam"=>"ArticleAttributeMap"),"aam.article_id = a.id",array())
              ->where('aam.published = ?',1)
   						->where('a.published = ?',1);
      
      if(!empty($tags)) {
        $select ->where("aam.value IN (?)",$tags);
      }
      $select ->group('a.id');
      $select ->order('a.date_published DESC');
      
      $articles 	= $this->fetchAll($select);
   		
   		return $articles;
   	}
}

