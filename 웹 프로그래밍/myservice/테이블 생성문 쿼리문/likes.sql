CREATE TABLE likes (
  likesID int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '공감 번호',
  contentsID int(10) unsigned NOT NULL COMMENT '게시물 번호',
  myMemberID int(10) unsigned NOT NULL COMMENT '고객번호',
  regTime int(10) unsigned NOT NULL COMMENT '공감 누른 시간',
  PRIMARY KEY (likesID)
) ENGINE=InnoDB CHARSET=utf8 COMMENT='공감하기';
