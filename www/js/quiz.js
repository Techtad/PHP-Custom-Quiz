function selectAnswer(event, id) {
  console.log(event.target.getAttribute("letter"));
  $(`#a_${id}`).val(event.target.getAttribute("letter"));

  $(`#q_${id} .answer`).removeAttr("selected");
  event.target.setAttribute("selected", true);
}
