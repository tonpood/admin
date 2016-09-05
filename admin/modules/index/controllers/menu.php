<?php
/*
 * @filesource index/controllers/menu.php
 * @link http://www.kotchasan.com/
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 */

namespace Index\Menu;

/**
 * รายการเมนูทั้งหมด.
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class Controller extends \Kotchasan\Controller
{

  /**
   * สร้างเมนู
   */
  public function getTopMenus($module)
  {
    switch ($module) {
      case 1:
        $className = 'adminMenu';
        break;
      default:
        $className = 'memberMenu';
        break;
    }
    $menus = \Index\Menu\Model::$className();
    return createClass('Index\Menu\View')->render($menus);
  }
}