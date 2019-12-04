window.addEventListener("DOMContentLoaded", function(event) {
  $("#select-quiz").on("change", function(event) {
    loadTable($("#select-quiz").val());
  });
  loadTable($("#select-quiz").val());
});

function loadTable(quizId) {
  $.get("php/admin/questiontable.php?quiz_id=" + quizId, function(data) {
    $("#question-table").html(data);
  });
}

function addQuestion() {
  $.post(
    "php/admin/addquestion.php",
    {
      quiz_id: $("#select-quiz").val(),
      question: $(`#new_question`).val(),
      answer_a: $(`#new_answer_a`).val(),
      answer_b: $(`#new_answer_b`).val(),
      answer_c: $(`#new_answer_c`).val(),
      answer_d: $(`#new_answer_d`).val(),
      right_answer: $(`#new_right_answer`).val()
    },
    function(data) {
      console.log("addquestion", data);
      loadTable($("#select-quiz").val());
    }
  );
}

function editQuestion(event) {
  let questionId = event.target.id.split("_")[0];
  $.post(
    "php/admin/modifyquestion.php",
    {
      question_id: questionId,
      question: $(`#${questionId}_question`).val(),
      answer_a: $(`#${questionId}_answer_a`).val(),
      answer_b: $(`#${questionId}_answer_b`).val(),
      answer_c: $(`#${questionId}_answer_c`).val(),
      answer_d: $(`#${questionId}_answer_d`).val(),
      right_answer: $(`#${questionId}_right_answer`).val()
    },
    function(data) {
      console.log("modifyquestion", questionId, data);
      loadTable($("#select-quiz").val());
    }
  );
}

function deleteQuestion(event) {
  let questionId = event.target.id.split("_")[0];
  $.post(
    "php/admin/deletequestion.php",
    {
      question_id: questionId
    },
    function(data) {
      console.log("deletequestion", questionId, data);
      loadTable($("#select-quiz").val());
    }
  );
}

function editValChanged(event) {
  event.target.style.backgroundColor =
    event.target.value == event.target.getAttribute("oldval")
      ? "white"
      : "yellow";

  let questionId = event.target.id.split("_")[0];
  let rowChanged = false;
  for (let col of [
    "question",
    "answer_a",
    "answer_b",
    "answer_c",
    "answer_d",
    "right_answer"
  ]) {
    let el = $(`#${questionId}_${col}`);
    if (el.val() != el.attr("oldval")) rowChanged = true;
  }
  if (rowChanged) $(`#${questionId}_submit`).removeAttr("disabled");
  else $(`#${questionId}_submit`).attr("disabled", true);
}

function addValChanged(event) {
  let allSet = true;
  for (let col of [
    "question",
    "answer_a",
    "answer_b",
    "answer_c",
    "answer_d"
  ]) {
    if ($(`#new_${col}`).val().length == 0) allSet = false;
  }
  if (allSet) $("#add_question").removeAttr("disabled");
  else $("#add_question").attr("disabled", true);
}
