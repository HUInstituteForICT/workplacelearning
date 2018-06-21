<?php

return array (
    'AccessLog' => 'Access log',
    'Category' => 'Category (activity)',
    'Cohort' => 'Cohort',
    'Competence' => 'Competence',
    'Difficulty' => 'Difficulty',
    'EducationProgram' => 'Education program',
    'LearningActivityActing' => 'Learning moment',
    'LearningActivityProducing' => 'Activity',
    'LearningGoal' => 'Learning goal',
    'ResourceMaterial' => 'Resource material',
    'ResourcePerson' => 'Resource person',
    'Status' => 'Status',
    'TimeSlot' => 'Category (learning moment)',
    'WorkplaceLearningPeriod' => 'Workplace learning period',

    'step1' => [

        'title' => 'Step 1: Type of analysis',
        'caption' => 'What kind of analysis do you want to add?',
        'builder' => 'Build a query',
        'template' => 'Analysis based on template',
        'custom' => 'Custom SQL Query'
    ],

    'step2' => [

        'builder' => 'Step 2: Choose entity and relations',
        'entity' => 'Entity',
        'relations' => 'Relations',

        'template' => 'Step 2: Choose and use a template',

        'custom' => 'Step 2: Enter query',
        'no-data' => 'No data found.',
        'sql-error' => 'The SQL query is not valid.',
        'show-query' => 'Show query'
    ],

    'step3' => [
        'title' => 'Step 3: filters, sorting and grouping',
        'data' => 'Data',
        'filters' => 'Filters',
        'sort' => 'Sort',
        'limit' => 'Limit',
        'limit-caption' => 'Number of',

        'action-data' => 'Data',
        'action-sum' => 'Sum',
        'action-count' => 'Number',
        'action-avg' => 'Average',

        'filter-equals' => 'Equal to',
        'filter-largerthan' => 'Greather than',
        'filter-smallerthan' => 'Less than',
        'filter-groupby' => 'Group by',
        'filter-limit' => 'Limit results',

        'asc' => 'Ascending',
        'desc' => 'Descending',

        'value' => 'Value'
    ],

    'step4' => [
        'title' => 'Step 4: Create a graph',
        'name' => 'Name',
        'name-caption' => 'Name analysis',
        'cache' => 'Cache for',
        'cache-caption' => 'Cache for X',

        'seconds' => 'Seconds',
        'minutes' => 'Minutes',
        'hours' => 'Hours',
        'days' => 'Days',
        'weeks' => 'Weeks',
        'months' => 'Months',
        'years' => 'Years',

        'graph-type' => 'Select a chart',
        'pie' => 'Pie chart',
        'bar' => 'Bar chart',
        'line' => 'Line graph',
        'x-axis' => 'X-axis',
        'y-axis' => 'Y-axis',

        'error-name' => 'Name field is required.',
        'error-cache-duration' => 'Cache field is required.',
        'error-chart-id' => 'Please choose a chart type',
        'error-axis' => 'X- en Y-as can\'t be empty'
    ],

    'next' => 'Next',
    'previous' => 'Back',
    'cancel' => 'Cancel',
    'save' => 'Save',

    'query-error' => 'Something went wrong while executing the query.'
);
