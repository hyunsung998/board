function validateText() {
  var title = document.querySelector("#title");
  var form = document.querySelector("#search_form");
  var title_value = title.value;

  if (title_value === "") {
    title.focus();
  } else {
    form.submit();
  }
}

function setSubmitBtnClickEvent() {
  var submitBtn_elem = document.querySelector("#submitBtn");

  submitBtn_elem.addEventListener("click", validateText);
}

function init() {
  var form = document.querySelector("#search_form");

  form.addEventListener("submit", function (e) {
    e.preventDefault();
  });

  setSubmitBtnClickEvent();
}

init();
