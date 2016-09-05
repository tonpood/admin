<?php
/*
 * @filesource index/controllers/editprofile.php
 * @link http://www.kotchasan.com/
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 */

namespace Index\Editprofile;

use \Kotchasan\Login;
use \Kotchasan\Html;
use \Kotchasan\Orm\Recordset;

/**
 * แก้ไขข้อมูลส่วนตัวสมาชิก
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class Controller extends \Kotchasan\Controller
{

  /**
   * แสดงผล
   */
  public function render()
  {
    // สมาชิก
    if ($login = Login::isMember()) {
      // อ่านข้อมูลสมาชิกที่ต้องการ ถ้าไม่ระบุ id มาใช้คนที่ login
      $rs = Recordset::create('Index\Member\Model');
      $user = $rs->find(self::$request->get('id', $login['id'])->toInt());
      if ($user && ($login['status'] == 1 || $login['id'] == $user->id)) {
        // แสดงผล
        $section = Html::create('section');
        // breadcrumbs
        $breadcrumbs = $section->add('div', array(
          'class' => 'breadcrumbs'
        ));
        $ul = $breadcrumbs->add('ul');
        $ul->appendChild('<li><a class="icon-user" href="index.php?module=member">สมาชิก</a></li>');
        $ul->appendChild('<li><span>ข้อมูลสมาชิก</span></li>');
        $section->add('header', array(
          'innerHTML' => '<h1 class="icon-profile">'.$this->title().'</h1>'
        ));
        // แสดงฟอร์ม
        $section->appendChild(createClass('Index\Editprofile\View')->render($user));
        return $section->render();
      }
    }
    // 404
    return \Index\PageNotFound\Controller::render();
  }

  /**
   * title bar
   */
  public function title()
  {
    return 'แก้ไขข้อมูลส่วนตัวของสมาชิก';
  }
}