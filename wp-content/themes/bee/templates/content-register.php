<div id="register-container">
    <div id="register__splash-screen" class="common__splash-screen is-hidden">
        <div class="container">
            <h1>Register your Organisation</h1>
            <p>Thanks for taking interest in StudentBee. Click below to register your organisation with StudentBee.</p>
            <a href="javascript:void(0);" class="btn btn-default">Start</a>
        </div>
    </div>
    <div class="container">
        <ol id="register__progress-bar" class="common__progress-bar">
            <li id="register__progress-bar__1" class="incomplete"></li>
            <li id="register__progress-bar__2" class="incomplete"></li>
            <li id="register__progress-bar__3" class="incomplete"></li>
            <li id="register__progress-bar__4" class="incomplete"></li>
            <li id="register__progress-bar__5" class="incomplete"></li>
            <li id="register__progress-bar__6" class="incomplete"></li>
            <li id="register__progress-bar__7" class="incomplete"></li>
            <li id="register__progress-bar__8" class="incomplete"></li>
            <li id="register__progress-bar__9" class="incomplete"></li>
            <li id="register__progress-bar__10" class="incomplete"></li>
        </ol>
        <h5 id="register__progress-text" class="common__progress-text is-visible">Screen <span id="quiz__progress-counter"></span> of 10</h5>
        <div id="register__form-container">
            <form id="register_submit_registration_form" novalidate>
                <div id="register_screen_1">
                    <div id="register_organisation_name_group" class="form-group">
                        <label for="register_organisation_name">
                            Organisation name
                        </label>
                        <input type="text" name="register_organisation_name" id="register_organisation_name" class="form-control" aria-describedby="register_organisation_name_help" placeholder="Your organisation name" required maxlength="140" />
                        <div class="invalid-feedback">
                            Names must be no more than 140 characters.
                        </div>
                    </div>
                    <div id="register_organisation_description_group" class="form-group">
                        <label for="register_organisation_description">
                            Organisation description
                        </label>
                        <textarea name="register_organisation_description" id="register_organisation_description" class="form-control" aria-describedby="register_organisation_description_help" placeholder="Short description of your organisation" required maxlength="500"></textarea>
                        <div class="invalid-feedback">
                            Descriptions must be no more than 500 characters.
                        </div>
                        <small id="register_organisation_description_help" class="form-text text-muted">Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Donec odio.</small>
                    </div>
                </div>
                <div id="register_screen_2">
                    <div id="register_organisation_contact_picture_group" class="form-group">
                        <label for="register_organisation_contact_picture">
                            Contact picture
                        </label>
                        <input type="file" name="register_organisation_contact_picture" id="register_organisation_contact_picture" class="form-control-file" aria-describedby="register_organisation_contact_picture_help" required accept="image/*" />
                        <div class="invalid-feedback">
                            Files must be images of size less than 2 MB.
                        </div>
                        <small id="register_organisation_contact_picture_help" class="form-text text-muted">Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Donec odio.</small>
                    </div>
                    <div id="register_organisation_contact_name_group" class="form-group">
                        <label for="register_organisation_contact_name">
                            Contact name
                        </label>
                        <input type="text" name="register_organisation_contact_name" id="register_organisation_contact_name" class="form-control" aria-describedby="register_organisation_contact_name_help" placeholder="A contact name" required maxlength="140" />
                        <div class="invalid-feedback">
                            Names must be no more than 140 characters.
                        </div>
                        <small id="register_organisation_contact_name_help" class="form-text text-muted">Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Donec odio.</small>
                    </div>
                    <div id="register_organisation_contact_email_address_group" class="form-group">
                        <label for="register_organisation_contact_email_address">
                            Contact email address
                        </label>
                        <input type="email" name="register_organisation_contact_email_address" id="register_organisation_contact_email_address" class="form-control" aria-describedby="register_organisation_contact_email_address_help" placeholder="A contact email address" required maxlength="140" />
                        <div class="invalid-feedback">
                            Please input a valid email address no more than 140 characters in length.
                        </div>
                        <small id="register_organisation_contact_email_address_help" class="form-text text-muted">Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Donec odio.</small>
                    </div>
                </div>
                <div id="register_screen_3">
                    <div id="register_organisation_address_group" class="form-group">
                        <label for="register_organisation_address">
                            Organisation address
                        </label>
                        <textarea name="register_organisation_address" id="register_organisation_address" class="form-control" aria-describedby="register_organisation_address_help" placeholder="Your organisation address" required maxlength="140"></textarea>
                        <div class="invalid-feedback">
                            Please select a valid UK address.
                        </div>
                        <small id="register_organisation_address_help" class="form-text text-muted">Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Donec odio.</small>
                    </div>
                </div>
                <div id="register_screen_4">
                    <div id="register_organisation_permission_group" class="form-group">
                        <div class="form-check">
                            <input type="checkbox" name="register_organisation_permission" id="register_organisation_permission" class="form-check-input" value="" required />
                            <label class="form-check-label" for="register_organisation_permission">
                                I have permission to register on behalf of this organisation.
                            </label>
                            <div class="invalid-feedback">
                                You must agree to this statement before submission.
                            </div>
                        </div>
                    </div>
                    <div id="register_organisation_terms_group" class="form-group">
                        <div class="form-check">
                            <input type="checkbox" name="register_organisation_terms" id="register_organisation_terms" class="form-check-input" value="" required />
                            <label class="form-check-label" for="register_organisation_terms">
                                I agree to the StudentBee Terms and Conditions.
                            </label>
                            <div class="invalid-feedback">
                                You must agree to this statement before submission.
                            </div>
                        </div>
                    </div>
                    <button type="submit" id="register_organisation_submit" class="btn btn-default">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>