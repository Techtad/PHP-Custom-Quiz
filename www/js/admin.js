window.addEventListener("DOMContentLoaded", function(event) {
  loadQuizTable();
  loadQuestionTable();
  loadUserTable();
});

function loadQuizTable() {
  let quizId = $("#quiz-table").attr("currentQuiz");
  $.get("php/admin/quiztable.php?selected=" + quizId, function(data) {
    $("#quiz-table").html(data);
  });
}

function loadQuestionTable() {
  let quizId = $("#quiz-table").attr("currentQuiz");
  if (quizId) {
    $.get("php/admin/questiontable.php?quiz_id=" + quizId, function(data) {
      $("#question-table").html(data);
    });
  }
}

function loadUserTable() {
  $.get("php/admin/usertable.php", function(data) {
    $("#user-table").html(data);
  });
}

// Pytania

function addQuestion() {
  $.post(
    "php/admin/addquestion.php",
    {
      quiz_id: $("#quiz-table").attr("currentQuiz"),
      question: $(`#new_question`).val(),
      answer_a: $(`#new_answer_a`).val(),
      answer_b: $(`#new_answer_b`).val(),
      answer_c: $(`#new_answer_c`).val(),
      answer_d: $(`#new_answer_d`).val(),
      right_answer: $(`#new_right_answer`).val()
    },
    function(data) {
      console.log("addquestion", data);
      loadQuestionTable();
    }
  );
}

function editQuestion(questionId) {
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
      loadQuestionTable();
    }
  );
}

function deleteQuestion(questionId) {
  if (!confirm("Czy na pewno chcesz usunąć to pytanie?")) return;

  $.post(
    "php/admin/deletequestion.php",
    {
      question_id: questionId
    },
    function(data) {
      console.log("deletequestion", questionId, data);
      loadQuestionTable();
    }
  );
}

function editValChanged(event) {
  event.target.style.backgroundColor =
    event.target.value == event.target.getAttribute("oldval") ? null : "yellow";

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
  if (rowChanged) {
    $(`#${questionId}_submit`).removeAttr("disabled");
    $(`#${questionId}_cancel`).removeAttr("disabled");
    $("#question-table input, select")
      .filter((i, e) => {
        return e.id.split("_")[0] != questionId;
      })
      .attr("disabled", true);
  } else {
    $(`#${questionId}_submit`).attr("disabled", true);
    $(`#${questionId}_cancel`).attr("disabled", true);
    $("#question-table input, select")
      .filter((i, e) => {
        return !["submit", "cancel"].includes(e.id.split("_")[1]);
      })
      .removeAttr("disabled");
  }
}

function addValChanged(event) {
  let allSet = true;
  let anySet = false;
  for (let col of [
    "question",
    "answer_a",
    "answer_b",
    "answer_c",
    "answer_d",
    "right_answer"
  ]) {
    if ($(`#new_${col}`).val().length == 0) allSet = false;
    else if (col != "right_answer") anySet = true;
  }

  if (allSet && $("#quiz-table").attr("currentQuiz")) {
    $("#add_question").removeAttr("disabled");
  } else {
    $("#add_question").attr("disabled", true);
  }

  if (anySet && $("#quiz-table").attr("currentQuiz")) {
    $("#add_question_cancel").removeAttr("disabled");
    $("#question-table input, select")
      .filter((i, e) => {
        return !["add", "new"].includes(e.id.split("_")[0]);
      })
      .attr("disabled", true);
  } else {
    $("#add_question_cancel").attr("disabled", true);
    $("#question-table input, select")
      .filter((i, e) => {
        return !["submit", "cancel"].includes(e.id.split("_")[1]);
      })
      .removeAttr("disabled");
  }
}

// Quizy

function onQuizEditValChanged(event) {
  event.target.style.backgroundColor =
    event.target.value == event.target.getAttribute("oldval")
      ? "white"
      : "yellow";

  let quizId = event.target.id.split("_")[1];
  let name = $(`#quiz_${quizId}_name`);
  let desc = $(`#quiz_${quizId}_desc`);
  let rowChanged =
    name.val() != name.attr("oldval") || desc.val() != desc.attr("oldval");
  if (rowChanged) {
    $(`#quiz_${quizId}_edit`).removeAttr("disabled");
    $(`#quiz_${quizId}_cancel`).removeAttr("disabled");
    $("#quiz-table input")
      .filter((i, e) => {
        return (
          e.id.split("_")[1] != event.target.id.split("_")[1] &&
          !["delete", "select"].includes(e.id.split("_")[2])
        );
      })
      .attr("disabled", true);
  } else {
    $(`#quiz_${quizId}_edit`).attr("disabled", true);
    $(`#quiz_${quizId}_cancel`).attr("disabled", true);
    $("#quiz-table input")
      .filter((i, e) => {
        return !["edit", "cancel", "delete", "select"].includes(
          e.id.split("_")[2]
        );
      })
      .removeAttr("disabled");
  }
}

function quizAddChanged(event) {
  let name = $(`#new_quiz_name`);
  let desc = $(`#new_quiz_desc`);
  let allSet = name.val().length > 0 && desc.val().length > 0;
  let anySet = name.val().length > 0 || desc.val().length > 0;
  if (allSet) {
    $("#add_quiz").removeAttr("disabled");
  } else {
    $("#add_quiz").attr("disabled", true);
  }
  if (anySet) {
    $("#cancel_add_quiz").removeAttr("disabled");
    $("#quiz-table input")
      .filter((i, e) => {
        return (
          !["new", "add", "cancel"].includes(e.id.split("_")[0]) &&
          !["delete", "select"].includes(e.id.split("_")[2])
        );
      })
      .attr("disabled", true);
  } else {
    $("#cancel_add_quiz").attr("disabled", true);
    $("#quiz-table input")
      .filter((i, e) => {
        return !["edit", "cancel", "delete", "select"].includes(
          e.id.split("_")[2]
        );
      })
      .removeAttr("disabled");
  }
}

function editQuiz(id) {
  $.post(
    "php/admin/modifyquiz.php",
    {
      quiz_id: id,
      name: $(`#quiz_${id}_name`).val(),
      description: $(`#quiz_${id}_desc`).val()
    },
    function(data) {
      console.log("modifyquiz", id, data);
      loadQuizTable();
    }
  );
}

function deleteQuiz(id) {
  if (!confirm("Czy na pewno chcesz usunąć ten test?")) return;

  $.post(
    "php/admin/deletequiz.php",
    {
      quiz_id: id
    },
    function(data) {
      console.log("deletequiz", id, data);
      loadQuizTable();
      if ($("#quiz-table").attr("currentQuiz") == id)
        $("#quiz-table").attr("currentQuiz", null);
    }
  );
}

function selectQuiz(id) {
  $("#quiz-table").attr("currentQuiz", id);
  loadQuizTable();
  loadQuestionTable();
}

function addQuiz() {
  $.post(
    "php/admin/addquiz.php",
    {
      name: $("#new_quiz_name").val(),
      description: $(`#new_quiz_desc`).val()
    },
    function(data) {
      console.log("addquiz", data);
      loadQuizTable();
    }
  );
}

// Użytkownicy

function newPasswordChanged(event) {
  let username = event.target.id.split("_")[0];
  if (event.target.value.length >= 6)
    $(`#${username}_change_password`).removeAttr("disabled");
  else $(`#${username}_change_password`).attr("disabled", true);
}

function changePassword(event) {
  let username = event.target.id.split("_")[0];
  $.post(
    "php/admin/changepassword.php",
    {
      username: username,
      new_password: $(`#${username}_newpassword`).val()
    },
    function(data) {
      console.log("changepassword", username, data);
      loadUserTable();
    }
  );
}

function toggleAdmin(event) {
  let username = event.target.id.split("_")[0];
  $.post(
    "php/admin/toggleadmin.php",
    {
      username: username
    },
    function(data) {
      console.log("toggleadmin", username, data);
      loadUserTable();
    }
  );
}

function deleteUser(event) {
  let username = event.target.id.split("_")[0];
  if (!confirm("Czy na pewno chcesz usunąć użytkownika " + username + "?"))
    return;

  $.post(
    "php/admin/deleteuser.php",
    {
      username: username
    },
    function(data) {
      console.log("deleteuser", username, data);
      loadUserTable();
    }
  );
}

// Zakładki panelu

function switchTab(toUsers) {
  if (toUsers) {
    $("#usertab").css("display", "block");
    $("#datatab").css("display", "none");
    $("#data-tab-btn").removeAttr("selected");
    $("#user-tab-btn").attr("selected", true);
  } else {
    $("#datatab").css("display", "block");
    $("#usertab").css("display", "none");
    $("#user-tab-btn").removeAttr("selected");
    $("#data-tab-btn").attr("selected", true);
  }
}
