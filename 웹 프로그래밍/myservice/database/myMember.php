<?php
  include_once $_SERVER['DOCUMENT_ROOT'].'/myservice/common/session.php';
  include_once $_SERVER['DOCUMENT_ROOT'].'/myservice/log/memberLog.php';

  class myMember extends memberLog{
    //데이터베이스 접속 정보를 대입할 프라퍼티
    protected $dbConnection = null;

    //mode
    protected $mode;
    //데이터베이스 접속 정보를 가져오는 메서드
    protected function dbConnection(){
      include_once $_SERVER['DOCUMENT_ROOT'].'/myservice/connect/connect.php';
    }

    //생성자
    function __construct(){
      //mode값에 따라 메서드 호출
      if(isset($_POST['mode'])){
        $this->mode = $_POST['mode'];
        //mode의 값에 따라 메서드 호출
        if($this->mode == 'emailCheck'){
          $this->emailCheck($_POST['userEmail']);
        }else if($this->mode == 'save'){
          $this->signUp();
        }else if($this->mode == 'photo'){
          $this->photoSave();
        }
      }
    }

    //회원가입(회원 정보 저장) 메서드
    function signUp(){
      //이름의 앞 뒤 공백 삭제
      $userName = trim($_POST['userName']);

      //한글 또는 영문으로 구성되어 있는지 확인
      if(!preg_match('/^[a-zA-Z가-힣]+$/', $userName)){
        echo '올바른 이름이 아닙니다.';
        exit;
      }

      //이메일 주소의 앞 뒤 공백 삭제
      $userEmail = trim($_POST['userEmail']);

      //이메일 유효성 체크
      if(!filter_Var($userEmail, FILTER_VALIDATE_EMAIL)){
        echo '올바른 이메일이 아닙니다.';
        exit;
      }

      //비밀번호
      $userPw = $_POST['userPw'];

      //비밀번호가 공백인지 확인
      if($userPw == ''){
        echo '비밀번호 값이 공백입니다.';
        exit;
      }

      //비밀번호 암호화
      $userPw = sha1('mySalt'.$userPw);

      //생년 숫자(정수)로 형변환
      $birthYear = (int) $_POST['birthYear'];

      //공백인지 확인
      if($birthYear == ''){
        echo '생년 값이 빈값입니다.';
        exit;
      }

      //올바른 값을 입력했는지 확인
      $birthYearCheck = false;
      //올해의 년도
      $thisYear = date('Y', time());
      for($i = 1900; $i <= $thisYear; $i++){
        //입력받은 생년월일이 일치하면 정상값
        if($i == $birthYear){
          $birthYearCheck = true;
          break;
        }
      }

      if($birthYearCheck == false){
        echo '올바른 생년 값이 아닙니다.';
        exit;
      }

      //생월 숫자(정수)로 형변환
      $birthMonth = (int) $_POST['birthMonth'];
      if($birthMonth == ''){
        echo '생월 값이 빈값입니다.';
        exit;
      }

      //올바른 값을 입력했는지 확인
      $birthMonthCheck = false;

      for($i = 1; $i <= 12; $i++){
        if($i == $birthMonth){
          $birthMonthCheck = true;
          break;
        }
      }

      if($birthMonthCheck == false){
        echo '올바른 생월 값이 아닙니다.';
        exit;
      }

      //생일 숫자(정수)로 형변환
      $birthDay = (int) $_POST['birthDay'];
      if($birthDay == ''){
        echo '생일 값이 빈값입니다.';
        exit;
      }

      //올바른 값을 입력했는지 확인
      $birthDayCheck = false;
      for($i = 1; $i <= 31; $i++){
        if($i == $birthDay){
          $birthDayCheck = true;
          break;
        }
      }

      if($birthDayCheck == false){
        echo '올바른 값이 아닙니다.';
        exit;
      }

      //데이터베이스에 입력할 값으로 변경
      $birth = $birthYear.'-'.$birthMonth.'-'.$birthDay;

      //성별 검사
      $gender = $_POST['gender'];

      //값이 m이거나 w인지 확인
      $genderCheck = false;

      switch($gender){
        case 'm';
        case 'w';
        $genderCheck = true;
        break;
      }

      if($genderCheck == false){
        echo '올바른 성별 정보가 아닙니다.';
        exit;
      }

      //여기까지 오면 입력받은 정보가 모두 검증이 완료

      //이름정보를 real_escape_string 처리
      //데이터베이스 입력정보가 필요하므로 정보를 담고 있는 dbConnection메서드를 호출
      $this->dbConnection();
      $userName = $this->dbConnection->real_escape_string($userName);

      //기본 프로필 사진 주소 설정
      $profilePhoto = '';
      if($gender == 'm'){
        $profilePhoto = '/myservice/images/me/boy.png';
      }else if($gender == 'w'){
        $profilePhoto = '/myservice/images/me/girl.png';
      }

      //기본 커버 사진 설정
      $coverPhoto = '/myservice/images/me/happyCat.png';

      //회원가입시간
      $regTime = time();

      //데이터베이스에 입력
      $sql = "INSERT INTO mymember(userName, email, pw, birthDay, gender, profilePhoto, coverPhoto, regtime) ";
      $sql .= "VALUES('{$userName}','{$userEmail}','{$userPw}','{$birth}','{$gender}','{$profilePhoto}','{$coverPhoto}','{$regTime}')";

      $res = $this->dbConnection->query($sql);


      //쿼리 질의 성공시
      if($res){
        //나중에 이곳에 회원가입로그 만듬
        //memberLog에 전달할 로그 정보를 배열로 전달
        $myLog = array();
        //로그번호
        $myLog['logNum'] = 1;
        //회원가입 시간
        $myLog['regTime'] = $regTime;
        //회원번호
        $myLog['myMemberID'] = $this->dbConnection->insert_id;
        //로그 정보 전달
        $this->writeMemberLog($myLog);

        //회원가입에 성공했으므로 세션생성
        $_SESSION['myMemberSes']['email'] = $userEmail;
        $_SESSION['myMemberSes']['userName'] = $userName;
        //insert_id는 입력한 정보의 primary_key(고객번호)를 반환
        $_SESSION['myMemberSes']['myMemberID'] = $this->dbConnection->insert_id;
        $_SESSION['myMemberSes']['profilePhoto'] = $profilePhoto;
        $_SESSION['myMemberSes']['coverPhoto'] = $coverPhoto;



        //세션 생성 후 나의 페이지로 이동
        header("Location:../me.php");
      }else{
        echo "<script>alert('실패'); location.href='../index.php';</script>";
        exit;
      }

    }

    //이메일 중복 체크 메서드
    function emailCheck($email){
      //이메일 사용가능 여부의 리턴 값으로 초기값 false 대입
      $result = false;

      //이메일유효성 검사
      if(filter_var($email, FILTER_VALIDATE_EMAIL)){

        //같은 이메일 주소 있는지 찾는 쿼리문
        $sql = "SELECT * FROM mymember WHERE email = '{$email}'";
        $this->dbConnection();
        $res = $this->dbConnection->query($sql);

        //데이터베이스에서 가져온 결과 수를 체크하여 0이면 사용 가능
        //0이 아니면 이미 존재하는 이메일
        if($res->num_rows == 0){
          $result = true;
        }
      }

      //값 전달
      echo json_encode(array(
        'result' => $result
      ));
    }

    //포토 업로드 메서드
    function photoSave(){
      //업로드할 포토가 프로필 사진인지 커버 사진인지 대입
      $uploadPhotoType = '';

      //업로드할 포토의 구분에 따라 달라지는 폴더명의 대입
      $uploadPhotoFolder = '';

      //프로필 포토 업로드 인지 확인
      if(isset($_FILES['myProfilePhoto'])){
        $uploadPhotoType = 'myProfilePhoto';
        $uploadPhotoFolder = 'myMemberProfilePhoto';
      }

      //커버 포토 업로드 인지 확인
      else if(isset($_FILES['myCoverPhoto'])){
        $uploadPhotoType = 'myCoverPhoto';
        $uploadPhotoFolder = 'myMemberCoverPhoto';
      }

      //업로드 된 임시 파일 대입
      $myTempFile = $_FILES[$uploadPhotoType]['tmp_name'];

      //파일 타입 및 확장자 구하기
      $fileTypeExtension =explode("/", $_FILES[$uploadPhotoType]['type']);

      //파일 타입
      $fileType = $fileTypeExtension[0];

      //파일 확장자
      $extension = $fileTypeExtension[1];

      //이미지 파일이 맞는지 확인
      if($fileType != 'image'){
        echo "<script>alert('이미지 파일만 가능');location.href='../me.php';</script>";
        exit;
      }

      //저장할 파일명을 생성 ex) $uploadPhotoFolder861225.jpg
      $makingFileName = $uploadPhotoFolder.$_SESSION['myMemberSes']['myMemberID']."."."{$extension}";

      //이미지파일을 저장할 실제 폴더명과 파일명 정보 생성
      $myFile = "../images/".$uploadPhotoFolder."/{$makingFileName}";

      //이미지 파일을 저장할 폴더가 있는지 확인 없으면 생성
      $dir = "../images/".$uploadPhotoFolder."/";

      //이미지 파일을 저장할 폴더가 있는지 확인 없으면 생성
      if(!is_dir($dir)){
        //폴더가 존재 하지 않으므로 폴더 생성
        mkdir($dir,0777);
      }

      //폴더 존재 여부 확인
      $isDir = is_dir($dir);

      //폴더 없으때 처리
      if($isDir == false){
        echo "<script><alert('이미지를 저장할 폴더가 없습니다. error - 3');location.href='../me.php';</script>";
        exit;
      }

      //폴더 열기
      $opendir = opendir($dir);

      //폴더 열기 실패시 처리
      if($opendir == false){
        echo "<script>alert('이미지를 저장할 폴더를 열 수 없습니다. error - 4');location.href='../me.php';</script>";
        exit;
      }

      //임시 저장된 파일을 우리가 저장할 폴더로 옮김
      $imageUpload = move_uploaded_file($myTempFile,$myFile);

      //임시 저장된 파일을 저장할 폴더로 옮기기 실패시 처리
      if($imageUpload = false){
        echo "<script>alert('사진 업로드 중 오류가 발생했습니다. - error - 5');location.href='../me.php';</script>";
        exit;
      }

      //이미지가 해당 폴더에 업로드 성공하면 데이터베이스 해당 이미지 정보를 mymember 테이블에 넣음
      //mymember 테이블에 넣을 정보를 생성
      $imageFilePath = '/myservice/images/'.$uploadPhotoFolder.'/'.$makingFileName;

      //위에서 생성한 정보를 데이터베이스에 입력

      //필드를 대입할 변수 초기화
      $photoField = '';
      //커버, 프로필 사진 구분에 따른 테이블의 필드 지정
      if($uploadPhotoType == 'myProfilePhoto'){
        $photoField = 'profilePhoto';
      }else if($uploadPhotoType == 'myCoverPhoto'){
        $photoField = 'coverPhoto';
      }

      $this->dbConnection();
      $filePath = $this->dbConnection->real_escape_string($imageFilePath);

      //사진 정보 업데이트할 회원 번호
      $myMemberID = $_SESSION['myMemberSes']['myMemberID'];

      //이미 있는 레코드에 포토 정보를 적용하므로 UPDATE문 사용
      $sql = "UPDATE mymember SET {$photoField} = '{$filePath}' WHERE myMemberID = {$myMemberID}";
      $res = $this->dbConnection->query($sql);

      //쿼리문 질의 성공시
      if($res){
        //나중에 이곳에 사진 업로드 로그 만듬
        //memberLog에 전달할 로그 정보를 배열로 전달
        $myLog = array();
        //로그번호
        $myLog['logNum'] = 3;
        //포토등록 시간
        $myLog['regTime'] = time();
        //프로필포토인지 커버포토인지 구분
        $myLog['photoType'] = $uploadPhotoType;
        //로그 정보 전달
        $this->writeMemberLog($myLog);

        //유저 세션 이미지 정보 변경
        $_SESSION['myMemberSes'][$photoField] = $filePath;

        //나의 페이지로 이동
        header("Location:../me.php");
      }else{
        echo "<script>alert('사진 업로드 중 오류가 발생했습니다. error - 6'); location.href='../me.php'</script>";
        exit;
      }
    }

  }
  $myMember = new myMember;
?>
