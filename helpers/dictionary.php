<?php
if (strcmp(
  realpath(__FILE__),
  realpath($_SERVER["SCRIPT_FILENAME"])
) == 0) {
  header("Location: /");
  return;
}

define("VALID_USERNAME_REQ", "Username is required.");
define("VALID_USERNAME_MIN", "Username must be more than or equal 6 characters.");
define("VALID_USERNAME_MAX", "Username must be less than or equal 125 characters.");
define("VALID_USERNAME_EXIST", "Username is already existing.");
define("VALID_PASSWORD_REQ", "Password is required.");
define("VALID_PASSWORD_MIN", "Password must be more than or equal 6 characters.");
define("VALID_PASSWORD_MAX", "Password must be less than or equal 125 characters.");
define("VALID_PASSWORD_MATCH", "Password does not match retype password.");
define("VALID_REPASSWORD_REQ", "Retype password is required.");
define("VALID_REPASSWORD_MIN", "Retype password must be more than or equal 6 characters.");
define("VALID_REPASSWORD_MAX", "Retype password must be less than or equal 125 characters.");
define("VALID_OLDPASSWORD_REQ", "Old password is required.");
define("VALID_OLDPASSWORD_MIN", "Old password must be more than or equal 6 characters.");
define("VALID_OLDPASSWORD_MAX", "Old password must be less than or equal 125 characters.");
define("VALID_FIRSTNAME_REQ", "First name is required.");
define("VALID_FIRSTNAME_MAX", "First name must be less than or equal 100 characters.");
define("VALID_LASTNAME_MAX", "Last name must be less than or equal 100 characters.");
define("VALID_EMAIL_REQ", "Email is required.");
define("VALID_EMAIL_FORMAT", "Email is written on a wrong format.");
define("VALID_EMAIL_MIN", "Email must be more than or equal 5 characters.");
define("VALID_EMAIL_MAX", "Email must be less than or equal 125 characters.");
define("VALID_EMAIL_EXIST", "Email is already existing.");
define("VALID_DEVICECARD_REQ", "Device card is required.");
define("VALID_DEVICECARD_MIN", "Device card must be more than or equal 8 characters.");
define("VALID_DEVICECARD_MAX", "Device card must be less than or equal 15 characters.");
define("VALID_DEVICECARD_FOUND", "Device does not exist.");
define("VALID_DEVICECARD_USED", "Device is previously used.");
define("VALID_SENSOR_REQ", "Sensor is required.");
define("VALID_ACTUATOR_REQ", "Actuator is required.");
define("VALID_STATE_REQ", "State is required.");
define("VALID_OPERATOR_REQ", "Operator is required.");
define("VALID_READING_REQ", "Reading is required.");
define("VALID_READING_NUM", "Reading should be a numeric value.");
define("VALID_READING_MAX", "Reading length must be less than or equal 5 digits (numbers).");
define("VALID_MESSAGE_REQ", "Message is required.");
define("VALID_MESSAGE_MAX", "Message must be less than or equal 125 characters.");
define("VALID_BIRTHDATE_MAX", "Birth date is written on a wrong format.");
define("VALID_BIO_MAX", "Bio must be less than or equal 1000 characters.");
define("VALID_AVATAR_EXT", "Avatar extension should be jpg, jpeg or png.");
define("VALID_AVATAR_MAX", "Avatar size should be less than or equal 2 MBytes.");
define("VALID_AVATAR_REQ", "Avatar is required.");
define("CONFIRM_AVATAR_UPDATE", "Are your confirming on updating your avatar?");
define("REGISTER_SUCCESS", "Account is created successfully.");
define("LOGIN_SUCCESS", "You logged in successfully.");
define("WRONG_USERNAME", "Username does not exist.");
define("WRONG_AUTH", "Username and/or password is/are wrong.");
define("NO_Dispos", "You don't have any Dispos.");
define("NO_ALARMS", "You don't have any alarms (notifications).");
define("NO_RULES", "You don't have any rules.");
define("NO_READINGS", "Device does not have any readings.");
define("NO_CALIBRATIONS", "Device does not have any calibrations.");
define("DEVICE_ADDED", "Device is added successfully.");
define("VALID_GENDER_WRONG", "You selected a wrong gender.");
define("UPDATE_PROFILE_SUCCESS", "Your profile is updated successfully.");
define("UPDATE_SETTINGS_SUCCESS", "Your settings is updated successfully.");
define("AVATAR_SUCCESS", "Your avatar is updated successfully.");
define("RULE_SUCCESS", "Rule is added successfully.");
define("RULE_UPDATE_SUCCESS", "Rule is updated successfully.");
define("CALIBRATION_SUCCESS", "Calibration is added successfully.");
define("CALIBRATION_UPDATE_SUCCESS", "Calibration is updated successfully.");
define("DELETE_SUCCESS", "Record is deleted successfully.");
define("DELETE_ALL_SUCCESS", "All records are deleted successfully.");
define("SOMETHING_WRONG", "Something went wrong.");
define("WRONG_OLD_PASSWORD", "You entered a wrong old password.");
define("CONFIRM_DELETE", "Are you sure to delete the record?");
define("CONFIRM_DELETE_ALL", "Are you sure to delete the records?");
define("NO_DATA", "There are no records to fetch.");
