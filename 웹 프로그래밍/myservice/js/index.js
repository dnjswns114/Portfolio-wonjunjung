$(document).ready(function(){
  //이곳에 jQuery 소스를 작성
  var signUpBtn = $('#signUpBtn');
  //회원가입폼
  signup = $('#signup');
  //로그인폼
  loginForm = $('#loginForm');
  //내가 만드는 첫 웹서비스 문구~~
  introSite = $('#introSite');
  //[가입하기] 버튼 클릭 이벤트
  signUpBtn.click(function(){
    //로그인 폼 숨기기
    loginForm.slideUp();
    //회원가입 폼 보이기
    signup.slideDown();
    //내가 만드는 첫 웹서비스~~ 문구 숨기기
    introSite.slideUp();
  });

  //로그인하기 버튼
  var goToLoginBtn = $('#goToLoginBtn');
  //로그인하기 버튼 클릭 이벤트
  goToLoginBtn.click(function(){
    //로그인 폼 보이기
    loginForm.slideDown();
    //회원가입 폼 숨기기
    signup.slideUp();
    //내가 만드는 첫 웹서비스 문구 보이기
    introSite.slideDown();
  })

  //성별컨트롤
  //여성 버튼
  var genderWoman = $('#gMW')
  //남성 버튼
  var genderMan = $('#gMM')

  //여성 버튼 클릭 이벤트
  genderWoman.click(function(){
    //배경색과 테스트의 색 초기화 함수 호출
    genderBgInit();
    $(this).css('background','#64cbf9');
    $(this).css('color','#fff');
  });

  //남성 버튼 클릭 이벤트
  genderMan.click(function(){
    //배경색과 테스트의 색 초기화 함수 호출
    genderBgInit();
    $(this).css('background','#64cbf9');
    $(this).css('color','#fff');
  })

  //배경색과 테스트의 색 초기화 함수
  function genderBgInit(){
    genderMan.css('background','#fff');
    genderWoman.css('background','#fff');
    genderMan.css('color','#000');
    genderWoman.css('color','#000');
  }

  toGoToShort = false;
  $(window).resize(function(){
    if(window.innerWidth >= 1200){
      loginForm.slideDown();
      signup.slideDown();
      introSite.slideDown();
      toGoToShort = true;
    }else{
      if(toGoToShort == true){
        signup.slideUp();
      }
    }
  });
})
