<?php

return array (
    'AccessLog' => 'Gebruiksgegevens',
    'Category' => 'Categorie (activiteit)',
    'Cohort' => 'Cohort',
    'Competence' => 'Competentie',
    'Difficulty' => 'Moeilijkheid',
    'EducationProgram' => 'Opleiding',
    'LearningActivityActing' => 'Leermoment',
    'LearningActivityProducing' => 'Activiteit',
    'LearningGoal' => 'Leervraag',
    'ResourceMaterial' => 'Materiaal Hulpbron',
    'ResourcePerson' => 'Persoon Hulpbron',
    'Status' => 'Status',
    'TimeSlot' => 'Categorie (leermoment)',
    'WorkplaceLearningPeriod' => 'Stageperiode',

    'step1' => [

        'title' => 'Stap 1: Soort analyse',
        'caption' => 'Wat voor soort analyse wil je toevoegen?',
        'builder' => 'Query bouwen',
        'template' => 'Analyse op basis van template',
        'custom' => 'Eigen SQL Query'
    ],

    'step2' => [

        'builder' => 'Stap 2: Kies entiteit en relaties',
        'entity' => 'Entiteit',
        'relations' => 'Relaties',
    
        'template' => 'Stap 2: Kies en vul een template in',
    
        'custom' => 'Stap 2: Voer query in',
        'no-data' => 'Geen data gevonden.',
        'sql-error' => 'De SQL query is niet valide.'
    ],

    'step3' => [
        'title' => 'Stap 3: filters, sortering en groepering',
        'data' => 'Gegevens',
        'filters' => 'Filters',

        'action-data' => 'Data',
        'action-sum' => 'Som',
        'action-count' => 'Aantal',

        'filter-equals' => 'Is gelijk aan',
        'filter-between' => 'Is tussen',
        'filter-largerthan' => 'Groter dan',
        'filter-smallerthan' => 'Kleiner dan',
        'filter-groupby' => 'Groepeer op',
        'filter-limit' => 'Limiteer resultaten',

        'value' => 'Waarde'
    ],

    'step4' => [
        'title' => 'Stap 4: Grafiek maken',
        'name' => 'Naam',
        'name-caption' => 'Naam analyse',
        'cache' => 'Cache voor',
        'cache-caption' => 'Cache voor X',
        
        'seconds' => 'Seconden',
        'minutes' => 'Minuten',
        'hours' => 'Uren',
        'days' => 'Dagen',
        'weeks' => 'Weken',
        'months' => 'Maanden',
        'years' => 'Jaren',

        'graph-type' => 'Kies een grafiek',
        'pie' => 'Cirkeldiagram',
        'bar' => 'Staafdiagram',
        'line' => 'Lijngrafiek'
    ],

    'next' => 'Volgende',
    'previous' => 'Vorige',
    'cancel' => 'Annuleren',
    'save' => 'Opslaan'
);
