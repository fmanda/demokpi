<?php
	require_once '../src/models/BaseModel.php';

	class ModelUsers extends BaseModel{
		public static function getTableName(){
			return 'Users';
		}
		public static function getFields(){
			return array(
				"username", "password",
				"fullname", "department_id",
			);
		}
	}
