<?php
    include_once $_SERVER['DOCUMENT_ROOT'].'/myservice/common/session.php';
    include_once $_SERVER['DOCUMENT_ROOT'].'/myservice/log/memberLog.php';
    class likes extends memberLog{
        //데이터베이스 접속 정보를 대입할 프라퍼티
        protected $dbConnection = null;
        //mode
        protected $mode;

        protected function dbConnection(){
            include_once $_SERVER['DOCUMENT_ROOT'].'/myservice/connect/connect.php';
        }

        function __construct(){
            if(isset($_POST)){
                //댓글 등록
                if($_POST['mode'] == 'save'){
                    $this->likesSave($_POST['contentsID']);
                }
            }
        }

        //공감저장 메서드
        protected function likesSave($contentsID){
            //게시물 번호 추출
            $contentsID = (int) str_replace('likes', '', $contentsID);

            //유효성 확인
            if($contentsID != 0){
                //공감정보 저장

                //회원번호
                $myMemberID = $_SESSION['myMemberSes']['myMemberID'];

                //회원번호와 게시물 번호가 일치하는 레코드를 찾음
                $sql = "SELECT * FROM likes WHERE contentsID = {$contentsID}";
                $sql .= " AND myMemberID = {$myMemberID}";
                $this->dbConnection();
                $res = $this->dbConnection->query($sql);

                //myLike는 해당게시물의 공감 여부 정보를 대입할 변수
                $myLike = '';
                //확인 후 레코드가 없다면 공감하기이므로 레코드를 추가한다.
                if($res->num_rows == 0){
                    //공감한 시간
                    $time = time();

                    $sql = "INSERT INTO likes (contentsID, myMemberID, regTime)";
                    $sql .= " VALUES({$contentsID}, {$myMemberID}, {$time})";
                    $this->dbConnection->query($sql);
                    //공감했다는 뜻으로 $myLike에 true 대입
                    $myLike = true;
                }

                //확인 후 레코드가 있다면 공감 취소이므로 레코드를 삭제한다.
                else{
                    $sql = "DELETE FROM likes WHERE contentsID = {$contentsID}";
                    $sql .= " AND myMemberID = {$myMemberID}";//AND 앞 띄어쓰기 있습니다.
                    $this->dbConnection->query($sql);

                    //공감취소했으므로 $myLike에 false대입
                    $myLike = false;
                }

                //나중에 공감기능의 로그를 이곳에 만듬
                //memberLog에 전달할 로그 정보를 배열로 전달
                $myLog = array();
                //로그번호
                $myLog['logNum'] = 5;
                //게시물 등록 시간
                $myLog['regTime'] = $time;
                //공감하기인지 공감취소인지 구분
                $myLog['myLike'] = $myLike;
                //대상 게시물번호
                $myLog['contentsID'] = $contentsID;
                //로그 정보 전달
                $this->writeMemberLog($myLog);

                //해당 게시물의 최신 공감수를 구해서 AJAX에 같이 보낸다 최신 값 갱신 위함
                $sql = "SELECT * FROM likes WHERE contentsID = {$contentsID}";
                $res = $this->dbConnection->query($sql);
                $count = $res->num_rows;

                echo json_encode(array(
                    'result' => true,
                    'count' => $count,
                    'myLike' => $myLike

                ));
            }else{
                echo json_encode(array(
                   'result' => false,
                ));
            }
        }
    }
    $likes = new likes;
?>