<?php
/*
 * @filesource index/models/updateprofile.php
 * @link http://www.kotchasan.com/
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 */

namespace Index\Updateprofile;

use \Kotchasan\Http\Request;
use \Kotchasan\Login;
use \Kotchasan\File;

/**
 * บันทึกข้อมูลสมาชิก
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class Model extends \Kotchasan\Model
{

  /**
   * บันทึก ข้อมูลสมาชิก
   */
  public function save(Request $request)
  {
    $ret = array();
    // session, token, member
    if ($request->initSession() && $request->isSafe() && $login = Login::isMember()) {
      // รับค่าจากการ POST
      $save = array(
        'username' => $request->post('register_username')->username(),
        'name' => $request->post('register_name')->topic(),
        'status' => $request->post('register_status')->toInt(),
      );
      // ชื่อตาราง user
      $user_table = $this->getFullTableName('user');
      // database connection
      $db = $this->db();
      // ตรวจสอบค่าที่ส่งมา
      $id = $request->post('register_id')->toInt();
      if ($id == 0) {
        // ใหม่
        $user = (object)array(
            'id' => 0,
            'username' => ''
        );
      } else {
        // แก้ไข
        $user = $db->first($user_table, $id);
      }
      if (!$user) {
        // ไม่พบสมาชิกที่แก้ไข
        $ret['alert'] = Language::get('not a registered user');
      } else {
        $isAdmin = Login::isAdmin();
        // ไม่ใช่แอดมิน ใช้รหัสสมาชิกเดิมจากฐานข้อมูล
        if (!$isAdmin && $user->id > 0) {
          $save['username'] = $user->username;
        }
        // ตรวจสอบค่าที่ส่งมา
        $input = false;
        $requirePassword = false;
        // username
        if (empty($save['username'])) {
          $ret['ret_register_username'] = 'this';
          $input = !$input ? 'register_username' : $input;
        } else {
          // ตรวจสอบ username ซ้ำ
          $search = $db->first($user_table, array('username', $save['username']));
          if ($search !== false && $user->id != $search->id) {
            $ret['ret_register_username'] = 'มีชื่อนี้ลงทะเบียนอยู่ก่อนแล้ว';
            $input = !$input ? 'register_username' : $input;
          } else {
            $requirePassword = $user->username !== $save['username'];
            $ret['ret_register_username'] = '';
          }
        }
        // password
        $password = $request->post('register_password')->topic();
        $repassword = $request->post('register_repassword')->topic();
        if (!empty($password) || !empty($repassword)) {
          if (mb_strlen($password) < 4) {
            // รหัสผ่านต้องไม่น้อยกว่า 4 ตัวอักษร
            $ret['ret_register_password'] = 'this';
            $input = !$input ? 'register_password' : $input;
          } elseif ($repassword != $password) {
            // ถ้าต้องการเปลี่ยนรหัสผ่าน กรุณากรอกรหัสผ่านสองช่องให้ตรงกัน
            $ret['ret_register_repassword'] = 'this';
            $input = !$input ? 'register_repassword' : $input;
          } else {
            $ret['ret_register_password'] = '';
            $ret['ret_register_repassword'] = '';
            $save['password'] = md5($password.$save['username']);
            $requirePassword = false;
          }
        }
        // มีการเปลี่ยน username ต้องการรหัสผ่าน
        if (!$input && $requirePassword) {
          $ret['ret_register_password'] = 'this';
          $input = !$input ? 'register_password' : $input;
        }
        // อัปโหลดไฟล์
        if (!$input) {
          foreach ($request->getUploadedFiles() as $item => $file) {
            if ($file->hasUploadFile()) {
              if (!File::makeDirectory(ROOT_PATH.self::$cfg->usericon_folder)) {
                // ไดเรคทอรี่ไม่สามารถสร้างได้
                $ret['ret_'.$item] = sprintf(Language::get('Directory %s cannot be created or is read-only.'), self::$cfg->usericon_folder);
                $input = !$input ? $item : $input;
              } else {
                try {
                  // อัปโหลด thumbnail
                  $file->cropImage(self::$cfg->user_icon_typies, ROOT_PATH.self::$cfg->usericon_folder.$user->id.'.jpg', self::$cfg->user_icon_w, self::$cfg->user_icon_h);
                } catch (\Exception $exc) {
                  // ไม่สามารถอัปโหลดได้
                  $ret['ret_'.$item] = Language::get($exc->getMessage());
                  $input = !$input ? $item : $input;
                }
              }
            }
          }
        }
        if (!$input) {
          // ไม่ใช่แอดมิน
          if (!$isAdmin) {
            unset($save['status']);
            unset($save['username']);
          }
          // บันทึก
          if ($id == 0) {
            // ใหม่
            $id = $db->insert($user_table, $save);
            // ไปหน้ารายการสมาชิก
            $ret['location'] = $request->getUri()->postBack('index.php', array('module' => 'member', 'id' => null, 'page' => null));
          } else {
            // แก้ไข
            $db->update($user_table, $id, $save);
            if ($login['id'] == $id) {
              // ตัวเอง
              if (isset($save['password'])) {
                if (isset($save['username'])) {
                  $_SESSION['login']['username'] = $save['username'];
                }
                $_SESSION['login']['password'] = $password;
              }
              // reload หน้าเว็บ
              $ret['location'] = 'reload';
            } else {
              // กลับไปหน้าก่อนหน้า
              $ret['location'] = $request->getUri()->postBack('index.php', array('id' => null));
            }
          }
          // คืนค่า
          $ret['alert'] = 'บันทึกเรียบร้อย';
          // clear
          $request->removeToken();
        } else {
          // error
          $ret['input'] = $input;
        }
      }
    } else {
      $ret['alert'] = 'ไม่สามารถดำเนินการได้ กรุณารีเฟรช';
    }
    // คืนค่าเป็น JSON
    if (!empty($ret)) {
      echo json_encode($ret);
    }
  }
}