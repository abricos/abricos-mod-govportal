<?php 
/**
 * @version $Id$
 * @package Abricos
 * @subpackage Govportal
 * @copyright Copyright (C) 2012 Abricos. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @author Alexander Kuzmin <roosit@abricos.org>
 */


/**
 * Модуль-сборка "Сайт госучреждения"
 */
class GovportalModule extends Ab_Module {
	
	/**
	 * Конструктор
	 */
	public function __construct(){
		$this->version = "0.1";
		$this->name = "govportal";
		$this->takelink = "govportal";
		$this->permission = new GovportalPermission($this);
	}
	
	/**
	 * @return GovportalManager
	 */
	public function GetManager(){
		if (is_null($this->_manager)){
			require_once 'includes/manager.php';
			$this->_manager = new GovportalManager($this);
		}
		return $this->_manager;
	}

	public function GetContentName(){
		return 'index';
	}
}

class GovportalAction {
	const VIEW	= 10;
	const WRITE	= 30;
	const ADMIN	= 50;
}

class GovportalPermission extends Ab_UserPermission {

	public function GovportalPermission(GovportalModule $module){
		// объявление ролей по умолчанию
		// используется при инсталяции модуля в платформе
		$defRoles = array(
			new Ab_UserRole(GovportalAction::VIEW, Ab_UserGroup::ADMIN),
			new Ab_UserRole(GovportalAction::WRITE, Ab_UserGroup::ADMIN),
			new Ab_UserRole(GovportalAction::ADMIN, Ab_UserGroup::ADMIN)
		);
		parent::__construct($module, $defRoles);
	}

	public function GetRoles(){
		return array(
			GovportalAction::VIEW => $this->CheckAction(GovportalAction::VIEW),
			GovportalAction::WRITE => $this->CheckAction(GovportalAction::WRITE),
			GovportalAction::ADMIN => $this->CheckAction(GovportalAction::ADMIN)
		);
	}
}

// создать экземляр класса модуля и зарегистрировать его в ядре 
Abricos::ModuleRegister(new GovportalModule())

?>