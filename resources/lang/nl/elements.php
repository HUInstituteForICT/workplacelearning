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

        ],
        "privacyagreement" => "Door te registreren voor deze applicatie ga je akkoord met de <a href=\"%s\">privacyverklaring</a>.",
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
                "titleassignment" => "Stageopdracht (Beschrijving)",
            ],
            "numhours"           => "Aantal Dagen",
            "companyname"       => "Bedrijfsnaam",
            "companylocation"   => "Locatie",
            "activeinternship"  => "Dit is mijn huidige stage",
            "startdate"         => "Geplande Startdatum",
            "enddate"           => "Geplande Einddatum",
            "contactperson"     => "Contactpersoon",
            "contactphone"      => "Telefoonnummer",
            "contactemail"      => "Emailadres",
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
    "analysis" => [
      "analysisexplanation"  => "Deze pagina geeft je meer inzicht in hoe je werkt en leert, en helpt je om na te denken over je werkzaamheden tijdens je stage, zodat je inzicht krijgt in hoe jij optimaal leert en zo je leerproces kunt verbeteren.",
      "analysischoice"      => "Kies hieronder voor een maand om de activiteiten in die maand te analyseren, of kies ervoor om alles te bekijken.",
      "workingdaysheader"    => "Aantal geregistreerde dagen",
      "workingdaysexplained"  => "Alleen werkdagen waarop minstens 7,5 uur is geregistreerd worden meegerekend.",
      "numberofdays"        => "Aantal dagen:",
      "days"                => "dag(en)",
      "choice"              => "Kies een maand om weer te geven",
      "showall"             => "Toon alle gegevens",
    ],

];
?>
