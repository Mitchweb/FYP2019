jQuery(document).ready(function($) {
    var nextQuestionNumber = 0;
    getNextQuestionNumber();

    $(document).on("click", "#add-question-target", function() {
        if ($(this).hasClass("disabled")) {
            return;
        }
        nextQuestionNumber++;
        addQuestion(nextQuestionNumber);
    });

    $(document).on("click", "#add-answer-target", function() {
        if ($(this).hasClass("disabled")) {
            return;
        }
        var questionNumber = $(this).data("question");
        addAnswer(questionNumber);
    });

    $(document).on("click", "#save-question-target", function() {
        var questionNumber = $(this).data("question");
        var container = $("#question-" + questionNumber + "-container");
        saveQuestion(questionNumber, container);
    });

    $(document).on("click", "#update-question-target", function() {
        var questionNumber = $(this).data("question");
        var container = $("#question-" + questionNumber + "-container");
        updateQuestion(questionNumber, container);
    });

    $(document).on("click", "#delete-question-target", function() {
        if ($(this).hasClass("disabled")) {
            return;
        }
        var questionNumber = $(this).data("question");
        var container = $("#question-" + questionNumber + "-container");
        deleteQuestion(questionNumber, container);
    });

    function getNextQuestionNumber() {
        // Fetch the last question number, then enable the 'Add Question' button.
        var formData = {
            action : "quiz_get_max_question_number",
            "_quiz_nonce" : $('input[name="_quiz_nonce"]').val(),
            "_wp_http_referer" : $('input[name="_wp_http_referer"]').val()
        };
        $.ajax({
            url : ajaxurl,
            type : "POST",
            data : formData
        }).done(function(response) {
            $("#add-question-target").removeClass("disabled");
            var parsedResponse = JSON.parse(response);
            nextQuestionNumber = parsedResponse;
        });
    }

    function addQuestion(i) {
        var formData = {
            "action" : "quiz_fill_appears_after",
            "_quiz_nonce" : $('input[name="_quiz_nonce"]').val(),
            "_wp_http_referer" : $('input[name="_wp_http_referer"]').val()
        };
        $.ajax({
            url : ajaxurl,
            type : "POST",
            data : formData
        }).done(function(response) {
            var parsedResponse = JSON.parse(response);
            $("#quiz-info-note").remove();
            $("#add-answer-target-container").remove();
            $("#add-question-target").replaceWith(
                '<div id="question-' + i + '-container" class="question-container">'
                +     '<table class="form-table">'
                +         '<tbody>'
                +             '<tr>'
                +                 '<th scope="row">'
                +                     '<label for="quiz_Q' + i + '_title">Question</label>'
                +                 '</th>'
                +                 '<td>'
                +                     '<input name="quiz_Q' + i + '_title" type="text" id="quiz_Q' + i + '_title" value="" class="regular-text" />'
                +                 '</td>'
                +             '</tr>'
                +             '<tr>'
                +                 '<th scope="row">'
                +                     '<label for="quiz_Q' + i + '_appears_after">Appears after the following answers (IDs shown):</label>'
                +                 '</th>'
                +                 '<td>'
                +                     '<select name="quiz_Q' + i + '_appears_after" id="quiz_Q' + i + '_appears_after" class="appears-after" multiple></select>'
                +                 '</td>'
                +             '</tr>'
                +             '<tr>'
                +                 '<th scope="row">'
                +                     '<label for="quiz_Q' + i + '_A1">Answer 1</label>'
                +                 '</th>'
                +                 '<td>'
                +                     '<input name="quiz_Q' + i + '_A1" type="text" id="quiz_Q' + i + '_A1" value="" class="regular-text" /><span id="quiz_Q' + i + '_A1_id" class="answer-id"></span>'
                +                 '</td>'
                +             '</tr>'
                +             '<tr>'
                +                 '<th scope="row">'
                +                     '<label for="quiz_Q' + i + '_A2">Answer 2</label>'
                +                 '</th>'
                +                 '<td>'
                +                     '<input name="quiz_Q' + i + '_A2" type="text" id="quiz_Q' + i + '_A2" value="" class="regular-text" /><span id="quiz_Q' + i + '_A2_id" class="answer-id"></span>'
                +                 '</td>'
                +             '</tr>'
                +             '<tr id="add-answer-target-container"><td id="add-answer-target" class="button button-secondary" data-question="' + i + '">Add Answer</td></tr>'
                +         '</tbody>'
                +     '</table>'
                +     '<div class="edit-question-container">'
                +         '<p id="delete-question-target" class="button button-delete-question disabled" data-question="' + i + '">Delete Question</p>'
                +         '<p id="save-question-target" class="button button-update-question" data-question="' + i + '">Save Question</p>'
                +     '</div>'
                + '</div>'
                + '<p id="add-question-target" class="button button-secondary disabled">Add Question</p>'
            );
            for (var k = 0; k < parsedResponse["all"].length; k++) {
                var answerId = parsedResponse["all"][k]["answer_id"];
                $("#quiz_Q" + i + "_appears_after").append(
                    '<option value="' + answerId + '">' + answerId + '</option>'
                );
            }
        });
    }

    function addAnswer(i) {
        $("#add-answer-target-container").replaceWith(
              '<tr>'
            +     '<th scope="row">'
            +         '<label for="quiz_Q' + i + '_A3">Answer 3</label>'
            +     '</th>'
            +     '<td>'
            +         '<input name="quiz_Q' + i + '_A3" type="text" id="quiz_Q' + i + '_A3" value="" class="regular-text" /><span id="quiz_Q' + i + '_A3_id" class="answer-id"></span>'
            +     '</td>'
            + '</tr>'
        );
    }

    function saveQuestion(i, container) {
        var formData = {
            "action" : $('input[name="action"]').val(),
            "_quiz_nonce" : $('input[name="_quiz_nonce"]').val(),
            "_wp_http_referer" : $('input[name="_wp_http_referer"]').val(),
            "question_number" : i,
            "question_string" : $("#quiz_Q" + i + "_title").val(),
            "answer_list" : $("#quiz_Q" + i + "_appears_after").val(),
            "answer_string_1" : $("#quiz_Q" + i + "_A1").val(),
            "answer_string_2" : $("#quiz_Q" + i + "_A2").val(),
            "answer_string_3" : $("#quiz_Q" + i + "_A3").val()
        };
        $.ajax({
            url : ajaxurl,
            type : "POST",
            data : formData
        }).done(function(response) {
            var parsedResponse = JSON.parse(response);
            container.find("label").addClass("disabled");
            container.find("input").attr("disabled", "disabled");
            container.find("select").attr("disabled", "disabled");
            container.find("#add-answer-target-container").remove();
            container.find("#save-question-target").replaceWith(
                '<p id="update-question-target" class="button button-update-question" data-question="' + i + '">Update Question</p>'
            );
            container.find("#delete-question-target").removeClass("disabled");
            $("#add-question-target").removeClass("disabled");
            $("#quiz_Q" + i + "_A1_id").text("ID: " + parsedResponse["answer_id_1"]);
            $("#quiz_Q" + i + "_A2_id").text("ID: " + parsedResponse["answer_id_2"]);
            $("#quiz_Q" + i + "_A3_id").text("ID: " + parsedResponse["answer_id_3"]);
        });
    }

    function updateQuestion(i, container) {
        var formData = {
            "action" : "quiz_fill_appears_after",
            "_quiz_nonce" : $('input[name="_quiz_nonce"]').val(),
            "_wp_http_referer" : $('input[name="_wp_http_referer"]').val(),
            "question_number" : i
        };
        $.ajax({
            url : ajaxurl,
            type : "POST",
            data : formData
        }).done(function(response) {
            var parsedResponse = JSON.parse(response);
            container.find("label").removeClass("disabled");
            container.find("input").removeAttr("disabled");
            container.find("select").removeAttr("disabled");
            
            if ($("#quiz_Q" + i + "_A3").length == 0) {
                // If a field for Answer 3 does not exist, add the button.
                container.find("tbody").append(
                    '<tr id="add-answer-target-container"><td id="add-answer-target" class="button button-secondary" data-question="' + i + '">Add Answer</td></tr>'
                );
            }

            container.find("#update-question-target").replaceWith(
                '<p id="save-question-target" class="button button-update-question" data-question="' + i + '">Save Question</p>'
            );
            container.find("#delete-question-target").addClass("disabled");
            $("#add-question-target").addClass("disabled");

            // Reset the answer list.
            $("#quiz_Q" + i + "_appears_after").html("");
            // Populate the answer list with ALL IDs.
            for (var k = 0; k < parsedResponse["all"].length; k++) {
                var answerId = parsedResponse["all"][k]["answer_id"];
                $("#quiz_Q" + i + "_appears_after").append(
                    '<option value="' + answerId + '">' + answerId + '</option>'
                );
            }
            // Highlight selected IDs.
            var selectedIds = [];
            for (var k = 0; k < parsedResponse["selected"].length; k++) {
                var selectedId = parsedResponse["selected"][k]["answer_id"];
                selectedIds.push(selectedId);
            }
            $("#quiz_Q" + i + "_appears_after").val(selectedIds);
        });
    }

    function deleteQuestion(i, container) {
        var formData = {
            "action" : "quiz_delete_question",
            "_quiz_nonce" : $('input[name="_quiz_nonce"]').val(),
            "_wp_http_referer" : $('input[name="_wp_http_referer"]').val(),
            "question_number" : i,
            "question_string" : $("#quiz_Q" + i + "_title").val()
        };
        $.ajax({
            url : ajaxurl,
            type : "POST",
            data : formData
        }).done(function(response) {
            $("#add-question-target").removeClass("disabled");
            container.remove();
        });
    }

    $("#switch-organisation").change(function() {
        console.log($(this).val());
        // Update all values with the new organisation ID.
        var formData = {
            "action" : "attributes_switch_organisation",
            "organisation_id" : $(this).val()
        };
        $.ajax({
            url : ajaxurl,
            type : "POST",
            data : formData
        }).done(function(response) {
            var parsedResponse = JSON.parse(response);
            // Set every select box to 0.
            $("#quiz_save_attributes_table").find("select").val(0);
            // Loop through response and select the relevant attributes.
            parsedResponse.forEach(function(attributeId) {
                $("select#" + attributeId).val(1);
            });
        });
    });
});