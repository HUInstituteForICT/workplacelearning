<?php
/**
 * This file (elements.php) was created on 05/23/2016 at 12:53.
 * (C) Max Cassee
 * This project was commissioned by HU University of Applied Sciences.
 */

return [
    // Header
    "header" => [
        "title" => "Werkplekleren",
    ],
    // Sidebar
    "sidebar"   => [
        "labels"  => [
        "dash"          => "Dashboard",
        "input"         => "Leerproces",
        "reports"       => "Analyse",
        "calendar"      => "Deadlines",
        "profile"       => "Profiel",
        "settings"      => "Voortgang",
        "logout"        => "Uitloggen",
      ],
    ],

    "alerts"    => [
        "notice" => "Melding",
        "warning" => "Waarschuwing",
        "error"   => "Fout",
        "success" => "Succes",
    ],
    
    // Registration Form
    "registration"  => [
        "title" => "Account Registratie",
        "labels"    => [
            "studentnr"         => "Studentnummer",
            "firstname"         => "Voornaam",
            "lastname"          => "Achternaam",
            "email"             => "E-Mail",
            "phone"             => "Telefoonnummer",
            "birthdate"         => "Geboortedatum",
            "gender"            => [
                "text"  => "Ik ben een",
                "male"  => "Man",
                "female"=> "Vrouw",
            ],
            "password"          => "Wachtwoord",
            "password_confirm"  => "Wachtwoord (Bevestiging)",
            "secret"            => "Registratiecode",
            "answer"            => "Controlevraag: Waar ben je geboren?",
        ],
        "placeholders"  => [
            "studentnr"         => "7 Cijfers",
            "firstname"         => "Bijv: Jan",
            "lastname"          => "Bijv: Jansen",
            "email"             => "Bijv: jan.jansen@student.hu.nl",
            "phone"             => "Bijv: 0612345678",
            "password"          => "Min. 8 karakters, hoofdlettergevoelig",
            "secret"            => "Registratiecode",
            "answer"            => "Waar ben je geboren?",
        ],
        "buttons"   => [
            "register"  => "Registreer",
            "reset"     => "Reset",

        ]
    ],

    // Profile Page
    "profile"   => [
        "title" => "Profiel",
        "btnsave"   => "Opslaan",
        "labels"    => [
            "studentnr"         => "Studentnummer",
            "firstname"         => "Voornaam",
            "lastname"          => "Achternaam",
            "email"             => "E-Mail",
            "phone"             => "Telefoonnummer",
            "birthdate"         => "Geboortedatum",
            "password"          => "Wachtwoord",
            "password_repeat"   => "Wachtwoord (Bevestiging)",
            "secretquestion"    => [
                "1"     => "Waar ben je geboren?",
                "2"     => "Hoe heet(te) je eerste huisdier?",
                "3"     => "Wat is je lievelingskleur?",
            ],
        ],
        "placeholders"  => [
            "firstname"         => "Bijv: Jan",
            "lastname"          => "Bijv: Jansen",
            "email"             => "Bijv: jan.jansen@student.hu.nl",
            "phone"             => "Bijv: 0612345678",
            "password"          => "Min. 8 karakters, hoofdlettergevoelig",
            "answer"            => "Waar ben je geboren?",
            "categoryname"      => "Naam Nieuwe Categorie",
            "cooperationname"   => "Naam Nieuw Samenwerkingsverband",
            "cooperationdesc"   => "Omschrijving Nieuw Samenwerkingsverband",
        ],
        "internships"   => [
            "backtoprofile" => "Terug naar Profiel",
            "profile"   => [
                "title"             => "Mijn Stages",
            ],
            "current"   => [
                "title"     => "Stageplaats Informatie",
                "titleadditive" => "(Huidige Stage)",
                "titleassignment" => "Stage Opdracht (Omschrijving)",
            ],
            "numhours"          => "Aantal Uren",
            "companyname"       => "Bedrijfsnaam",
            "companylocation"   => "Locatie",
            "activeinternship"  => "Dit is mijn huidige stage",
            "startdate"         => "Geplande Startdatum",
            "enddate"           => "Geplande Einddatum",
            "contactperson"     => "Contactpersoon",
            "contactphone"      => "Telefoonnummer",
            "contactemail"      => "Email Adres",
        ],
        "categories"   => [
            "title"             => "Categorieën",
            "internshipname"    => "Gekoppeld aan stage",
            "categoryname"      => "Naam Categorie",
        ],
        "cooperations"   => [
            "title"             => "Samenwerkingsverbanden",
            "internshipname"    => "Gekoppeld aan stage",
            "cooperationname"   => "Naam Verband",
            "cooperationdesc"   => "Omschrijving",
        ],
    ],

    "general"   => [
        "mayonlycontain"    => "Dit veld mag alleen de volgende tekens bevatten:",
    ],

    "calendar" => [
        "labels" => [
            "newdeadline"    => "Afspraak",
            "date"              => "Datum",
        ],
        "placeholders"  => [
            "description"   => "Omschrijving",
        ],
        "btntext" => [
            "newdeadline"   => "Nieuwe Afspraak",
            "adddeadline"   => "Opslaan",
            "removedeadline"=> "Verwijderen",
        ],
        "notifications" => [
            "success"   => "is succesvol opgeslagen",
            "fail"      => "kon niet worden opgeslagen",
        ],
    ],
    // Weekstaten
    "tasks"     => [
        "hour"  => "uur|uur",
    ],

];
?>