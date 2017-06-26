<?php
/*
 * @filesource index/controllers/index.php
 * @link http://www.kotchasan.com/
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 */

namespace Index\Index;

use \Kotchasan\Http\Request;
use \Gcms\Login;
use \Kotchasan\Template;
use \Kotchasan\Http\Response;

/**
 * Controller หลัก สำหรับแสดง backend
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class Controller extends \Kotchasan\Controller
{

  /**
   * แสดงผลหน้าหลักเว็บไซต์
   *
   * @param Request $request
   */
  public function index(Request $request)
  {
    // ตัวแปรป้องกันการเรียกหน้าเพจโดยตรง
    define('MAIN_INIT', __FILE__);
    // เริ่มต้นใช้งาน session
    $request->initSession();
    // ตรวจสอบการ login
    Login::create();
    // กำหนด skin ให้กับ template
    Template::init('admin');
    // backend
    $view = new \Kotchasan\View;
    if ($login = Login::isMember()) {
      // Controller หลัก
      $main = new \Index\Main\Controller;
      $bodyclass = 'mainpage';
    } else {
      // forgot or login
      if ($request->request('action')->toString() === 'forgot') {
        $main = new \Index\Forgot\Controller;
      } else {
        $main = new \Index\Login\Controller;
      }
      $bodyclass = 'loginpage';
    }
    // เนื้อหา
    $view->setContents(array(
      // main template
      '/{MAIN}/' => $main->execute($request),
      // title
      '/{TITLE}/' => $main->title(),
      // class สำหรับ body
      '/{BODYCLASS}/' => $bodyclass
    ));
    if ($login) {
      // โหลดเมนู
      $menu = new \Index\Menu\Controller;
      $view->setContents(array(
        // ID สมาชิก
        '/{LOGINID}/' => $login['id'],
        // แสดงชื่อคน Login
        '/{LOGINNAME}/' => empty($login['name']) ? $login['username'] : $login['name'],
        // ไอคอนสมาชิก
        '/{USERICON}/' => WEB_URL.(is_file(ROOT_PATH.self::$cfg->usericon_folder.$login['id'].'.jpg') ? self::$cfg->usericon_folder.$login['id'].'.jpg' : 'skin/img/noicon.jpg'),
        // สถานะสมาชิก
        '/{STATUS}/' => $login['status'],
        // เมนู
        '/{MENUS}/' => $menu->getTopMenus($login['status'])
      ));
    }
    // ส่งออก เป็น HTML
    $response = new Response;
    $response->withContent($view->renderHTML())->send();
  }
}
