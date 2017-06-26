<?php
/*
 * @filesource Gcms/Forgot.php
 * @link http://www.kotchasan.com/
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 */

namespace Gcms;

use \Kotchasan\Http\Request;
use \Kotchasan\Text;

/**
 * คลาสสำหรับขอรหัสผ่านใหม่
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class Forgot extends \Kotchasan\KBase
{

  /**
   * ฟังก์ชั่นส่งอีเมล์ลืมรหัสผ่าน
   */
  public function execute(Request $request)
  {
    // ค่าที่ส่งมา
    $username = $request->post('login_username')->url();
    if (empty($username)) {
      if ($request->post('action')->toString() === 'forgot') {
        self::$login_message = Language::get('Please fill in');
      }
    } else {
      self::$text_username = $username;
      // ชื่อฟิลด์สำหรับตรวจสอบอีเมล์ ใช้ฟิลด์แรกจาก config
      $field = reset(self::$cfg->login_fields);
      // ค้นหาอีเมล์
      $model = new Model;
      $search = $model->db()->first($model->getTableName('user'), array(array($field, $username)));
      if ($search === false) {
        self::$login_message = Language::get('not a registered user');
      } else {
        // สุ่มรหัสผ่านใหม่
        $password = Text::rndname(6);
        // ข้อมูลอีเมล์
        $replace = array(
          '/%PASSWORD%/' => $password,
          '/%EMAIL%/' => $search->$field
        );
        // send mail
        $err = Email::send(3, 'member', $replace, $search->$field);
        if (!$err->error()) {
          // อัปเดทรหัสผ่านใหม่
          $model->db()->update($table, (int)$search->id, array('password' => sha1($password.$search->$field)));
          // คืนค่า
          self::$login_message = Language::get('Your message was sent successfully');
          self::$request = $request->withQueryParams(array('action' => 'login'));
        } else {
          self::$login_message = $err->getErrorMessage();
        }
      }
    }
  }
}