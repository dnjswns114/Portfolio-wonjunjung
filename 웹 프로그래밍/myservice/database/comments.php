<?php
    include_once $_SERVER['DOCUMENT_ROOT'].'/myservice/common/session.php';
    include_once $_SERVER['DOCUMENT_ROOT'].'/myservice/log/memberLog.php';
    class comments extends memberLog{
        //데이터베이스 접속 정보를 대입할 프라퍼티
        protected $dbConnection = null;
        //mode
        protected $mode;

        protected function dbConnection(){
            include_once $_SERVER['DOCUMENT_ROOT'].'/myservice/connect/connect.php';
        }

        function __construct(){
            if(isset($_POST['mode'])){
                //댓글 등록
                if($_POST['mode'] == 'save'){
                    $this->commentsSave($_POST['contentsID'], $_POST['comment']);
                }
            }
        }

        //댓글저장 메서드
        protected function commentsSave($contentsID, $comment){

            //게시물 번호
            $contentsID = (int) $_POST['contentsID'];
            //파라미터 유효성 확인
            $errorCheck = false;

            //게시물 번호가 양의 정수 맞는지 확인
            if($contentsID == 0){
                $errorCheck = true;
            }

            //댓글 내용이 공백인지 확인
            if($comment == ''){
                $errorCheck = true;
            }

            //유효하지 않은 값 이라고 알림
            if($errorCheck == true){
                echo json_encode(array(
                    'result' => false
                ));
            }

            //입력하는 사람의 회원번호
            $myMemberID = $_SESSION['myMemberSes']['myMemberID'];

            $this->dbConnection();

            //댓글 내용 real_escape_string처리
            $comment = $this->dbConnection->real_escape_string($comment);

            //댓글 등록 시간
            $time = time();

            //댓글 입력 쿼리문
            $sql = "INSERT INTO comments(contentsID, myMemberID, comment, regTime) ";
            $sql .= "VALUES({$contentsID}, {$myMemberID}, '{$comment}', '{$time}')";
            $res = $this->dbConnection->query($sql);

            $result = false;
            if($res){
                //나중에 댓글 등록 로그 남김
                //memberLog에 전달할 로그 정보를 배열로 전달
                $myLog = array();
                //로그 번호
                $myLog['logNum'] = 6;
                //로그 생성 시간
                $myLog['regTime'] = $time;
                //댓글을 등록한 게시물 번호
                $myLog['contentsID'] = $contentsID;
                //댓글 내용
                $myLog['comment'] = $comment;
                //로그 정보 전달
                $this->writeMemberLog($myLog);

                $result = true;
            }

            //입력한 결과와 정보를 AJAX에서 처리할 수 있도록 전달
            echo json_encode(array(
                'result' => $result,
                'poster' => $_SESSION['myMemberSes']['userName'],
                'profilePhoto' => $_SESSION['myMemberSes']['profilePhoto'],
                'regTime' => $time
            ));
        }
    }
    $comments = new comments;
?>