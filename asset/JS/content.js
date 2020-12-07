function confirmDatas() {
  var title_elem = document.querySelector(".title");
  var description_elem = document.querySelector(".description");
  var form_elem = document.querySelector(".form");

  if (title_elem.value === "") {
    alert("제목을 입력해주세요.");
    title_elem.focus();
  } else if (description_elem.value === "") {
    alert("내용을 입력해주세요.");
    description_elem.focus();
  } else {
    alert("수정이 완료되었습니다.");
    form_elem.submit();
  }
}

function setSubmitBtnClickEvent() {
  var submit_btn_elem = document.querySelector(".submitBtn");

  submit_btn_elem.addEventListener("click", confirmDatas);
}

function init() {
  setSubmitBtnClickEvent();
}

init();
