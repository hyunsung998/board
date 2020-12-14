function validate() {
  var id_elem = document.querySelector("#id");
  var pw_elem = document.querySelector("#pw");
  var login_form_elem = document.querySelector("#login_form");

  if (id_elem.value === "") {
    alert("아이디를 입력해주세요.");
    id_elem.focus();
  } else if (pw_elem.value === "") {
    alert("비밀번호를 입력해주세요.");
    pwd_elem.focus();
  } else {
    login_form_elem.submit();
  }
}

function setLoginBtnClickEvent() {
  var login_btn_elem = document.querySelector("#login_btn");

  login_btn_elem.addEventListener("click", validate);
}

function init() {
  $("#id").focus();
  setLoginBtnClickEvent();
}
init();
