<?php
/*
 * @filesource index/controllers/main.php
 * @link http://www.kotchasan.com/
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 */

namespace Index\Main;

use \Kotchasan\Http\Request;
use \Kotchasan\Template;

/**
 * Controller หลัก สำหรับแสดง backend ของ GCMS
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class Controller extends \Kotchasan\Controller
{
  /**
   * Controller ที่กำลังทำงาน
   *
   * @var \Kotchasan\Controller
   */
  private $controller;

  /**
   * หน้าหลักแอดมิน
   *
   * @param Request $request
   * @return string
   */
  public function execute(Request $request)
  {
    // โมดูลจาก URL ถ้าไม่มีใช้ default (dashboard)
    $module = $request->get('module', 'dashboard')->toString();
    if (preg_match('/^([a-z]+)([\/\-]([a-z]+))?$/i', $module, $match)) {
      if (empty($match[3])) {
        $owner = 'index';
        $module = $match[1];
      } else {
        $owner = $match[1];
        $module = $match[3];
      }
    } else {
      // หน้า default ถ้าไม่ระบุ module มา
      $owner = 'index';
      $module = 'dashboard';
    }
    // ตรวจสอบหน้าที่เรียก
    if (is_file(APP_PATH.'modules/'.$owner.'/controllers/'.$module.'.php')) {
      // หน้าที่เรียก (Admin)
      $controller = ucfirst($owner).'\\'.ucfirst($module).'\Controller';
    } elseif (is_file(ROOT_PATH.'modules/'.$owner.'/controllers/office/'.$module.'.php')) {
      // เรียกโมดูลที่ติดตั้ง
      $controller = ucfirst($owner).'\Admin\\'.ucfirst($module).'\Controller';
    } else {
      // หน้า default ถ้าไม่พบหน้าเที่เรียก
      $controller = 'Index\Dashboard\Controller';
    }
    $this->controller = new $controller;
    // tempalate
    $template = Template::create('', '', 'main');
    $template->add(array(
      '/{CONTENT}/' => $this->controller->render($request)
    ));
    return $template->render();
  }

  /**
   * title bar
   */
  public function title()
  {
    return $this->controller->title();
  }
}