ALTER TABLE `user` ADD COLUMN `openid` varchar(64) DEFAULT NULL COMMENT '微信openid' AFTER `email`;
CREATE INDEX idx_openid ON `user` (`openid`); 