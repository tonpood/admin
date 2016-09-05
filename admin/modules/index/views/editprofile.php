<?php
/*
 * @filesource index/views/editprofile.php
 * @link http://www.kotchasan.com/
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 */

namespace Index\Editprofile;

use \Kotchasan\Html;
use \Kotchasan\Login;

/**
 * module=editprofile
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class View extends \Kotchasan\View
{

  /**
   * แสดงผล
   */
  public function render($user)
  {
    // editprofile form
    $form = Html::create('form', array(
        'id' => 'setup_frm',
        'class' => 'setup_frm',
        'autocomplete' => 'off',
        'action' => 'index.php/index/model/updateprofile/save',
        'onsubmit' => 'doFormSubmit',
        'token' => true,
        'ajax' => true
    ));
    $fieldset = $form->add('fieldset', array(
      'title' => 'ข้อมูลสมาชิก'
    ));
    $groups = $fieldset->add('groups');
    // username
    $groups->add('text', array(
      'id' => 'register_username',
      'itemClass' => 'width50',
      'labelClass' => 'g-input icon-user',
      'label' => 'Username',
      'comment' => 'ภาษาอังกฤษตัวพิมพ์เล็กและตัวเลข ไม่เกิน 20 ตัวอักษร',
      'maxlength' => 20,
      'disabled' => Login::isAdmin() ? false : true,
      'validator' => array('keyup,change', 'checkUsername', 'index.php/index/model/checker/username'),
      'value' => $user->username
    ));
    // name
    $groups->add('text', array(
      'id' => 'register_name',
      'itemClass' => 'width50',
      'labelClass' => 'g-input icon-user',
      'label' => 'ชื่อ นามสกุล',
      'maxlength' => 150,
      'value' => $user->name
    ));
    $groups = $fieldset->add('groups');
    // password
    $groups->add('password', array(
      'id' => 'register_password',
      'itemClass' => 'width50',
      'labelClass' => 'g-input icon-password',
      'label' => 'รหัสผ่าน',
      'comment' => 'รหัสผ่านต้องไม่น้อยกว่า 4 ตัวอักษร',
      'maxlength' => 20,
      'validator' => array('keyup,change', 'checkPassword')
    ));
    // repassword
    $groups->add('password', array(
      'id' => 'register_repassword',
      'itemClass' => 'width50',
      'labelClass' => 'g-input icon-password',
      'label' => 'รหัสผ่านอีกครั้ง',
      'comment' => 'กรอกรหัสผ่านอีกครั้ง',
      'maxlength' => 20,
      'validator' => array('keyup,change', 'checkPassword')
    ));
    // icon
    if (is_file(ROOT_PATH.self::$cfg->usericon_folder.$user->id.'.jpg')) {
      $img = WEB_URL.self::$cfg->usericon_folder.$user->id.'.jpg';
    } else {
      $img = WEB_URL.'skin/img/noicon.jpg';
    }
    $fieldset->add('file', array(
      'id' => 'icon',
      'labelClass' => 'g-input icon-upload',
      'itemClass' => 'item',
      'label' => 'รูปประจำตัว',
      'comment' => 'เลือกรูปภาพประจำตัวสมาชิก ชนิด '.implode(', ', self::$cfg->user_icon_typies).' (ปรับขนาดอัตโนมัติ)',
      'accept' => self::$cfg->user_icon_typies,
      'dataPreview' => 'imgPicture',
      'previewSrc' => $img
    ));
    $fieldset->add('select', array(
      'id' => 'register_status',
      'itemClass' => 'item',
      'label' => 'สถานะ',
      'labelClass' => 'g-input icon-star0',
      'options' => self::$cfg->member_status,
      'value' => $user->status
    ));
    $fieldset = $form->add('fieldset', array(
      'class' => 'submit'
    ));
    // submit
    $fieldset->add('submit', array(
      'class' => 'button save large',
      'value' => 'บันทึก'
    ));
    $fieldset->add('hidden', array(
      'id' => 'register_id',
      'value' => $user->id
    ));
    return $form->render();
  }
}