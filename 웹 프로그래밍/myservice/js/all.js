$(document).ready(function(){

    //게시물 입력 공간의 게시글 입력 폼
    var meContent = $('#meContent');

    //게시물 입력 공간의 게시 버튼
    var mePostBtn = $('#mePostBtn');

    //게시버튼 클릭 이벤트
    mePostBtn.click(function(){
        if(meContent.val() == ''){
            alert('내용을 입력하세요.');
            meContent.focus();
            return false;
        }
        //백엔드 프로젝트에서 게시물 등록 기능 구현
        $.ajax({
            type: 'post', //post 전송 방식으로 전달
            dataType: 'json', //json 언어로 전달
            url: './database/contents.php',
            data: {mode: 'save', meContent: $('#meContent').val()}, //전달할 데이터
            async: false, //값을 전달 받은 후 실행

            success: function (data) {
                console.log(data.result);
                if(data.result == true){
                    //새로 고침
                    location.reload();
                }else{
                    alert('게시글 등록이 실패하였습니다.');
                }
            },
            error: function (request, status, error) {
                console.log('request'+request);
                console.log('status'+status);
                console.log('error'+error);
            }
        });
    });

    //로그아웃 페이지 이동 기능 구현

    //[로그아웃] 버튼
    var logoutBtn = $('#logoutBtn');

    //[로그아웃] 버튼 클릭 이벤트
    logoutBtn.click(function(){
        location.href = '/myservice/logout.php';
    });

    //스크롤이 80% 도달시 게시물 더 불러오기 기능 구현.
    //스크롤 이벤트
    $(window).scroll(function(){
        console.log('in scroll');

        //페이지가 0이면 스크롤 이벤트를 실행하지 않음.
        if($('#page').val() == 0){
            return false;
        }

        //현재의 스크롤 값
        var nowScrollVar = $(document).scrollTop();

        //백분율 값 구하기
        var nowPercent = nowScrollVar / $(document).height() * 100;

        //스크롤이 화면의 80%가 넘어가면 게시물 더 불러오기 기능 실행
        if(nowPercent >= 80){
            $.ajax({
                type : 'post',
                dataType : 'json',
                url : './database/contents.php',
                data : {mode : 'loadMore', contentsLoadType : 'all', page : $('#page').val()},
                async : false,
                success: function (data){
                    console.log(data);

                    //데이터 불러오기 성공시
                    if(data.result == true){
                        //게시물을 변수에 대입
                        var content = data.content;
                        console.log('content is ' + JSON.stringify(content));

                        //가져온 게시물의 수가 20개 미만이면 page의 값을 0으로 변경
                        if(content.length < 20){
                            //페이지 값 0
                            $('#page').val(0);
                            //게시물이 없음을 알리는 문구 노출
                            $('#noContents').show();
                        }
                        //게시물이 20개 이상이면 현재 페이지를 갱신
                        else{
                            //현재 페이지의 수에서 1을 더함
                            pageUpdate = parseInt($('#page').val()) + 1;
                            //1을 더한 값을 적용

                            $('#page').val(pageUpdate);
                        }

                        //게시물 데이터 id가 center인 엘리먼트에 넣기위해
                        //게시물의 HTML 태그를 만듬

                        //생성할 HTML코드를 대입할 변수 선언
                        var inputHtml = '';

                        //for( in )은 php의  foreach와 같은 기능
                        for(var contents in content){
                            inputHtml += "<div class='reading'>";
                            inputHtml += "<div class='writerArea'>";
                            inputHtml += "<img src='"+content[contents]['profilePhoto']+"' />";
                            inputHtml += "<div class='writingInfo'>";
                            inputHtml += "<p>"+content[contents]['userName']+"</p>";

                            //타임스탬프 시간을 사람이 이해할 수 있는 시간으로 변경
                            var d = new Date(content[contents]['regTime'] * 1000);
                            //월은 0부터 시작하므로 +1을 더해야 함
                            var month = d.getMonth()+1;
                            //시간 만들기
                            var regTime = d.getFullYear()+'년 '+month+'월 '+d.getDate()+'일 '+d.getHours()+'시 '+ d.getMinutes()+'분';

                            //사용자가 내용에 태그 입력시를 대비해서 특수기호를 HTML코드로 변경
                            bbs = content[contents]['content'];
                            bbs = bbs.replace(/</g,'&lt;');
                            bbs = bbs.replace(/>/g,'&gt;');

                            inputHtml += "<div class='writingDate'>"+regTime+"</div>";
                            inputHtml += "</div></div>";
                            inputHtml += "<span class='content'>"+bbs+"</span>";
                            inputHtml += "<div class='likeArea'>";

                            //myLike값이 1이면 공감한 게시물 이므로 배경색을 변경
                            inputHtml += "<div class='likeNum likes" + content[contents]['contentsID'] + "' style='background:" + ((content[contents]['myLike'] == 1) ? '#f9d1e4' : '#fff') + "'>공감 수 : " + content[contents]['myLike'] + "</div>";

                            //자신이 공감한 게시물에는 '취소'문구를 공감하지 않은 게시물에는 '하기'문구를 selectLike 변수에 대입
                            var selectLike = ((content[contents]['myLike'] == 1) ? '취소' : '하기');

                            //selectLike값을 표시
                            inputHtml += "<div class='likeBtn' id='likes" + content[contents]['contentsID'] + "'>공감" +selectLike  + "</div>";
                            inputHtml += "<div class='contentsID'>컨텐츠 번호 : " + content[contents]['contentsID'] + "</div>";
                            inputHtml += "</div>";




                            //댓글 영역 프로그래밍 시작
                            inputHtml += "<div class='myCommentArea myCommentArea"+ content[contents]['contentsID'] +"'>";

                            //댓글 정보를 변수에 대입
                            var comments = content[contents]['comment'];

                            for(var comment in comments){
                                inputHtml += "<div class='commentBox'>";
                                inputHtml += "<img src='" + comments[comment]['profilePhoto'] + "' />";

                                //타임스탬프 시간을 사람이 이해할 수 있는 시간으로 변경
                                var d = new Date(content[contents]['regTime'] * 1000);
                                //월은 0부터 시작하므로 +1을 더해야 함
                                var month = d.getMonth() + 1;
                                //시간 만들기
                                var regTime = d.getFullYear() + '년 ' + month +'월 ' + d.getDate() + '일 ' + d.getHours() + '시 ' +  d.getMinutes() + '분';

                                //태그 입력시를 대비해서 특수기호를 HTML코드로 변경
                                bbs2 = comments[comment]['comment'];
                                bbs2 = bbs2.replace(/</g,'&lt;');
                                bbs2 = bbs2.replace(/>/g,'&gt;');

                                inputHtml += "<p class='commentRegTime'>" + regTime + "</p>";
                                inputHtml += "<p class='commentPoster'>" + comments[comment]['userName'] + "</p>";
                                inputHtml += "<p class='writtenComment'>" + bbs2 + "</p>";
                                inputHtml += "</div>";
                            }
                            //댓글 영역 끝


                            inputHtml += "</div>";

                            inputHtml += "<div class='inputBox'>";
                            inputHtml += "<img src='"+content[contents]['profilePhoto']+"' />";
                            inputHtml += "<input type='text' class='inputComment comments"+content[contents]['contentsID']+"' placeholder='코멘트 입력' />";
                            inputHtml += "<div class='regCommentBox'>";
                            inputHtml += "<input type='button' class='regCommentBtn' id='comments"+content[contents]['contentsID']+"' value='게시' />";
                            inputHtml += "</div></div></div></div>";
                        }

                        //위에서 완성된 HTML코드를 id가 container인 엘리먼트에 넣음
                        $('#container').append(inputHtml);

                    }
                },
                error:function(request, status, error){
                    console.log('request ' + request);
                    console.log('status ' + status);
                    console.log('error ' + error);
                }

            });
        }

    });

    //댓글 게시 버튼 클릭 이벤트
    //댓글 게시 버튼 클릭 이벤트 지금까지와는 다른 모습의 클릭 이벤트입니다.
    $(document).on('click', '.regCommentBtn', function(){
        //클릭한 버튼에 적용된 id값을 가져옴
        var catchID = $(this).attr('id');

        //클릭한 버튼에 적용된 id값과 동일한 클래스의 값을 가져옴
        //이것은 입력한 댓글 내용을 가져옴
        comment = $('.' + catchID).val();

        //코멘트 내용이 공백인지 확인
        if(comment == ''){
            alert('내용을 입력하세요.');
            return false;
        }

        //댓글을 등록할 게시물의 번호를 추출함
        contentsID = catchID.replace('comments','');

        //댓글 등록 메서드에 전송
        $.ajax({
            type:'post', //post 전송 방식으로 전달
            dataType:'json', //json 언어로 전달
            url:'./database/comments.php',
            data:{mode: 'save', contentsID: contentsID, comment: comment},
            async: false, //값을 전달 받은 후 실행

            success:function (data) {
                console.log(data);

                //여기에서 등록한 댓글을 화면에 표시하는 기능 구현해야함
                if(data.result == true){
                    //등록시간 만들기
                    var d = new Date(data.regTime * 1000);
                    var month = d.getMonth() + 1;
                    var regTime = d.getFullYear() + '년 ' + month + '월 ' + d.getDate() + '일 ' + d.getHours() + '시 ' + d.getMinutes() + '분';

                    //댓글영역의 HTML 코드 만들기
                    var inputHtml = "";
                    inputHtml += "<div class='commentBox'>";
                    inputHtml += "<img src='" + data.profilePhoto + "' />";
                    inputHtml += "<p class='commentRegTime'>" + regTime + "</p>";
                    inputHtml += "<p class='commentPoster'>" + data.poster + "</p>";
                    inputHtml += "<p class='writtenComment'>" + comment + "</p>";
                    inputHtml += "</div>";

                    //댓글 표시 영역에 위에서 만든 HTML코드를 넣음
                    console.log('output == contentsID is ' + contentsID);
                    $('.myCommentArea' + contentsID).append(inputHtml);

                    //댓글 입력폼에 적혀있는 내용을 삭제
                    $('.' + catchID).val('');
                }else{
                    alert('댓글 등록 실패');
                }

            },
            error:function(request, status, error) {
                console.log('request '+request);
                console.log('status '+status);
                console.log('error '+error);
            }
        });
    });

    //공감하기,공감취소 기능 구현

    //공감 버튼 클릭이벤트
    $(document).on('click', '.likeBtn', function(){

        //likesID 변수는 AJAX 통신 이후 결과 처리를 위해 사용
        likesID = $(this).attr('id');

        //likesController에 mode명 reflection과 공감할 컨텐츠 번호 전송
        $.ajax({
            type : 'post', //post 전송방식으로 전달
            dataType : 'json', //json 언어로 전달
            url : './database/likes.php',
            data : {mode : 'save', contentsID : $(this).attr('id')}, //전달할 데이터
            async : false, //값을 전달 받은 후 실행

            success: function (data) {
                console.log('likes - return data is ' + JSON.stringify(data));
                //공감하기 결과에 따른 프로그래밍 필요함
                if(data.result == true){

                    //게시물의 공감수를 최신정보로 갱신
                    $('.' + likesID).text('공감 수 : ' + data.count);

                    //게시물에 공감했을 때 버튼 내용 변경 및 배경색 변경
                    if(data.myLike == true){
                        //공감 수 영역 배경색 변경
                        $('.' + likesID).css('background','#f9d1e4');
                        //공감 버튼 내용을 공감취소로 변경
                        $('#' + likesID).text('공감취소');
                    }
                    //게시물에 공감취소했을 때 버튼 내용 변경 및 배경색 변경
                    else{
                        //공감 수 영역 배경색 흰색으로 변경
                        $('.' + likesID).css('background','#fff');
                        //공감하기 버튼 내용을 공감하기로 변경
                        $('#' + likesID).text('공감하기');
                    }
                }

            },
            error: function (request, status, error) {
                console.log('request' + request);
                console.log('status' + status);
                console.log('error' + error);
            }
        });
    });
});
