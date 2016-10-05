<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted'             => 'Deze waarde: :attribute moet worden geaccepteerd.',
    'active_url'           => 'Deze URL: :attribute is ongeldig.',
    'after'                => 'De datum: :attribute moet na :date vallen.',
    'alpha'                => 'De waarde :attribute mag alleen maar letters bevatten.',
    'alpha_dash'           => 'De waarde :attribute mag alleen maar letters, nummers en streepjes bevatten.',
    'alpha_num'            => 'De waarde :attribute mag alleen maar letters en nummers bevatten.',
    'array'                => 'De waarde :attribute moet een array zijn.',
    'before'               => 'De datum :attribute moet voor :date vallen.',
    'between'              => [
        'numeric' => 'De waarde :attribute moet tussen :min en :max zijn.',
        'file'    => 'De waarde :attribute moet tussen :min en :max kilobytes groot zijn.',
        'string'  => 'De waarde :attribute moet tussen :min en :max karakters lang zijn.',
        'array'   => 'De waarde :attribute moet tussen :min en :max items bevatten.',
    ],
    'boolean'              => 'De waarde :attribute waar of niet waar zijn.',
    'confirmed'            => 'De waarde :attribute komt niet overeen met de bevestiging.',
    'date'                 => 'De waarde :attribute is geen geldige datum.',
    'date_format'          => 'De waarde :attribute komt niet met het formaat :format overeen.',
    'different'            => 'De waarde :attribute en :other moeten verschillend zijn.',
    'digits'               => 'De waarde :attribute moeten :digits cijfers zijn.',
    'digits_between'       => 'De waarde :attribute tussen :min en :max cijfers zijn.',
    'distinct'             => 'De waarde :attribute heeft een duplicaat.',
    'email'                => 'De waarde :attribute moet een geldig email adres zijn.',
    'exists'               => 'De geselecteerde waarde :attribute is ongeldig.',
    'filled'               => 'De waarde :attribute field is vereist.',
    'image'                => 'De waarde :attribute moet een afbeelding zijn.',
    'in'                   => 'De geselecteerde waarde :attribute is ongeldig.',
    'in_array'             => 'De waarde :attribute bestaat niet in :other.',
    'integer'              => 'De waarde :attribute moet een nummer zijn.',
    'ip'                   => 'De waarde :attribute moet een geldig IP adres zijn.',
    'json'                 => 'De waarde :attribute moet een geldige JSON string zijn.',
    'max'                  => [
        'numeric' => 'De waarde :attribute mag niet groter zijn dan :max.',
        'file'    => 'De waarde :attribute mag niet groter zijn dan :max kilobytes.',
        'string'  => 'De waarde :attribute mag niet langer zijn dan :max karakters.',
        'array'   => 'De waarde :attribute mag niet meer dan :max items bevatten.',
    ],
    'mimes'                => 'De waarde :attribute moet een bestand zijn van het type: :values.',
    'min'                  => [
        'numeric' => 'De waarde :attribute moet ten minste :min zijn.',
        'file'    => 'De waarde :attribute moet ten minste :min kilobytes groot zijn.',
        'string'  => 'De waarde :attribute moet ten minste :min karakters lang zijn.',
        'array'   => 'De waarde :attribute moet ten minste :min items bevatten.',
    ],
    'not_in'               => 'De waarde :attribute is ongeldig.',
    'numeric'              => 'De waarde :attribute moet een nummer zijn.',
    'present'              => 'De waarde :attribute moet aanwezig zijn.',
    'regex'                => 'De formattering van :attribute is ongeldig.',
    'required'             => 'Het :attribute veld is vereist.',
    'required_if'          => 'Het :attribute veld is vereist als :other gelijk is aan :value.',
    'required_unless'      => 'Het :attribute veld is vereist tenzij :other voorkomt in :values.',
    'required_with'        => 'Het :attribute veld is vereist als :values aanwezig is.',
    'required_with_all'    => 'Het :attribute veld is vereist als :values aanwezig is.',
    'required_without'     => 'Het :attribute veld is vereist als :values niet aanwezig is.',
    'required_without_all' => 'Het :attribute veld is vereist als geen van :values aanwezig is.',
    'same'                 => 'Het :attribute en :other moeten overeenkomen.',
    'size'                 => [
        'numeric' => 'De waarde van :attribute moet :size zijn.',
        'file'    => 'De waarde van :attribute moet :size kilobytes zijn.',
        'string'  => 'De waarde van :attribute moet :size karakters zijn.',
        'array'   => 'De waarde van :attribute moet :size items bevatten.',
    ],
    'string'               => 'De waarde van :attribute moet een string zijn.',
    'timezone'             => 'De waarde van :attribute moet een geldige tijdzone zijn.',
    'unique'               => 'De waarde van :attribute is al in gebruik.',
    'url'                  => 'De waarde van :attribute heeft een ongeldig formaat.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */

    'attributes' => [],

];
