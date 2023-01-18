CREATE TABLE contents (
  contentsID int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '게시물 번호',
  myMemberID int(10) unsigned NOT NULL COMMENT '작성한 고객번호',
  content longtext NOT NULL COMMENT '게시물 내용',
  regTime int(10) unsigned NOT NULL COMMENT '등록 시간',
  PRIMARY KEY (contentsID)
) DEFAULT CHARSET=utf8 COMMENT='게시물';
