<?php
  //id가 viewType인 영역은 세션이 존재하며, me.php페이지와 all.php 파일에서만 표시
  if(strpos($_SERVER['REQUEST_URI'],'me.php') || strpos($_SERVER['REQUEST_URI'], 'all.php')){
?>
<div id="viewType">
  <a href="/myservice/me.php" id="meLink">me</a>
  <a href="/myservice/all.php" id="allLink">all</a>
</div>
<?php
  }
?>
<header>
  <div id="myService">My First Web Service</div>
<?php
  //id가 myName인 영역은 로그인을 한 상태에서 표시하므로 세션이 있으면 표시
  if(isset($_SESSION['myMemberSes'])){
?>
    <div id="myName">
      <p>안녕하세요. <?=$_SESSION['myMemberSes']['userName']?> 님</p>
      <div id="logoutBox">
        <input type="button" id="logoutBtn" value="로그아웃" />
      </div>
    </div>
<?php
  //세션이 존재하지 않으면 로그인 폼을 표시
  }else{
?>
    <div id="loginForm">
      <form name="loginForm" method="post" action="./login.php">
        <div id="loginEmailArea">
          <label for="loginEmail">E-mail</label>
          <div class="loginInputBox">
            <input type="email" id="loginEmail" name="email" placeholder="이메일" />
          </div>
        </div>
        <div id="loginPwArea">
          <label for="loginPw">Password</label>
          <div class="loginInputBox">
            <input type="password" name="userPw" id="loginPw" placeholder="비밀번호 8자 이상 입력" />
          </div>
        </div>
        <div class="loginSubmitBox">
          <input type="submit" id="loginSubmit" value="로그인" />
        </div>
      </form>
    </div>
<?php
  }
?>
</header>
