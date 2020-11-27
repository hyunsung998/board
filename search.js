function confirmData() {
  var title = document.querySelector(".title");
  var form = document.querySelector(".form");
  var title_value = title.value;

  if (title_value === "" || title_value.length <= 2) {
    alert("검색할 제목을 3글자 이상 입력해주세요.");
    title.focus();
  } else {
    form.submit();
  }
}

function setSubmitBtnClickEvent() {
  var submitBtn_elem = document.querySelector(".submitBtn");

  submitBtn_elem.addEventListener("click", confirmData);
}

function init() {
  setSubmitBtnClickEvent();
}

init();
