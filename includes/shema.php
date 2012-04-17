<?php
/**
 * Схема таблиц данного модуля
 * 
 * @version $Id$
 * @package Abricos
 * @subpackage Govportal
 * @copyright Copyright (C) 2012 Abricos. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @author Alexander Kuzmin <roosit@abricos.org>
 */

$charset = "CHARACTER SET 'utf8' COLLATE 'utf8_general_ci'";
$updateManager = Ab_UpdateManager::$current; 
$db = Abricos::$db;
$pfx = $db->prefix;

if ($updateManager->isInstall()){
	Abricos::GetModule('govportal')->permission->Install();
}

if (Ab_UpdateManager::$isCoreInstall){ 
	// идет инсталляция платформы, значит можно установить шаблон для госучреждения
	
	// Установить шаблон gov
	Abricos::GetModule('sys')->GetManager();
	$sysMan = Ab_CoreSystemManager::$instance;
	$sysMan->DisableRoles();
	$sysMan->SetTemplate('gov');
	$sysMan->SetSiteName('Государственное учреждение');
	$sysMan->SetSiteTitle('/официальный сайт/');
	
	
	// Страницы сайта
	Abricos::GetModule('sitemap')->GetManager();
	$manSitemap = SitemapManager::$instance;
	$manSitemap->DisableRoles();
	$manSitemap->MenuRemove(2);
	
	
	// меняем текст на главной
	$d = $manSitemap->Page(1, true);
	
	$ord = 10;
	
	// Структура предприятия
	$modCompany = Abricos::GetModule('company');
	if (!empty($modCompany)){
		
		$m = new stdClass();
		$m->nm = 'struct';
		$m->tl = 'Структура предприятия';
		$m->ord = $ord++;
		$m->id = $manSitemap->MenuAppend($m);
	
		$p = new stdClass();
		$p->mid = $m->id;
		$p->nm = 'index';
		$p->mods = '{"company":{"viewdict":""}}';
		$p->bd = "
			<h2>Структура предприятия, телефоны</h2>
			[mod]company:viewdict[/mod]
		";
		$manSitemap->PageAppend($p);

		$modCompany->GetManager();
		$manCompany = CompanyManager::$instance;
		$manCompany->DisableRoles();
		
		// наполнение справочников
		$d = new stdClass();
		$d->nm = "Руководство";
		$d->ord = 1;
		$manCompany->DeptAppend($d);

		$d = new stdClass();
		$d->nm = "Отдел кадров";
		$d->ord = 3;
		$manCompany->DeptAppend($d);
		
		$d = new stdClass();
		$d->nm = "Отдел информатизации";
		$d->ord = 3;
		$deptId = $manCompany->DeptAppend($d);
		
		
		$emp = new stdClass();
		$emp->unm = 'admin';
		$emp->efnm = 'Иванов';
		$emp->elnm = 'Иван';
		$emp->epnc = 'Иванович';
		$emp->deptid = $deptId;
		$emp->rm = '109';
		$emp->phs = '68-55-09|2109';
		$manCompany->EmployeeAppend($emp);
	}
	
	
	// Модуль "Обратная связь" используется в качестве Интернет-приемной
	$modFeedback = Abricos::GetModule('feedback');
	if (!empty($modFeedback)){
		$m = new stdClass();
		$m->nm = 'feedback';
		$m->tl = 'Интернет-приемная';
		$m->ord = $ord++;
		$m->id = $manSitemap->MenuAppend($m);
		
		$p = new stdClass();
		$p->mid = $m->id;
		$p->nm = 'index';
		$p->bd = '';
		$manSitemap->PageAppend($p);
	}
	
	// Модуль "Блог" - ответы на часто задаваемые вопросы публикуются сюда
	$modBlog = Abricos::GetModule('blog');
	if (!empty($modBlog)){
		$m = new stdClass();
		$m->nm = 'blog';
		$m->tl = 'Блог';
		$m->ord = $ord++;
		$m->id = $manSitemap->MenuAppend($m);
	
		$p = new stdClass();
		$p->mid = $m->id;
		$p->nm = 'index';
		$p->bd = '';
		$manSitemap->PageAppend($p);
	}
	
	
}




?>