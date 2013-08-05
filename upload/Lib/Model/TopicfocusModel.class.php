<?php
class TopicfocusModel extends RelationModel{
		protected $_link=array(
		'topicfocus'=>array(
			'mapping_type'=>BELONGS_TO,
			'mapping_name'=>'topic',
			'foreign_key'=>'topicid',
			//'mapping_fields'=>'uid',
		),
	);
}
?>