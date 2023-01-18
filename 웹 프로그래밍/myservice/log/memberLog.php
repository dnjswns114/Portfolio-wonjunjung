<?php
    class memberLog{
        public function writeMemberLog($myLog){

            //로그 파일에 기록할 내용 담을 변수 선언
            $content = '';

            //로그 번호에 따른 내용 쓰기
            switch($myLog['logNum']){

                //회원가입
                case 1:
                    $content = '회원가입';
                    $_SESSION['myMemberSes']['myMemberID'] = $myLog['myMemberID'];
                    break;
                //로그인
                case 2:
                    $content = '로그인';
                    break;
                //포토 업로드
                case 3:
                    $content = '';
                    //프로필포토인지 커버포토인지 구분
                    if($myLog['photoType'] == 'myProfilePhoto'){
                        $content = '프로필';
                    }else if($myLog['photoType'] == 'myCoverPhoto'){
                        $content = '커버';
                    }
                    $content = $content.'사진 등록.';
                    break;
                //게시물 등록
                case 4:
                    $content = '게시물 등록, 게시물 내용 : '.$myLog['contents'];
                    break;
                //공감 반영
                case 5:
                    $content = $myLog['contentsID'].'번 게시물 ';
                    //공감 하기 인지 취소인지 구분
                    if($myLog['myLike'] == true){
                        $content .= '공감함.';
                    }else{
                        $content .= '공감 취소함';
                    }
                    break;
                //코멘트 등록
                case 6:
                    $content = '코멘트 등록, '.$myLog['contentsID'].'번 게시물 - 등록한 코멘트 내용 : '.$myLog['comment'];
                    break;
                //로그아웃
                case 7:
                    $content = '로그아웃';
                    break;
            }

            //파일명
            $fileName = 'myLog_'.$_SESSION['myMemberSes']['myMemberID'].'.txt';

            //로그파일을 생성할 폴더가 있는지 확인 후 없으면 생성
            $dir = $_SERVER['DOCUMENT_ROOT'].'/myservice/log/memberLogs/';

            //이미지 파일을 저장할 폴더가 있는지 확인 없으면 생성
            if(!is_dir($dir)){
                //폴더가 존재하지 않으므로 폴더 생성
                mkdir($dir, 0777);
            }

            //파일이 존재하면 열고 없으면 생성
            $fp = fopen($dir.$fileName,'a');

            //타임스탬프 시간을 사람이 보기 쉽도록 년월일시초분단위로 변경
            $time = date('Y년 m월 d일 H시 i분 s초',$myLog['regTime']);

            //내용 앞에 시간과 공백을 입력
            $content = $time.'  '.$content;

            //줄바꿈 chr은 아스키코드(한글, 일본어 등을 갖고 있지 않음)의 문자를 불러오는 기능
            //10은 아스키코드로 new Line을 의미
            $content .= chr(10);
            //파일 쓰기
            fwrite($fp, $content);

            //파일 닫기
            fclose($fp);
        }
    }
?>