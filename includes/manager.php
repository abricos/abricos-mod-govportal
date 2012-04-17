<?php
/**
 * @version $Id$
 * @package Abricos
 * @subpackage Govportal
 * @copyright Copyright (C) 2012 Abricos. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @author Alexander Kuzmin <roosit@abricos.org>
 */

require_once 'dbquery.php';

class GovportalManager extends Ab_ModuleManager {
	
	/**
	 * @var GovportalModule
	 */
	public $module = null;
	
	/**
	 * @var GovportalManager
	 */
	public static $instance = null; 
	
	public function __construct(GovportalModule $module){
		parent::__construct($module);
		
		GovportalManager::$instance = $this;
	}
	
	public function IsAdminRole(){
		return $this->IsRoleEnable(GovportalAction::ADMIN);
	}
	
	public function IsWriteRole(){
		if ($this->IsAdminRole()){ return true; }
		return $this->IsRoleEnable(GovportalAction::WRITE);
	}
	
	public function IsViewRole(){
		if ($this->IsWriteRole()){ return true; }
		return $this->IsRoleEnable(GovportalAction::VIEW);
	}
	
	public function AJAX($d){
		switch($d->do){
			case 'init': return $this->BoardData();
		}
		return null;
	}
	
	public function ToArray($rows, &$ids1 = "", $fnids1 = 'uid', &$ids2 = "", $fnids2 = '', &$ids3 = "", $fnids3 = ''){
		$ret = array();
		while (($r = $this->db->fetch_array($rows))){
			array_push($ret, $r);
			if (is_array($ids1)){ $ids1[$r[$fnids1]] = $r[$fnids1]; }
			if (is_array($ids2)){ $ids2[$r[$fnids2]] = $r[$fnids2]; }
			if (is_array($ids3)){ $ids3[$r[$fnids3]] = $r[$fnids3]; }
		}
		return $ret;
	}
	
	public function ToArrayId($rows, $field = "id"){
		$ret = array();
		while (($row = $this->db->fetch_array($rows))){
			$ret[$row[$field]] = $row;
		}
		return $ret;
	}
	
	public function BoardData(){
		if (!$this->IsViewRole()){ return null; }
		$ret = new stdClass();
		return $ret;
	}
	

	
}

?>