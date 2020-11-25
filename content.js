function confirmDelete() {
  var deleteForm_elem = document.querySelector(".deleteForm");

  if (confirm("정말 삭제하시겠습니까?") == true) {
    alert("삭제가 완료되었습니다.");
    deleteForm_elem.submit();
  } else {
    alert("삭제가 취소되었습니다.");
    return false;
  }
}

function setDeleteBtnClickEvent() {
  var deleteBtn_elem = document.querySelector(".deleteBtn");

  deleteBtn_elem.addEventListener("click", confirmDelete);
}

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
  var update_submitBtn_elem = document.querySelector(".submitBtn");

  update_submitBtn_elem.addEventListener("click", confirmDatas);
}

function init() {
  setSubmitBtnClickEvent();
  setDeleteBtnClickEvent();
}

init();
