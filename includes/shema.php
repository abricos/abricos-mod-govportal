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
	
	Abricos::$user->id = 1;
	
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
	
	// Посетителям
	$m = new stdClass();
	$m->nm = 'visitors';
	$m->tl = 'Посетителям';
	$m->ord = $ord++;
	$m->id = $manSitemap->MenuAppend($m);
	
	$p = new stdClass();
	$p->mid = $m->id;
	$p->nm = 'index';
	$p->bd = "
		<h2>Посетителям</h2>
		<hr style='width: 100%;'>
		
		<p><strong>Время работы:</strong></p>
		
		<table border='0' style='width: 300px;'><tbody>
			<tr>
				<td>Понедельник - пятница</td>
				<td>с <strong>9.00</strong> до <strong>18.00</strong></td>
			</tr>
			<tr>
				<td>Перерыв</td>
				<td>с <strong>12.00</strong> до <strong>13.00</strong></td>
			</tr>
		</tbody></table>
		
		<hr style='width: 100%;'>
		
		<p><strong>Местонахождение:</strong></p>
	
		<p>101000, г.Москва, Красная площадь, дом 1</p>
		
		<p>
			Тел.: 101-00-01<br> 
			Факс. 101-00-02
		</p>
	";
	$manSitemap->PageAppend($p);
	
	$pmid = $m->id;
	
	// Посетителям / Прием населения
	$m = new stdClass();
	$m->nm = 'priem_naseleniya';
	$m->pid = $pmid;
	$m->tl = 'Прием населения';
	$m->ord = $ord++;
	$m->id = $manSitemap->MenuAppend($m);
	
	$p = new stdClass();
	$p->mid = $m->id;
	$p->nm = 'index';
	$p->bd = "
		<h3>Прием населения</h3>
		
		<table class='infotable' style='width: 500px;'><tbody>
		<tr>
			<td>Начальник</td>
			<td>вторник - четверг</td>
			<td>с <strong>13.00</strong> до <strong>16.00</strong></td>
		</tr>
		<tr>
			<td>Заместитель начальника</td>
			<td>вторник - четверг</td>
			<td>с <strong>10.00</strong> до <strong>12.00</strong></td>
		</tr>
		</tbody></table>
		
		<p>
			Запись на прием – каждый вторник,  четверг с 09.00 до 10.00
		</p>
		
	";
	$manSitemap->PageAppend($p);
	
	
	// Новости
	$m = new stdClass();
	$m->nm = 'news';
	$m->tl = 'Новости';
	$m->ord = $ord++;
	$m->id = $manSitemap->MenuAppend($m);
	
	$p = new stdClass();
	$p->mid = $m->id;
	$p->nm = 'index';
	$p->bd = "<h2>Новости</h2>";
	$manSitemap->PageAppend($p);
	
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
		
		$modBlog->GetManager();
		$blogMan = BlogManager::$instance;
		$blogMan->DisableRoles();
		
		// Категория блога
		$c = new stdClass();
		$c->nm = 'vopros-otvet';
		$c->ph = 'Вопрос-ответ';
		$catid = $blogMan->CategoryAppend($c);
		
		// Запись в блоге
		$t = new stdClass();
		$t->catid = $catid;
		$t->st = 1;
		$t->tl = "Ответ по обычной почте";
		$t->intro = "
<p>
	<strong>Вопрос:</strong> Будет ли отправлен ответ на вопрос через интернет-приемную по обычной почте?
</p>
		";

		$t->body = "
<p>
	<strong>Ответ:</strong> 
	Согласно <a href='http://cms/blog/vopros-otvet/' title='Федеральному закону Российской Федерации от 2 мая 2006 г. N 59-ФЗ \"О порядке рассмотрения обращений граждан Российской Федерации\"'>
	Федеральному закону Российской Федерации от 2 мая 2006 г. N 59-ФЗ \"О порядке рассмотрения обращений граждан Российской Федерации\"</a> статья 10 п.4: <br />
	Ответ на обращение, поступившее в государственный орган, орган местного самоуправления или должностному 
	лицу по информационным системам общего пользования, направляется по почтовому адресу, 
	указанному в обращении.
</p>
		";
		$t->tags = "Интернет-приемная, Обращение граждан";
		$blogMan->TopicSave($t);
	}
	
	// Карта сайта
	$m = new stdClass();
	$m->nm = 'sitemap';
	$m->tl = 'Карта сайта';
	$m->ord = $ord++;
	$m->id = $manSitemap->MenuAppend($m);
	
	$p = new stdClass();
	$p->mid = $m->id;
	$p->nm = 'index';
	$p->bd = "<h2>Карта сайта</h2>";
	$manSitemap->PageAppend($p);
	
	Abricos::$user->id = 0;
}




?>