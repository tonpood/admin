<?php
/**
 * @filesource Gcms/Config.php
 * @link http://www.kotchasan.com/
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 */

namespace Gcms;

/**
 * Config Class สำหรับ GCMS
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class Config extends \Kotchasan\Config
{
  /**
   * รายชื่อฟิลด์จากตารางสมาชิก สำหรับตรวจสอบการ login
   *
   * @var array
   */
  public $login_fields = array('username');
  /**
   * สถานะสมาชิก
   * 0 สมาชิกทั่วไป
   * 1 ผู้ดูแลระบบ
   *
   * @var array
   */
  public $member_status = array(
    0 => 'สมาชิกทั่วไป',
    1 => 'ผู้ดูแลระบบ'
  );
  /**
   * สีของสมาชิกตามสถานะ
   *
   * @var array
   */
  public $color_status = array(
    0 => '#259B24',
    1 => '#FF0000',
    2 => '#FF6600',
    3 => '#3366FF',
    4 => '#902AFF',
    5 => '#660000',
    6 => '#336600',
  );
  /**
   * ความกว้างสูงสุดของรูปประจำตัวสมาชิก
   *
   * @var int
   */
  public $user_icon_w = 50;
  /**
   * ความสูงสูงสุดของรูปประจำตัวสมาชิก
   *
   * @var int
   */
  public $user_icon_h = 50;
  /**
   * ชนิดของรูปถาพที่สามารถอัปโหลดเป็นรูปประจำตัวสมาชิก ได้
   *
   * @var array
   */
  public $user_icon_typies = array('jpg', 'jpeg', 'gif', 'png');
  /**
   * ไดเร็คทอรี่เก็บ icon สมาชิก
   *
   * @var string
   */
  public $usericon_folder = 'datas/member/';
  /**
   * กำหนดอายุของแคช (วินาที)
   * 0 หมายถึงไม่มีการใช้งานแคช
   *
   * @var int
   */
  public $cache_expire = 0;
  /**
   * คำอธิบายเกี่ยวกับเว็บไซต์
   *
   * @var string
   */
  public $web_description = 'คชสารเว็บเฟรมเวอร์ค';
  /**
   * ชื่อเว็บไซต์
   *
   * @var string
   */
  public $web_title = 'Kotchasan';
  /**
   * template
   *
   * @var string
   */
  public $skin = 'admin';
  /*
   * คีย์สำหรับการเข้ารหัส
   *
   * @var string
   */
  public $password_key = '12345678';
}
