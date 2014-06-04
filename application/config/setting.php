<?php defined('SYSPATH') OR die('No direct access allowed.');

return array(
  'type' => array(
      0  => '全局',
      1  => '区域',
      2  => '地铁',
      3  => '地铁站',
      5  => '学校',
      6  => '商圈',
      9  => '学区',
    ),
  'attachment' => array(
      0  => '全景缩略图',
      1  => '户型',
      2  => '实景',
      3  => '样板',
      4  => '规划',
    ),
  'ugroup' => array(
    0 => '未设定',
    1 => '数据录入组',
    2 => '数据审核组',
    9 => '系统管理员',
  ),
  'module' => array(
      1 => 'house',
      2 => 'aricle',
      4 => 'school',
      8 => 'company',
      1024 => 'setting',
      2048 => 'system',
    ),
  'module_article' => array(
      1 => '房源',
      4 => '学区',
      8 => '公司'
    ),
  'house' => array(
    0 => '不限', 
    1 => '新房', 
    2 => '期房', 
    3 => '租售', 
  ),
  'auth' => array(
      1  =>  array(
          1 => '添加',  
          2 => '编辑',  
          4 => '删除',  
        ),  
      2  => array(
          1 => '添加',  
          2 => '编辑',  
          4 => '删除',  
          8 => '直播',  
        ),  
      4  => array(
          1 => '添加',  
          2 => '编辑',  
          4 => '删除',  
        ),  
      8  => array(
          1 => '添加',  
          2 => '编辑',  
          4 => '删除',  
        ),  
      1024  => array(
          1 => '广告',  
          2 => '问答',  
          4 => '关键词过滤',  
        ),  
      2048  => array(
          1 => '核心'
        )  
      ),
  'ad' => array(
    0 => '文本信息',
    1 => '文本链接',
    2 => '图片连接',
    4 => '高级',
  ),
  'var' => array(
    'house_building' => '建筑类型',
    'house_decorate' => '装饰程度',
    'school_level' => '学校资质',
    'school_characteristic' => '学校特色',
    'school_type' => '学校类型',
    'school_limit' => '落户年限',
  )
);
