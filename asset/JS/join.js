function validateAllInfo() {
  var regex_id = /^[a-z]+[a-z0-9]{4,19}$/;
  var regex_pw = /^.*(?=^.{8,15}$)(?=.*\d)(?=.*[a-zA-Z])(?=.*[!@#$%^&+=]).*$/;
  var id_elem = document.querySelector("#id");
  var id_value = id_elem.value;
  var pw_elem = document.querySelector("#pw");
  var pw_value = pw_elem.value;
  var re_pw_elem = document.querySelector("#re_pw");
  var re_pw_value = re_pw_elem.value;
  var join_form_elem = document.querySelector("#join_form");

  if (!regex_id.test(id_value)) {
    id_elem.focus();
  } else if (callData(id_value) === id_value) {
    id_elem.focus();
  } else {
    if (!regex_pw.test(pw_value)) {
      pw_elem.focus();
    } else {
      if (!regex_pw.test(re_pw_value)) {
        re_pw_elem.focus();
      } else {
        join_form_elem.submit();
      }
    }
  }
}

function setJoinBtnClickEvent() {
  var join_btn_elem = document.querySelector("#join_btn");

  join_btn_elem.addEventListener("click", validateAllInfo);
}

function validateId() {
  var regex = /^[a-z]+[a-z0-9]{4,19}$/;
  var id_elem = document.querySelector("#id");
  var id_value = id_elem.value;
  var id_able_elem = document.querySelector("#id_able");
  var id_unable_elem = document.querySelector("#id_unable");

  if (validityCheck(id_value, regex)) {
    if (callData(id_value) === id_value) {
      id_able_elem.style.display = "none";
      id_unable_elem.style.display = "block";
      id_unable_elem.innerHTML = "이미 사용중인 아이디입니다.";
    } else {
      id_able_elem.style.display = "block";
      id_unable_elem.style.display = "none";
      id_able_elem.innerHTML = "사용 가능한 아이디입니다.";
    }
  } else {
    id_able_elem.style.display = "none";
    id_unable_elem.style.display = "block";
    id_unable_elem.innerHTML = "사용할 수 없는 아이디입니다.";
  }

  if (id_value === "") {
    id_able_elem.style.display = "none";
    id_unable_elem.style.display = "none";
  }
}

function validateRePw() {
  // *질문* 비밀번호 재확인 특수문자사용이 일치하지않음
  var regex = /^.*(?=^.{8,15}$)(?=.*\d)(?=.*[a-zA-Z])(?=.*[!@#$%^&+=]).*$/;
  var pw_elem = document.querySelector("#pw");
  var pw_value = pw_elem.value;
  var re_pw_elem = document.querySelector("#re_pw");
  var re_pw_value = re_pw_elem.value;
  var pw_able_elem = document.querySelector("#pw_able");
  var pw_unable_elem = document.querySelector("#pw_unable");

  if (validityCheck(re_pw_value, regex)) {
    if (re_pw_value === pw_value) {
      pw_able_elem.style.display = "block";
      pw_unable_elem.style.display = "none";
      pw_able_elem.innerHTML = "비밀번호가 일치합니다.";
    }
  } else {
    pw_unable_elem.style.display = "block";
    pw_able_elem.style.display = "none";
    pw_unable_elem.innerHTML = "비밀번호가 일치하지 않습니다.";
  }

  if (re_pw_value === "") {
    pw_able_elem.style.display = "none";
    pw_unable_elem.style.display = "none";
  }
}

function validatePw() {
  // 8~15자 문자,숫자,특수문자 조합의 정규표현식
  var regex = /^.*(?=^.{8,15}$)(?=.*\d)(?=.*[a-zA-Z])(?=.*[!@#$%^&+=]).*$/;
  var pw_elem = document.querySelector("#pw");
  var pw_value = pw_elem.value;
  var pw_able_elem = document.querySelector("#pw_able");
  var pw_unable_elem = document.querySelector("#pw_unable");

  if (validityCheck(pw_value, regex)) {
    pw_unable_elem.style.display = "none";
    pw_able_elem.style.display = "block";
    pw_able_elem.innerHTML = "사용 가능한 비밀번호입니다.";
  } else {
    pw_unable_elem.style.display = "block";
    pw_able_elem.style.display = "none";
    pw_unable_elem.innerHTML = "사용 불가능한 비밀번호입니다.";
  }

  //빈 문자열일 경우
  if (pw_value === "") {
    pw_able_elem.style.display = "none";
    pw_unable_elem.style.display = "none";
  }
}

function callData(id) {
  // ID 중복검사를 위한 비동기통신
  var result;

  $.ajax({
    url: "validate_user.php",
    type: "post",
    async: false, // 비동기방식은 return 값을 가져울 수 없어서 동기방식으로 변경 true: 비동기 , false: 동기
    data: { id: id },
    success: function (data) {
      result = data; // ajax 함수 안에서 변수선언 안됨.
    },
  });

  return result;
}

function validityCheck(data, regex) {
  // 정규표현식에 일치하는 경우, 참or거짓 반환
  if (regex.test(data)) {
    var result = regex.test(data);
  }
  return result;
}

function init() {
  $("#id").focus();
  $("#id").keyup(validateId);
  $("#pw").keyup(validatePw);
  $("#re_pw").keyup(validateRePw);
  setJoinBtnClickEvent();
}

init();
