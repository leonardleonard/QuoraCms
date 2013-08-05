<?php
class AnswerModel extends RelationModel{
		protected $_link=array(
		'question'=>array(
			'mapping_type'=>BELONGS_TO,
			'mapping_name'=>'question',
			'foreign_key'=>'qid',
		),
		'question'=>array(
			'mapping_type'=>BELONGS_TO,
			'mapping_name'=>'user',
			'foreign_key'=>'Uid',
		),
	);
}
?>