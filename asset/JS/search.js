function confirmData() {
  var title = document.querySelector(".title");
  var form = document.querySelector(".form");
  var title_value = title.value;

  if (title_value === "") {
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
  var form = document.querySelector(".form");

  form.addEventListener("submit", function (e) {
    e.preventDefault();
  });

  setSubmitBtnClickEvent();
}

init();
