<?php
    return array(
        "title" => "Login",

        "token" => "Token missing or not valid.",

        "lang_form" => array(

            "select_lang"   => "Select Language",
            "translate"     => "Translate",
        ),

        "login_form" => array(
            "id_placeholder"        => "ID",
            "password_placeholder"  => "password",
            "submit_btn"            => "Submit",
            "form_heading"          => "Login",

            "form_error" => array(
                "empty_field"   => "Please fill out the fields.",
                "incorrect"     => "Incorrect userid or password",
                "many_attempt"  => "Too many login attempt. Try again later after."
            )

        ),

        "first_login_form" => array(
            "old_pwd"       => "old Password",
            "new_pwd"       => "new password",
            "confirm_pwd"   => "confirm password",
            "save_btn"      => "save",
            "form_heading"  => "First time login",
            "success"       => "New password saved",

            "form_error" => array(
                "incorrect_old_pwd" => "Old password is incorrect"
            )
        ),

        "scheduled_pwd_reset" => array(
            "old_pwd"       => "old Password",
            "new_pwd"       => "new password",
            "confirm_pwd"   => "confirm password",
            "save_btn"      => "save",
            "form_heading"  => "Your current password has already expired. Change your password",
            "success"       => "New password saved",

            "form_error" => array(
                "incorrect_old_pwd" => "Old password is incorrect"
            )
        )

    );