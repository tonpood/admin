<?php
/*
 * @filesource index/views/member.php
 * @link http://www.kotchasan.com/
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 */

namespace Index\Member;

use \Kotchasan\Http\Request;
use \Kotchasan\DataTable;
use \Kotchasan\Date;
use \Kotchasan\ArrayTool;

/**
 * module=member
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class View extends \Kotchasan\View
{

  /**
   * ตารางรายชื่อสมาชิก
   *
   * @param Request $request
   * @return string
   */
  public function render(Request $request)
  {
    // action
    $actions = array('delete' => 'ลบ');
    foreach (self::$cfg->member_status as $key => $value) {
      $actions['status_'.$key] = 'เปลี่ยนสถานะสมาชิกเป็น '.$value;
    }
    // ตารางสมาชิก
    $table = new DataTable(array(
      'model' => 'Index\Member\Model',
      'perPage' => $request->cookie('member_perPage', 30)->toInt(),
      'sort' => $request->cookie('member_sort', 'id desc')->toString(),
      'onRow' => array($this, 'onRow'),
      /* คอลัมน์ที่สามารถค้นหาได้ */
      'searchColumns' => array('name', 'username'),
      /* คอลัมน์ที่ไม่ต้องแสดงผล */
      'hideColumns' => array('id', 'visited'),
      /* ตั้งค่าการกระทำของของตัวเลือกต่างๆ ด้านล่างตาราง ซึ่งจะใช้ร่วมกับการขีดถูกเลือกแถว */
      'action' => 'index.php/index/model/member/action',
      'actions' => array(
        array(
          'id' => 'action',
          'class' => 'ok',
          'text' => 'ทำกับที่เลือก',
          'options' => $actions
        )
      ),
      /* ตัวเลือกด้านบนของตาราง ใช้จำกัดผลลัพท์การ query */
      'filters' => array(
        'status' => array(
          'name' => 'status',
          'default' => -1,
          'text' => 'สถานะ',
          'options' => ArrayTool::merge(array(-1 => 'ทั้งหมด'), self::$cfg->member_status),
          'value' => $request->get('status', -1)->toInt()
        )
      ),
      /* รายชื่อฟิลด์ที่ query (ถ้าแตกต่างจาก Model) */
      'fields' => array(
        'id',
        'username',
        'name',
        'status',
        'lastvisited',
        'visited'
      ),
      /* ส่วนหัวของตาราง และการเรียงลำดับ (thead) */
      'headers' => array(
        'username' => array(
          'text' => 'Username',
          'sort' => 'username'
        ),
        'name' => array(
          'text' => 'ชื่อ นามสกุล',
          'sort' => 'name'
        ),
        'status' => array(
          'text' => 'สถานะ',
          'class' => 'center'
        ),
        'lastvisited' => array(
          'text' => 'เข้าระบบล่าสุด (ครั้ง)',
          'class' => 'center',
          'sort' => 'lastvisited'
        )
      ),
      /* รูปแบบการแสดงผลของคอลัมน์ (tbody) */
      'cols' => array(
        'status' => array(
          'class' => 'center'
        ),
        'lastvisited' => array(
          'class' => 'center'
        )
      ),
      /* ปุ่มแสดงในแต่ละแถว */
      'buttons' => array(
        array(
          'class' => 'icon-edit button green',
          'href' => $request->getUri()->createBackUri(array('module' => 'editprofile', 'id' => ':id')),
          'text' => 'แก้ไข'
        )
      )
    ));
    // save cookie
    setcookie('member_perPage', $table->perPage, time() + 3600 * 24 * 365, '/');
    setcookie('member_sort', $table->sort, time() + 3600 * 24 * 365, '/');
    return $table->render();
  }

  /**
   * จัดรูปแบบการแสดงผลในแต่ละแถว
   *
   * @param array $item
   * @return array
   */
  public function onRow($item, $o, $prop)
  {
    $item['status'] = isset(self::$cfg->member_status[$item['status']]) ? '<span class=status'.$item['status'].'>'.self::$cfg->member_status[$item['status']].'</span>' : 'Unknow';
    $item['lastvisited'] = Date::format($item['lastvisited'], 'd M Y H:i').' ('.number_format($item['visited']).')';
    return $item;
  }
}