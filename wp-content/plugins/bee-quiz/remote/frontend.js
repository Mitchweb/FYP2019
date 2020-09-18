class Validator {
    static validateTextInput(textInput) {
        document.getElementById(textInput.getAttribute("name") + "_group").classList.add("was-validated");
        var value = textInput.value;
        if (value.trim().length == 0) {
            // Empty input.
            textInput.setCustomValidity("Input cannot be empty.");
            return;
        }
        if (value.length > 140) {
            // Input too long.
            textInput.setCustomValidity("Input cannot exceed 140 characters.");
            return;
        }
        textInput.setCustomValidity("");
    }

    static validateTextareaInput(textareaInput) {
        document.getElementById(textareaInput.getAttribute("name") + "_group").classList.add("was-validated");
        var value = textareaInput.value;
        if (value.trim().length == 0) {
            // Empty input.
            textareaInput.setCustomValidity("Input cannot be empty.");
            return;
        }
        if (value.length > 500) {
            // Input too long.
            textareaInput.setCustomValidity("Input cannot exceed 500 characters.");
            return;
        }
        textareaInput.setCustomValidity("");
    }

    static validateFileInput(fileInput) {
        document.getElementById(fileInput.getAttribute("name") + "_group").classList.add("was-validated");
        var files = fileInput.files;
        if (files.length > 0) {
            if (files[0].size > (2 * 1024 * 1024)) {
                // File size too large.
                fileInput.setCustomValidity("File size must be less than 2 MB.");
                return;
            }
        }
        fileInput.setCustomValidity("");
    }

    static validateCheckbox(checkbox) {
        document.getElementById(checkbox.getAttribute("name") + "_group").classList.add("was-validated");
        if (!checkbox.checked) {
            checkbox.setCustomValidity("You must agree to this statement before form submission.");
            return;
        }
        checkbox.setCustomValidity("");
    }
}

jQuery(document).ready(function($) {

    if (window.location.href.includes("/quiz")) {
        // Set up an empty object to track which questions and answers have been
        // shown or selected, respectively.
        var quizTracker = {
            "questions" : [],
            "answers" : []
        };

        var questionCount = 1;
        startQuiz(questionCount);

        $(document).on("click", ".quiz__answer", function() {
            // Update the quiz tracker with the selected answer.
            quizTracker[ "answers" ].push($(this).find("a").data("id"));

            if (questionCount >= 10) {
                endQuiz();
                return;
            }

            questionCount++;
            transitionQuizContainer(questionCount);
        });
    }

    if (window.location.href.includes("/register")) {
        // Add screen transitions on click.
        $(document).on("click", "#register__next-screen__1", function() {
            continueScreen(1);
        });
        $(document).on("click", "#register__next-screen__2", function() {
            continueScreen(2);
        });
        $(document).on("click", "#register__next-screen__3", function() {
            continueScreen(3);
        });

        // Validate organisation name.
        var inputOrganisationName = $("#register_organisation_name");
        inputOrganisationName.change(function() {
            Validator.validateTextInput($(this)[0]);
            tryEnableRegisterNextScreen(1);
        });

        // Validate organisation description.
        var inputOrganisationDescription = $("#register_organisation_description");
        inputOrganisationDescription.change(function() {
            Validator.validateTextareaInput($(this)[0]);
            tryEnableRegisterNextScreen(1);
        });
        
        // Validate organisation contact picture.
        var inputOrganisationContactPicture = $("#register_organisation_contact_picture");
        inputOrganisationContactPicture.change(function() {
            Validator.validateFileInput($(this)[0]);
            tryEnableRegisterNextScreen(2);
            // Also, update the label with the name of the uploaded file.
            var value = $(this)[0].value;
            $("#register_organisation_contact_picture_label").text(value.substr(value.lastIndexOf("\\") + 1));
        });

        // Validate organisation contact name.
        var inputOrganisationContactName = $("#register_organisation_contact_name");
        inputOrganisationContactName.change(function() {
            Validator.validateTextInput($(this)[0]);
            tryEnableRegisterNextScreen(2);
        });

        // Validate organisation contact email address.
        var inputOrganisationContactEmailAddress = $("#register_organisation_contact_email_address");
        inputOrganisationContactEmailAddress.change(function() {
            Validator.validateTextInput($(this)[0]);
            tryEnableRegisterNextScreen(2);
        });

        // Validate organisation address fields.
        var inputOrganisationAddressLine1 = $("#register_organisation_address_line_1");
        inputOrganisationAddressLine1.change(function() {
            Validator.validateTextInput($(this)[0]);
            tryEnableRegisterNextScreen(3);
        });
        var inputOrganisationAddressTownCity = $("#register_organisation_address_town_city");
        inputOrganisationAddressTownCity.change(function() {
            Validator.validateTextInput($(this)[0]);
            tryEnableRegisterNextScreen(3);
        });
        var inputOrganisationAddressPostcode = $("#register_organisation_address_postcode");
        inputOrganisationAddressPostcode.change(function() {
            Validator.validateTextInput($(this)[0]);
            tryEnableRegisterNextScreen(3);
        });

        // Validate address on dropdown change.
        $(document).on("change", "#register_organisation_address_lookup_dropdown", function() {
            inputOrganisationAddressLine1.change();
            inputOrganisationAddressTownCity.change();
            inputOrganisationAddressPostcode.change();
        });

        // Validate permission checkbox.
        var inputOrganisationPermission = $("#register_organisation_permission");
        inputOrganisationPermission.change(function() {
            Validator.validateCheckbox($(this)[0]);
        });

        // Validate terms checkbox.
        var inputOrganisationTerms = $("#register_organisation_terms");
        inputOrganisationTerms.change(function() {
            Validator.validateCheckbox($(this)[0]);
        });

        // START: Ideal Postcodes API
        $("#register_organisation_address_lookup_group").setupPostcodeLookup({
            api_key : "ak_jt4k0klzUSaV8mS48EnpuRo07CaNh",
            output_fields : {
                line_1 : "#register_organisation_address_line_1",
                line_2 : "#register_organisation_address_line_2",
                line_3 : "#register_organisation_address_line_3",
                post_town : "#register_organisation_address_town_city",
                postcode : "#register_organisation_address_postcode"
            },
            input : "#register_organisation_address_lookup",
            button : "#register_organisation_address_lookup_button",
            dropdown : "#register_organisation_address_lookup_dropdown",
            dropdown_id : "register_organisation_address_lookup_dropdown",
            dropdown_class : "form-control",
            dropdown_select_message : "Select address",
            error_message_invalid_postcode : "Invalid postcode.",
            error_message_not_found : "Postcode not found. Please manually enter the address.",
            error_message_address_not_found : "Address not found. Please manually enter the address.",
            error_message_default : "Error. Please manually enter the address.",
            onSearchCompleted : function(data) {
                if (data.code === 2000) {
                    $("#register_dummy_dropdown").addClass("is-removed");
                }
            }
        });
        // END: Ideal Postcodes API

        // Validate form upon submission.
        var form = document.getElementById("register_registration_form");
        form.addEventListener("submit", function(event) {
            // Stop default submit.
            event.preventDefault();
            event.stopPropagation();
            if (form.checkValidity() === false) {
                // Invalid form. Run checks against all fields.
                $("#register_organisation_name_group").addClass("was-validated");
                $("#register_organisation_description_group").addClass("was-validated");
                $("#register_organisation_contact_name_group").addClass("was-validated");
                $("#register_organisation_contact_email_address_group").addClass("was-validated");
                $("#register_organisation_address_line_1_group").addClass("was-validated");
                $("#register_organisation_address_town_city_group").addClass("was-validated");
                $("#register_organisation_address_postcode_group").addClass("was-validated");
                $("#register_organisation_permission_group").addClass("was-validated");
                $("#register_organisation_terms_group").addClass("was-validated");
            } else {
                // Valid form. Post it via AJAX.
                var formData = new FormData($("#register_registration_form")[0]);
                $.ajax({
                    url : quiz_ajax_handler.ajax_url,
                    type : "POST",
                    data : formData,
                    cache : false,
                    contentType : false,
                    processData : false
                }).done(function(response) {
                    var parsedResponse = JSON.parse(response);
                    if (parsedResponse == 0) {
                        // Set an error message.
                        $("#register__results-screen__message").text("There was error in the form. Please try again.");
                        // TODO: More customised errors.
                    }
                    completeRegistration();
                });
            }
        }, false);
    }

    // MARK:- Helper Methods for Quiz

    function startQuiz(questionCount) {
        var data = {
            "action" : "get_question_contents",
            "quiz_tracker" : quizTracker
        };
        $.post(quiz_ajax_handler.ajax_url, data, function(response) {
            var parsedResponse = JSON.parse(response);

            updateQuizContainer(questionCount, parsedResponse);

            $("#quiz__splash-screen").addClass("is-hidden");
            setTimeout(function() {
                $("#quiz__loader").removeClass("is-animated");
            }, 1000);
        });
    }

    function endQuiz() {
        // Fetch the best match for these answers.
        var data = {
            "action" : "get_quiz_results",
            "answers" : quizTracker[ "answers" ]
        }
        $.post(quiz_ajax_handler.ajax_url, data, function(response) {
            var parsedResponse = JSON.parse(response);

            if (parsedResponse == null) {
                $("#quiz__results-screen__match").text("Sorry, you haven't matched with any organisations...");
                $("#quiz__results-screen__picture").attr("style", "display: none;");
                $("#quiz__results-screen__name").attr("style", "display: none;");
                $("#quiz__results-screen__message").attr("style", "display: none;");
                $("#quiz__results-screen__email").attr("style", "display: none;");
            } else {
                // Update the results screen.
                $("#quiz__results-screen__picture").attr("style", "background-image: url('" + parsedResponse.organisation_contact_picture + "');");
                $("#quiz__results-screen__name").text(parsedResponse.organisation_contact_name + " at " + parsedResponse.organisation_name);
                $("#quiz__results-screen__email").attr("href", "mailto:" + parsedResponse.organisation_contact_email_address);
                $("#quiz__results-screen__email").text(parsedResponse.organisation_contact_email_address);
            }

            setIsFadingOut(); // Transitions for 300 ms.
            setTimeout(function() {
                setIsHidden();
                $("#quiz__results-screen").addClass("is-visible");
            }, 300);
        });
    }

    function updateQuizContainer(questionCount, parsedResponse) {
        // Update the quiz progress.
        $("#quiz__progress-bar__" + (questionCount - 1)).removeClass("current").addClass("complete");
        $("#quiz__progress-bar__" + questionCount).removeClass("incomplete").addClass("current");
        $("#quiz__progress-counter").text(questionCount);
        // Update the question contents.
        $("#quiz__question").text(parsedResponse.question_title);
        $("#quiz__answers__1").text(parsedResponse.answer_1);
        $("#quiz__answers__1").data("id", parsedResponse.answer_1_id);
        $("#quiz__answers__2").text(parsedResponse.answer_2);
        $("#quiz__answers__2").data("id", parsedResponse.answer_2_id);
        if (parsedResponse.answer_3 != undefined) {
            $("#quiz__answers__3").parent().removeClass("is-removed");
            $("#quiz__answers__3").text(parsedResponse.answer_3);
            $("#quiz__answers__3").data("id", parsedResponse.answer_3_id);
        } else {
            $("#quiz__answers__3").parent().addClass("is-removed");
        }
        // Update the quiz tracker.
        quizTracker = parsedResponse.quiz_tracker;
    }

    function transitionQuizContainer(questionCount) {
        // Initialise an empty variable to store the AJAX response.
        var parsedResponse = {};

        // Send our AJAX request ASAP. We don't wait for AJAX to respond before
        // executing the code below.
        var data = {
            "action" : "get_question_contents",
            "quiz_tracker" : quizTracker
        };
        $.post(quiz_ajax_handler.ajax_url, data, function(response) {
            parsedResponse = JSON.parse(response);
        });

        setIsFadingOut(); // Transitions for 300 ms.

        setTimeout(function() {
            // After the fade-out transition finishes, make elements invisible.
            // We don't make elements visible again until we receive the AJAX
            // response.
            setIsHidden();

            // Wait at least 100 ms to allow elements to become invisible.
            setTimeout(function() {
                while ($.isEmptyObject(parsedResponse)) {
                    // Wait for the AJAX response.
                }
    
                updateQuizContainer(questionCount, parsedResponse, quizTracker);
                setIsFadingIn(); // Transitions for 600 ms.
    
                setTimeout(function() {
                    // After the fade-in transition finishes, make elements visible.
                    setIsVisible();
                }, 600);
            }, 100);
        }, 300);
    }

    function setIsHidden() {
        $("#quiz__progress-text").removeClass("is-fading-out").addClass("is-hidden");
        $("#quiz__question-container").removeClass("is-fading-out").addClass("is-hidden");
        $("#quiz__answers").removeClass("is-fading-out").addClass("is-hidden");
    }

    function setIsFadingIn() {
        $("#quiz__progress-text").removeClass("is-hidden").addClass("is-fading-in");
        $("#quiz__question-container").removeClass("is-hidden").addClass("is-fading-in");
        $("#quiz__answers").removeClass("is-hidden").addClass("is-fading-in");
    }

    function setIsVisible() {
        $("#quiz__progress-text").removeClass("is-fading-in").addClass("is-visible");
        $("#quiz__question-container").removeClass("is-fading-in").addClass("is-visible");
        $("#quiz__answers").removeClass("is-fading-in").addClass("is-visible");
    }

    function setIsFadingOut() {
        $("#quiz__progress-text").removeClass("is-visible").addClass("is-fading-out");
        $("#quiz__question-container").removeClass("is-visible").addClass("is-fading-out");
        $("#quiz__answers").removeClass("is-visible").addClass("is-fading-out");
    }

    // MARK:- Helper Methods for Register

    function updateRegisterContainer(buttonNumber) {
        // Get the description of the next screen.
        var screenDescription;
        switch (buttonNumber) {
        case 1:
            screenDescription = "Organisation Details";
            break;
        case 2:
            screenDescription = "Contact Details";
            break;
        case 3:
            screenDescription = "Organisation Address";
            break;
        case 4:
            screenDescription = "Submit Registration";
            break;
        default:
            console.error("Unexpected buttonNumber!");
            break;
        }

        // Update the register progress.
        $("#register__progress-bar__" + (buttonNumber - 1)).removeClass("current").addClass("complete");
        $("#register__progress-bar__" + buttonNumber).removeClass("incomplete").addClass("current");
        $("#register__progress-counter").text(buttonNumber);
        $("#register__progress-description").text(screenDescription);
    }

    function tryEnableRegisterNextScreen(buttonNumber) {
        var button = $("#register__next-screen__" + buttonNumber);
        switch (buttonNumber) {
        case 1:
            if ($("#register_organisation_name").is(":valid") && $("#register_organisation_description").is(":valid")) {
                enableButton(button);
            } else {
                disableButton(button);
            }
            break;
        case 2:
            if ($("#register_organisation_contact_picture").is(":valid") && $("#register_organisation_contact_name").is(":valid") && $("#register_organisation_contact_email_address").is(":valid")) {
                enableButton(button);
            } else {
                disableButton(button);
            }
            break;
        case 3:
            if ($("#register_organisation_address_line_1").is(":valid") && $("#register_organisation_address_town_city").is(":valid") && $("#register_organisation_address_postcode").is(":valid")) {
                enableButton(button);
            } else {
                disableButton(button);
            }
            break;
        default:
            console.error("Unexpected buttonNumber!");
            break;
        }
    }

    function enableButton(button) {
        button.removeClass("disabled");
        button.removeAttr("aria-disabled");
        button.removeAttr("tabindex");
    }

    function disableButton(button) {
        button.addClass("disabled");
        button.attr("aria-disabld", "true");
        button.attr("tabindex", -1);
    }

    function continueScreen(i) {
        $("#register__screen__" + (i + 1)).removeClass("is-removed");
        $("#register__progress-text").removeClass("is-visible").addClass("is-fading-out");
        $("#register__screen__" + i).removeClass("is-visible").addClass("is-fading-out");
        setTimeout(function() {
            $("#register__progress-text").removeClass("is-fading-out").addClass("is-hidden");
            $("#register__screen__" + i).removeClass("is-fading-out").addClass("is-hidden is-removed");
            setTimeout(function() {
                updateRegisterContainer(i + 1);
                $("#register__progress-text").removeClass("is-hidden").addClass("is-fading-in");
                $("#register__screen__" + (i + 1)).removeClass("is-hidden").addClass("is-fading-in");
                setTimeout(function() {
                    $("#register__progress-text").removeClass("is-fading-in").addClass("is-visible");
                    $("#register__screen__" + (i + 1)).removeClass("is-fading-in").addClass("is-visible");
                }, 600);
            }, 300);
        }, 300);
    }

    function completeRegistration() {
        $("#register__progress-text").removeClass("is-visible").addClass("is-fading-out");
        $("#register__screen__4").removeClass("is-visible").addClass("is-fading-out");
        setTimeout(function() {
            $("#register__progress-text").removeClass("is-fading-out").addClass("is-hidden is-removed");
            $("#register__screen__4").removeClass("is-fading-out").addClass("is-hidden is-removed");
            $("#register__results-screen").addClass("is-visible");
        }, 300);
    }
});