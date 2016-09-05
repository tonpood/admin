<?php
/*
 * @filesource index/models/member.php
 * @link http://www.kotchasan.com/
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 */

namespace Index\Member;

use \Kotchasan\Http\Request;
use \Kotchasan\Login;

/**
 * action ตารางสมาชิก
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class Model extends \Kotchasan\Orm\Field
{
  /**
   * ชื่อตาราง
   *
   * @var string
   */
  protected $table = 'user U';

  /**
   * รับค่าจาก action
   */
  public function action(Request $request)
  {
    if ($request->initSession() && $request->isReferer() && $login = Login::isAdmin()) {
      // รับค่าจากการ POST
      $action = $request->post('action')->toString();
      // Model
      $model = new \Kotchasan\Model;
      // ตาราง user
      $user_table = $model->getFullTableName('user');
      // id ที่ส่งมา
      if (preg_match_all('/,?([0-9]+),?/', $request->post('id')->toString(), $match)) {
        if ($action === 'delete') {
          // ลบสมาชิก
          $model->db()->delete($user_table, array(
            array('id', $match[1]),
            array('id', '!=', 1)
            ), 0);
        } elseif (preg_match('/^status_([0-9]+)$/', $action, $status)) {
          // เปลี่ยนสถานะสมาชิก
          $model->db()->update($user_table, array(
            array('id', $match[1]),
            array('id', '!=', 1)
            ), array(
            'status' => (int)$status[1]
          ));
        }
      }
    }
  }
}