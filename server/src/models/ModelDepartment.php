<?php
	require_once '../src/models/BaseModel.php';
	require_once '../src/models/ModelMLArea.php';

	class ModelDepartment extends BaseModel{
		public static function getFields(){
			return array(
				"deptcode", "deptname"
			);
		}

		//override
		public static function retrieve($id){
			$obj = parent::retrieve($id);
			if (isset($obj)) {				
				$obj->mlitems = ModelMLArea::retrieveList('department_id = '. $obj->id);
				foreach($obj->mlitems as $mlarea){
					$mlarea->items = ModelMLSubArea::retrieveList('ml_area_id = '. $mlarea->id);
				}
			}
			return $obj;
		}


		public static function deleteFromDB($id){
			try{
				$obj = parent::retrieve($id);
				$str = static::generateSQLDelete("id=". $id);
				$str = $str . "delete a, b from ml_area a
							inner join ml_subarea b on a.id = b.ml_area_id where a.department_id = " . $id . "; ";
				// $str = $str . "delete a, b from kpi_area a
				// 			inner join kpi_subarea b on a.id = b.kpi_area_id where a.department_id = " . $id . "; ";
				DB::executeSQL($str);
			} catch (Exception $e) {
				$db->rollback();
				throw $e;
			}
		}

		public static function saveToDB($obj){
			$db = new DB();
			$db = $db->connect();
			$db->beginTransaction();
			try {
				if (!static::isNewTransaction($obj)){
					$str = "delete a, b from ml_area a
								left join ml_subarea b on a.id = b.ml_area_id where a.department_id = " . $obj->id . "; ";
					// $str = $str . "delete a, b from kpi_area a
					// 			inner join kpi_subarea b on a.id = b.kpi_area_id where a.department_id = " . $id . "; ";
					$db->prepare($str)->execute();
				}

				static::saveObjToDB($obj, $db);


				foreach($obj->mlitems as $mlarea){
					$mlarea->department_id = $obj->id;
					$mlarea->id = 0; //force insert
					ModelMLArea::saveObjToDB($mlarea, $db);
					foreach($mlarea->items as $mlsubarea){
						$mlsubarea->id = 0; //force insert
						$mlsubarea->ml_area_id = $mlarea->id;
					  ModelMLSubArea::saveObjToDB($mlsubarea, $db);
					}
				}

				$db->commit();
				$db = null;

			} catch (Exception $e) {
				$db->rollback();
				throw $e;
			}
		}

	}
