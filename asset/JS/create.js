function confirmCancel() {
  if (confirm("게시글 작성을 취소하시겠습니까?") == true) {
    window.history.back();
  } else {
    return false;
  }
}

function setCancelBtnClickEvent() {
  var cancel_btn_elem = document.querySelector(".cancelBtn");

  cancel_btn_elem.addEventListener("click", confirmCancel);
}

function confirmDatas() {
  var title = document.querySelector(".title");
  var description = document.querySelector(".description");
  var form = document.querySelector(".form");
  var title_value = title.value;
  var description_value = description.value;

  if (title_value === "" || title_value.length <= 2) {
    alert("제목을 3글자 이상 입력해주세요.");
    title.focus();
  } else if (description_value === "" || description_value.length <= 9) {
    alert("내용을 10글자 이상 입력해주세요.");
    description.focus();
  } else {
    form.submit();
  }
}

function setCreateSubmitBtnClickEvent() {
  var create_submitBtn_elem = document.querySelector(".submitBtn");

  create_submitBtn_elem.addEventListener("click", confirmDatas);
}

function init() {
  var form = document.querySelector(".form");

  form.addEventListener("submit", function (e) {
    e.preventDefault();
  });

  var title = document.querySelector(".title");

  title.focus();

  setCreateSubmitBtnClickEvent();
  setCancelBtnClickEvent();
}

init();
