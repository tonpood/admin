<?php
/*
 * @filesource index/controllers/dashboard.php
 * @link http://www.kotchasan.com/
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 */

namespace Index\Dashboard;

use \Kotchasan\Http\Request;
use \Kotchasan\Login;

/**
 * Dashboard
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class Controller extends \Kotchasan\Controller
{

  /**
   * แสดงผล
   *
   * @param Request $request
   */
  public function render(Request $request)
  {
    // สมาชิก
    if ($login = Login::isMember()) {
      // dashboard
      return createClass('Index\Dashboard\View')->render($request);
    }
    // not login display 404
    return \Index\PageNotFound\Controller::render();
  }

  /**
   * title bar
   */
  public function title()
  {
    return 'Welcome to Kotchasan Web Framework';
  }
}