<?php
/*
 * @filesource index/models/checker.php
 * @link http://www.kotchasan.com/
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 */

namespace Index\Checker;

/**
 * ตรวจสอบข้อมูลสมาชิกด้วย Ajax
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class Model extends \Kotchasan\Model
{

  /**
   * ฟังก์ชั่นตรวจสอบความถูกต้องของ username และตรวจสอบ username ซ้ำ
   */
  public function username()
  {
    // referer
    if (self::$request->isReferer()) {
      $id = self::$request->post('id')->toInt();
      $value = self::$request->post('value')->toString();
      if (!preg_match('/^[a-zA-Z0-9]+$/', $value)) {
        echo 'ภาษาอังกฤษและตัวเลขเท่านั้น';
      } else {
        // ตรวจสอบอีเมล์ซ้ำ
        $search = $this->db()->first($this->getFullTableName('user'), array('username', $value));
        if ($search && ($id == 0 || $id != $search->id)) {
          echo 'มีชื่อสมาชิกนี้ลงทะเบียนอยู่ก่อนแล้ว';
        }
      }
    }
  }
}