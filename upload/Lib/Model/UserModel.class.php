<?php
class UserModel extends RelationModel{
		protected $_link=array(
		'question'=>array(
			'mapping_type'=>HAS_MANY,
			'mapping_name'=>'question',
			'foreign_key'=>'uid',
		),
		'topic'=>array(
			'mapping_type'=>MANY_TO_MANY,
			'mapping_name'=>'topic',
			'relation_foreign_key'=>'topicid',
			'foreign_key'=>'uid',
			'relation_table'=>'topicfocus',
		),
		'action'=>array(
			'mapping_type'=>MANY_TO_MANY,
			'mapping_name'=>'action',
			'relation_foreign_key'=>'hisid',
			'foreign_key'=>'myid',
			'relation_table'=>'follow',
		),
	);
}
?>