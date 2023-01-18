<?php
    include_once $_SERVER['DOCUMENT_ROOT'].'/myservice/common/session.php';
    if(isset($_SESSION['myMemberSes'])){
        header("Location:./me.php");
        exit;
    }
?>
<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width" />
<meta charset="utf-8" />
<title>My First Web Service</title>
<link rel="stylesheet" href="./css/cssReset.css" />
<link rel="stylesheet" href="./css/header.css" />
<link rel="stylesheet" href="./css/index.css" />
<link rel="stylesheet" href="./css/footer.css" />
<script type="text/javascript" src="./js/jquery-3.1.0.min.js"></script>
<script type="text/javascript" src="./js/index.js"></script>
<script type="text/javascript" src="./js/valueCheck.js"></script>
</head>
<body>
<?php
  include_once $_SERVER['DOCUMENT_ROOT'].'/myservice/include/header.php';
?>
  <!-- container -->
  <div id="container">
    <section id="introSite">
      <div id="siteComment">
        내가 만드는<br />
        첫 웹서비스에<br />
        어서오세요.
      </div>
      <div id="signUpBtn">
        <p>가입하기</p>
      </div>
    </section>
    <section id="signup">
      <div id="signupCenter">
        <form id="signUpForm" method="post" action="./database/myMember.php">
          <div class="row">
            <div class="inputBox">
              <input type="text" name="userName" id="userName" placeholder="이름" />
            </div>
          </div>
          <div class="row">
            <div class="inputBox">
              <input type="email" name="userEmail" id="userEmail" placeholder="이메일" />
            </div>
          </div>
          <div class="row">
            <div class="inputBox">
              <input type="password" name="userPw" id="userPw" placeholder="비밀번호" />
            </div>
          </div>
          <div class="row">
            <label>생일</label>
            <div class="selectBox">
              <select name="birthYear" id="birthYear">
                <option value="">연도</option>
<?php
  //현재 연도를 구함
  $nowYear = date("Y",time());
  //현재 연도부터 1900년도까지 내림차순으로 option태그 생성
  for($i = $nowYear; $i >= 1900; $i--){?>
                <option value="<?=$i?>"><?=$i?></option>
<?php
  }
?>
              </select>
            </div>

            <div class="selectBox selectBoxMargin">
              <select name="birthMonth" id="birthMonth">
                <option value="">월</option>
<?php
  for($i = 1; $i <= 12; $i++){?>
                <option value="<?=$i?>"><?=$i?></option>
<?php
  }
?>
              </select>
            </div>
            <div class="selectBox">
              <select name="birthDay" id="birthDay">
                <option value="">일</option>
<?php
  for($i = 1; $i <= 31; $i++){?>
                <option value="<?=$i?>"><?=$i?></option>
<?php
  }
?>
              </select>
            </div>
          </div>
          <div class="row genderRow">
            <div id="genderLabel">
              <label for="gW" id="gMW">여성</label>
              <label for="gM" id="gMM">남성</label>
            </div>
            <input type="radio" name="gender" class="gender" id="gW" value="w" />
            <input type="radio" name="gender" class="gender" id="gM" value="m" />
          </div>
          <div class="row">
            <p id="valueError"></p>
          </div>
          <div class="row">
            <div class="submitBox">
              <input type="submit" id="signUpSubmit" value="가입하기" />
            </div>
          </div>
          <input type="hidden" name="mode" value="save" />
        </form>
        <div id="goToLoginBtn">
          <p>로그인하기</p>
        </div>
      </div>
    </section>
  </div>
  <footer>
    <p>My First Web Service</p>
  </footer>
</body>
</html>
