CREATE TABLE comments (
  commentsID int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '댓글 번호',
  contentsID int(10) unsigned NOT NULL COMMENT '게시물 번호',
  myMemberID int(10) unsigned NOT NULL COMMENT '회원번호 ',
  comment text NOT NULL COMMENT '댓글 내용',
  regTime int(10) unsigned NOT NULL COMMENT '댓글 등록 시간',
  PRIMARY KEY (commentsID)
)CHARSET=utf8 COMMENT='댓글';
