function confirmDelete() {
  var deleteForm_elem = document.querySelector(".deleteForm");

  if (confirm("정말 삭제하시겠습니까?") == true) {
    deleteForm_elem.submit();
  } else {
    return false;
  }
}

function setDeleteBtnClickEvent() {
  var deleteBtn_elem = document.querySelector(".deleteBtn");

  deleteBtn_elem.addEventListener("click", confirmDelete);
}

function init() {
  setDeleteBtnClickEvent();
}

init();
