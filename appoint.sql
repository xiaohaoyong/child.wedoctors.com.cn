CREATE TABLE `appoint` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `userid` int(11) NOT NULL DEFAULT '0' COMMENT '用户',
  `doctorid` int(11) NOT NULL DEFAULT '0' COMMENT '社区',
  `createtime` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `appoint_time` tinyint(4) NOT NULL DEFAULT '0' COMMENT '预约时间',
  `appoint_date` int(11) NOT NULL DEFAULT '0' COMMENT '预约日期',
  `type` tinyint(4) NOT NULL DEFAULT '0' COMMENT '预约类型',
  `childid` int(11) NOT NULL DEFAULT '0' COMMENT '关联儿童',
  `phone` bigint(20) NOT NULL DEFAULT '0' COMMENT '预约手机号',
  `state` tinyint(4) NOT NULL DEFAULT '0' COMMENT '预约状态',
  `loginid` int(11) NOT NULL DEFAULT '0' COMMENT '登录ID',
  `remark` varchar(100) NOT NULL DEFAULT '' COMMENT '备注',
  `cancel_type` tinyint(4) NOT NULL DEFAULT '0' COMMENT '取消原因',
  `push_state` tinyint(4) NOT NULL DEFAULT '0' COMMENT '推送状态',
  `mode` tinyint(4) NOT NULL DEFAULT '0' COMMENT '来源',
  `vaccine` int(11) NOT NULL DEFAULT '0' COMMENT '疫苗ID',
  PRIMARY KEY (`id`)
)