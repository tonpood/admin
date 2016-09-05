<?php
/*
 * @filesource index/views/register.php
 * @link http://www.kotchasan.com/
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 */

namespace Index\Register;

use \Kotchasan\Html;

/**
 * module=register
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
  public function render()
  {
    // register form
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
      'title' => 'ลงทะเบียนสมาชิกใหม่'
    ));
    // username
    $fieldset->add('text', array(
      'id' => 'register_username',
      'itemClass' => 'item',
      'labelClass' => 'g-input icon-user',
      'label' => 'Username',
      'comment' => 'ภาษาอังกฤษตัวพิมพ์เล็กและตัวเลข ไม่เกิน 20 ตัวอักษร',
      'maxlength' => 20,
      'validator' => array('keyup,change', 'checkUsername', 'index.php/index/model/checker/username')
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
    $fieldset->add('select', array(
      'id' => 'register_status',
      'itemClass' => 'item',
      'label' => 'สถานะ',
      'labelClass' => 'g-input icon-star0',
      'options' => self::$cfg->member_status
    ));
    $fieldset = $form->add('fieldset', array(
      'class' => 'submit'
    ));
    // submit
    $fieldset->add('submit', array(
      'class' => 'button save large',
      'value' => 'ลงทะเบียน'
    ));
    $fieldset->add('hidden', array(
      'id' => 'register_id',
      'value' => 0
    ));
    return $form->render();
  }
}