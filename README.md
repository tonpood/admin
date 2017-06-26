# คชสาร เว็บเฟรมเวิร์ค (Kotchasan Web Framework)

Workshop เว็บไซต์ตัวอย่าง ที่มีระบบ Login และ ระบบสมาชิกอย่างง่าย เช่นระบบแอดมิน

## การติดตั้ง
### 1. สร้างฐานข้อมูล ```u``` และ ตารางตามโค้ดด้านล่าง

```
CREATE TABLE `user` (
  `id` int(11) UNSIGNED NOT NULL,
  `username` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `lastvisited` int(11) UNSIGNED NOT NULL,
  `visited` int(11) UNSIGNED NOT NULL,
  `session_id` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `ip` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(2) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `user` (`id`, `username`, `password`, `name`, `lastvisited`, `visited`, `session_id`, `ip`, `status`) VALUES
(1, 'admin', 'f6fdffe48c908deb0f4c3bd36c032e72', 'แอดมิน', 0, 0, '', '', 1),
(2, 'demo', 'c514c91e4ed341f263e458d44b3bb0a7', 'ตัวอย่าง', 0, 0, '', '', 0);
```

### 2. แก้ไขค่าติดตั้งของฐานข้อมูลให้ถูกต้อง ไฟล์ settings/database.php

```
<?php
/* settings/database.php */
return array(
  'mysql' => array(
    'dbdriver' => 'mysql',
    'username' => 'plus',
    'password' => '1234',
    'dbname' => 'u',
    'prefix' => '',
  ),
  'tables' => array(
    'user' => 'user'
  )
);
```

### 3. สร้างไดเร็คทอรี่ ```datas/``` และปรับ chmod เป็น 777

## การใช้งาน
เข้าระบบโดย User : ```admin``` และ Password : ```admin```