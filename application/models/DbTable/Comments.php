<?php

class Application_Model_DbTable_Comments extends Zend_Db_Table_Abstract
{

    protected $_name = 'Comments';

    /*
		Do some reading on this.
		http://framework.zend.com/manual/1.12/en/zend.db.table.relationships.html
		
		protected $_referenceMap	= array(
			"Article"	=> array(
				"columns"		=> array("id"),
				"refTableClass"	=> "Article",
				"refColumns"	=> array("id")
			)
		);

		Article.php mei bhi changes karne hai shayad.
		$_dependentTables	= array("Comments.php");
    */
}

