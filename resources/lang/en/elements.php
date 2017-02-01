<?php
/**
 * This file (elements.php) was created on 05/23/2016 at 12:53.
 * (C) Max Cassee
 * This project was commissioned by HU University of Applied Sciences.
 */

return [
    // Header
    "header" => [
      "title" => "Workplace Assisted Learning",
    ],
    // Sidebar
    "sidebar" => [
        "labels" => [
            "dash" => "Dashboard",
            "input" => "Activities",
            "reports" => "Analysis",
            "calendar" => "Deadlines",
            "profile" => "Profile",
            "settings" => "Progress",
            "logout" => "Log Out",
        ],
    ],

    "alerts"    => [
        "notice" => "Notice",
        "warning" => "Warning",
        "error"   => "Error",
        "success" => "Success",
    ],

    // Registration Form
    "registration" => [
        "title" => "Account Registration",
        "labels" => [
            "studentnr" => "Student No.",
            "firstname" => "Firstname",
            "lastname" => "Lastname",
            "email" => "E-Mail",
            "phone" => "Phone Number",
            "birthdate" => "Date of Birth",
            "gender" => [
                "text" => "I am a",
                "male" => "Man",
                "female" => "Woman",
            ],
            "password" => "Password",
            "password_confirm" => "Password (Confirmation)",
            "secret" => "Registration Code",
            "answer" => "Security Question: Where were you born?",
        ],
        "placeholders" => [
            "studentnr" => "7 Digits",
            "firstname" => "Ex: Jan",
            "lastname" => "Ex: Jansen",
            "email" => "Ex: jan.jansen@student.hu.nl",
            "phone" => "Ex: 0612345678",
            "password" => "At least 8 characters, Case Sensitive",
            "secret" => "Ask your mentor about this code.",
            "answer" => "Ex: Amsterdam",
        ],
        "buttons" => [
            "register"  => "Register",
            "reset"     => "Reset",
        ],
        "privacyagreement" => "By registering you agree with our <a href=\"%s\">privacy policy</a>.",
    ],

    // Profile Page
    "profile"   => [
        "title" => "Profile",
        "btnsave"   => "Save",
        "labels"    => [
            "studentnr"         => "Student Number",
            "firstname"         => "First name",
            "lastname"          => "Last name",
            "email"             => "E-Mail",
            "phone"             => "Phone Number",
            "birthdate"         => "Date of Birth",
            "password"          => "Password",
            "password_repeat"   => "Password (Confirmation)",
            "secretquestion"    => [
                "1"     => "Where were you born?",
                "2"     => "What is or was the name of your first pet?",
                "3"     => "What is your favorite color?",
            ],
        ],
        "placeholders"  => [
            "firstname"         => "Ex: Jan",
            "lastname"          => "Ex: Jansen",
            "email"             => "Ex: jan.jansen@student.hu.nl",
            "phone"             => "Ex: 0612345678",
            "password"          => "At least 8 characters, Case sensitive",
            "answer"            => "Where were you born?",
            "categoryname"    => "New Category Name",
            "cooperationname"    => "New Cooperation Name",
            "cooperationdesc"    => "New Cooperation Description",
        ],
        "internships"   => [
            "backtoprofile" => "Back to Profile",
            "profile"   => [
                "title"             => "My Internships",
            ],
            "current"   => [
                "title"     => "Placement Information",
                "titleadditive" => "(Current Internship)",
                "titleassignment" => "Internship Assignment",
            ],
            "numdays"          => "Number of Days",
            "companyname"       => "Company Name",
            "companylocation"   => "Location",
            "companystreet"     => "Street",
            "companyhousenr"    => "Building no.",
            "companypostalcode" => "Postal Code",
            "activeinternship"  => "This is my current internship",
            "startdate"         => "Planned start date",
            "enddate"           => "Planned end date",
            "contactperson"     => "Contact",
            "contactphone"      => "Phone Number",
            "contactemail"      => "Email Address",
        ],
        "categories"   => [
            "title"             => "Categories",
            "internshipname"    => "Linked to Internship",
            "categoryname"      => "Name of Category",
        ],
        "cooperations"   => [
            "title"             => "Cooperations",
            "internshipname"    => "Linked to Internship",
            "cooperationname"   => "Name of Cooperation",
            "cooperationdesc"   => "Description",
        ],
    ],

    "general"   => [
        "mayonlycontain"    => "Dit veld mag alleen de volgende tekens bevatten:",
    ],

    "calendar" => [
        "labels" => [
            "newdeadline"    => "New Appointment",
            "date"              => "Date",

        ],
        "placeholders"  => [
            "description"   => "Description",
        ],
        "btntext" => [
            "newdeadline"   => "Appointment",
            "adddeadline"   => "Save",
            "removedeadline"=> "Remove",
        ],
        "notifications" => [
            "success"   => "was saved successfully",
            "fail"      => "could not be saved",
        ],
    ],

    // Weekstaten
    "tasks"     => [
        "hour"  => "hour|hours",
    ],

    "analysis" => [
      "analysisexplanation"  => "This page can give you insight in how you work and learn, and helps you to reflect on your activities in the workplace. This can give you insight in how to improve your learning process.",
      "analysischoice"      => "Below, you can choose to analyse the activities for a specific month or to view activities for all months.",
      "workingdaysheader"    => "Number of registered working days",
      "workingdaysexplained"  => "Only days with at least 7.5 hours of registered activities are included.",
      "numberofdays"          => "Number of days:",
      "days"                => "day(s)",
      "choice"              => "Choose a month to display",
      "showall"             => "Show all data",
    ],

];
?>
