<?php
/*
 * @filesource index/controllers/pagenotfound.php
 * @link http://www.kotchasan.com/
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 */

namespace Index\PageNotFound;

/**
 * หน้าเพจ 404 (Page Not Found)
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class Controller extends \Kotchasan\Controller
{

  /**
   * แสดงข้อผิดพลาด (เช่น 404 page not found)
   *
   * @param string $message ข้อความที่จะแสดง ถ้าไม่กำหนดจะใช้ข้อความของระบบ
   * @return string
   */
  public static function render($message = '')
  {
    if ($message == '') {
      $message = 'ขออภัย ไม่พบหน้าที่เรียก กรุณาตรวจสอบ URL';
    }
    return '<aside class=error>'.$message.'</aside>';
  }
}