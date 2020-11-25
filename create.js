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
    alert("게시글이 작성되었습니다.");
    form_elem.submit();
  }
}

function setSubmitBtnClickEvent() {
  var create_submitBtn_elem = document.querySelector(".submitBtn");

  create_submitBtn_elem.addEventListener("click", confirmDatas);
}

function init() {
  setSubmitBtnClickEvent();
}

init();
