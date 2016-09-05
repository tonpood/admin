<?php
/*
 * @filesource index/views/menu.php
 * @link http://www.kotchasan.com/
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 */

namespace Index\Menu;

/**
 * module=menu
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class View extends \Kotchasan\View
{

  /**
   * module=menu
   *
   * @param array $items
   * @return string
   */
  public function render($items)
  {
    $menus = array();
    foreach ($items as $alias => $values) {
      if (isset($values['submenus'])) {
        $menus[] = $this->getItem($alias, $values, true).'<ul>';
        $menus[] = $this->render($values['submenus']);
        $menus[] = '</ul>';
      } else {
        $menus[] = $this->getItem($alias, $values, false);
      }
    }
    return implode('', $menus);
  }

  /**
   * ฟังก์ชั่น แปลงเป็นรายการเมนู
   *
   * @param $name string|int ชื่อเมนู
   * @param array $item แอเรย์ข้อมูลเมนู
   * @param bool $arrow (optional) true=แสดงลูกศรสำหรับเมนูที่มีเมนูย่อย (default false)
   * @return string คืนค่า HTML ของเมนู
   */
  public function getItem($name, $item, $arrow = false)
  {
    $c = empty($name) && !is_int($name) ? '' : ' class="'.$name.'"';
    if (!empty($item['url'])) {
      $a = array('href="'.$item['url'].'"');
      if (isset($item['target'])) {
        $a[] = 'target="'.$item['target'].'"';
      }
    }
    if (!empty($item['text'])) {
      $a[] = 'title="'.$item['text'].'"';
    }
    $a = isset($a) ? ' '.implode(' ', $a) : '';
    if ($arrow) {
      return '<li'.$c.'><a class=menu-arrow'.$a.'><span>'.(empty($item['text']) ? '&nbsp;' : htmlspecialchars_decode($item['text'])).'</span></a>';
    } else {
      return '<li'.$c.'><a'.$a.'><span>'.(empty($item['text']) ? '&nbsp;' : htmlspecialchars_decode($item['text'])).'</span></a>';
    }
  }
}