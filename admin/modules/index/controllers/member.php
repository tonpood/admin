<?php
/*
 * @filesource index/controllers/member.php
 * @link http://www.kotchasan.com/
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 */

namespace Index\Member;

use \Kotchasan\Http\Request;
use \Kotchasan\Login;
use \Kotchasan\Html;

/**
 * รายชื่อสมาชิก
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class Controller extends \Kotchasan\Controller
{

  /**
   * ตารางสมาชิก
   *
   * @param Request $request
   * @return string
   */
  public function render(Request $request)
  {
    // แอดมิน
    if (Login::isAdmin()) {
      // แสดงผล
      $section = Html::create('section');
      // breadcrumbs
      $breadcrumbs = $section->add('div', array(
        'class' => 'breadcrumbs'
      ));
      $ul = $breadcrumbs->add('ul');
      $ul->appendChild('<li><span class="icon-user">สมาชิก</span></li>');
      $ul->appendChild('<li><span>'.$this->title().'</span></li>');
      $section->add('header', array(
        'innerHTML' => '<h1 class="icon-list">'.$this->title().'</h1>'
      ));
      // แสดงตาราง
      $section->appendChild(createClass('Index\Member\View')->render($request));
      return $section->render();
    }
    // 404
    return \Index\PageNotFound\Controller::render();
  }

  /**
   * title bar
   */
  public function title()
  {
    return 'รายชื่อสมาชิก';
  }
}