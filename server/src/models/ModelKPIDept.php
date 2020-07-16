<?php
	require_once '../src/models/BaseModel.php';
	require_once '../src/models/ModelDepartment.php';

	class ModelKPIDept extends BaseModel{
		public static function getFields(){
			return array(
				"dept_id", "deptcode", "deptname", "yearperiod", "transdate"
			);
		}

		public static function generate($dept_id, $yearperiod){
			$obj = new stdClass();
			$obj->id = 0;
			$obj->yearperiod = $yearperiod;

			$dept = ModelDepartment::retrieve($dept_id);

			if (isset($dept)) {
				$obj->dept_id = $dept->id;
				$obj->deptcode = $dept->deptcode;
				$obj->deptname = $dept->deptname;
				$obj->mlitems = array();
				$obj->kpiitems = array();

				foreach($dept->mlitems as $area){
					foreach($area->items as $subarea){
						ModelKPIDept::setObjItem($obj->mlitems, $area, $subarea);
					}
				}

				foreach($dept->kpiitems as $area){
					foreach($area->items as $subarea){
						ModelKPIDept::setObjItem($obj->kpiitems, $area, $subarea);
					}
				}
			}
			return $obj;
		}

		public static function retrieve($id){
			$obj = parent::retrieve($id);
			if (isset($obj)) {
				$obj->items = ModelKPIDeptDetail::retrieveList('KPIDept_ID = '. $obj->id);
			}
			return $obj;
		}

		private static function setObjItem(&$items, $area, $subarea){
			for ($i = 1; $i <= 5; $i++) {
				if ($i == 2 && $subarea->level_2 == '') continue ;
				if ($i == 3 && $subarea->level_3 == '') continue;
				if ($i == 4 && $subarea->level_4 == '') continue;
				if ($i == 5 && $subarea->level_5 == '') continue;

				$newitem = new stdClass();

				$newitem->areacode = $area->areacode;
				$newitem->areaname = $area->areaname;
				$newitem->subcode = $subarea->subcode;
				$newitem->subname = $subarea->subname;

				// $newitem->subdesc = $subarea->subname.  "\n\n" . $subarea->subdesc;
				if ($i == 1) {
					$newitem->subdesc = $subarea->subname;
				}else{
					$newitem->subdesc = $subarea->subdesc;
				}
				
				$newitem->weight = $subarea->weight;

				if ($i == 1){
					$newitem->level = $subarea->level_1;
					$newitem->leveldetail = $subarea->leveldetail_1;
				}
				else if ($i == 2){
					$newitem->level = $subarea->level_2;
					$newitem->leveldetail = $subarea->leveldetail_2;
				}
				else if ($i == 3) {
					$newitem->level = $subarea->level_3;
					$newitem->leveldetail = $subarea->leveldetail_3;
				}
				else if ($i == 4){
					$newitem->level = $subarea->level_4;
					$newitem->leveldetail = $subarea->leveldetail_4;
				}
				else if ($i == 5){
					$newitem->level = $subarea->level_5;
					$newitem->leveldetail = $subarea->leveldetail_5;
				}

				$newitem->key = $newitem->subcode . '_' . $i;
				array_push($items, $newitem);
			}
		}

		public static function deleteFromDB($id){
			try{
				$obj = parent::retrieve($id);
				$str = ModelKPIDeptDetail::generateSQLDelete("KPIDept_ID=". $id);
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
					$str = ModelKPIDeptDetail::generateSQLDelete("KPIDept_ID=". $id);
					$db->prepare($str)->execute();
				}
				static::saveObjToDB($obj, $db);
				foreach($obj->items as $item){
					$item->KPIDept_ID = $obj->id;
					ModelKPIDeptDetail::saveObjToDB($item, $db);
				}
				$db->commit();
				$db = null;
			} catch (Exception $e) {
				$db->rollback();
				throw $e;
			}
		}
	}


	class ModelKPIDeptDetail extends BaseModel{
		public static function getTableName(){
			return 'KPIDeptDetail';
		}
		public static function getFields(){
			return array(
				"KPIDept_ID",
				"groupCode", // ML/KPI
				"areacode",
				"areaname",
				"subcode",
				"subname", "subdesc", "weight",
				"level_1", "leveldetail_1",
				"level_2", "leveldetail_2",
				"level_3", "leveldetail_3",
				"level_4", "leveldetail_4",
				"level_5", "leveldetail_5",
			);
		}
	}
