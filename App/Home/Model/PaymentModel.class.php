<?php
namespace Home\Model;
use Think\Model;
use Think\Model\RelationModel;

class PaymentModel extends RelationModel{
	protected $connection = 'DB_CONFIG2';

	protected $_link = array(
		'user'=>array(
			'mapping_type'=> self:: BELONGS_TO ,
			'foreign_key'=>'uid',
			'mapping_fields'=>'username,nickname',
			'as_fields'=>'username,nickname',
			),
		);
}