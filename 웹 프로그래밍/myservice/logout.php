<?php
  session_start();
  include_once $_SERVER['DOCUMENT_ROOT'].'/myservice/log/memberLog.php';
  //나중에 로그기능 구현
  $myLog = array();
  //로그 번호
  $myLog['logNum'] = 7;
  //로그 생성 시간
  $myLog['regTime'] = time();

  //memberLog의 인스턴스 생성
  $memberLog = new memberLog;
  //로그 정보 전달
  $memberLog->writeMemberLog($myLog);

  //세션 제거
  unset($_SESSION['myMemberSes']);

  //세션 제거 확인 후 메인페이지로 이동
  if(!isset($_SESSION['myMemberSes'])){
    header("Location:./index.php");
  }
?>
