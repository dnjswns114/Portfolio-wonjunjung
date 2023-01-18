CREATE TABLE mymember (
  myMemberID int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '고객번호',
  userName varchar(60) NOT NULL DEFAULT '' COMMENT '이름',
  email varchar(30) NOT NULL,
  pw varchar(40) NOT NULL COMMENT '비밀번호',
  birthDay char(10) NOT NULL COMMENT '생일',
  gender enum('w','m') NOT NULL COMMENT '성별',
  profilePhoto varchar(80) DEFAULT NULL COMMENT '프로필사진',
  coverPhoto varchar(80) DEFAULT NULL COMMENT '커버사진',
  regTime int(10) unsigned NOT NULL COMMENT '가입시간',
  PRIMARY KEY (myMemberID),
  UNIQUE (email)
)CHARSET=utf8 COMMENT='회원 정보';
