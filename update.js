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

function setSubmitBtnClickEvent() {
  var submit_btn_elem = document.querySelector(".submitBtn");

  submit_btn_elem.addEventListener("click", confirmDatas);
}

function init() {
  var form = document.querySelector(".form");

  form.addEventListener("submit", function (e) {
    e.preventDefault();
  });

  setSubmitBtnClickEvent();
}

init();
