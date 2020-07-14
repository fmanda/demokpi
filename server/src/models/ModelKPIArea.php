<?php
	require_once '../src/models/BaseModel.php';

	class ModelKPIArea extends BaseModel{
		public static function getTableName(){
			return 'KPI_Area';
		}

		public static function getFields(){
			return array(
				"department_id", "areacode", "areaname"
			);
		}
	}

	class ModelKPISubArea extends BaseModel{
		public static function getTableName(){
			return 'KPI_SubArea';
		}
		public static function getFields(){
			return array(
				"kpi_area_id", "subcode",
				"subname", "subdesc", "weight",
				"level_1", "leveldetail_1",
				"level_2", "leveldetail_2",
				"level_3", "leveldetail_3",
				"level_4", "leveldetail_4",
				"level_5", "leveldetail_5",
			);
		}
	}
