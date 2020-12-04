function confirmCancel() {
  if (confirm("게시글 수정을 취소하시겠습니까?") == true) {
    var url_parameter = document.location.search;
    location.href = "content.php" + url_parameter;
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
  setCancelBtnClickEvent();
}

init();
