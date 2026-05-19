<?php
defined('BASEPATH') or exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Error Codes Hierarchy
|--------------------------------------------------------------------------
|
| // ===================== MODULE NAME =====================
|     'MODULE' => [
|         'FUNCTION' => [
|             'CODE' => [
|                 'message' => 'Message displayed on the screen.',
|                 'hint'    => 'Description for debugging error.',
|                 'level'   => 'success / info / warning / error',
|                 'cobtn'   => TRUE / FALSE,
|                 'cotext'   => 'String',
|                 'cabtn'   => TRUE / FALSE,
|                 'catext'   => 'String',
|                 'redirectUrl'   => 'String',
|             ],
|         ],
|     ],
|
*/

$config['error_codes'] = [

    // ===================== SYSTEM =====================
    'SYS' => [
        'QB' => [ // Error function query_builder()
            'E001' => [
                'message' => 'A system error has occurred. Please report the problem to the administrator and check the system again.',
                'hint'    => 'The system in the check_query() helper encountered a problem.',
                'level'   => 'warning',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => FALSE,
                'catext' => null,
                'redirectUrl' => null
            ],
            'E002' => [
                'message' => 'A system error has occurred. Please report the problem to the administrator and check the system again.',
                'hint'    => 'There was a problem with the GET data model.',
                'level'   => 'warning',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => FALSE,
                'catext' => null,
                'redirectUrl' => null
            ],
            'E003' => [
                'message' => 'Query Whereclaue is wrong or missing, please check the value again.',
                'hint'    => 'Check back in the logic section.',
                'level'   => 'warning',
                'cobtn' => TRUE,
                'cotext' => 'OK',
                'cabtn' => FALSE,
                'catext' => null,
                'redirectUrl' => null
            ]
        ],
        'BUG' => [
            'E001' => [
                'message' => 'A system error has occurred. Please report the problem to the administrator and check the system again.',
                'hint'    => "Ajax request on file 'delete.invy.js' error.",
                'level'   => 'error',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
            'E002' => [
                'message' => 'A system error has occurred. Please report the problem to the administrator and check the system again.',
                'hint'    => "The parameter in the helper 'get_error_info' is an array data type, but the array is not an array with an empty key 'code'.",
                'level'   => 'error',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ]
        ],
        'FRM' => [
            'E001' => [
                'message' => 'Confirm password does not match.',
                'hint'    => "",
                'level'   => 'warning',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
            'E002' => [
                'message' => 'Sorry, the uploaded file is too large.',
                'hint'    => "",
                'level'   => 'warning',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
            'E003' => [
                'message' => 'Sorry, the selected file does not match the allowed format.',
                'hint'    => "",
                'level'   => 'warning',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ]
        ],
    ],

    // ===================== EXMP =====================
    'EXMP' => [
        'DTL' => [ // Error function detailed()
            'E001' => [
                'message' => 'The data you are looking for was not found',
                'hint'    => 'The data you are looking for does not exist in the database.',
                'level'   => 'warning',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
            'E002' => [
                'message' => 'A system error has occurred. Please report the problem to the administrator and check the system again.',
                'hint'    => 'Query error.',
                'level'   => 'error',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
            'E003' => [
                'message' => 'A system error has occurred. Please report the problem to the administrator and check the system again.',
                'hint'    => 'ID and WhereClaus are empty.',
                'level'   => 'warning',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
        ],
        'PTC' => [ // Error in model 'test()' and in patch section
            'E001' => [
                'message' => 'The data is up to date.',
                'hint'    => '',
                'level'   => 'info',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ]
        ],
        'DEL' => [ // Error in model 'test()' and in delete section
            'E001' => [
                'message' => 'A system error has occurred. Please report the problem to the administrator and check the system again.',
                'hint'    => 'Query error.',
                'level'   => 'error',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
            'E002' => [
                'message' => 'A system error has occurred. Please report the problem to the administrator and check the system again.',
                'hint'    => 'Empty ID.',
                'level'   => 'error',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
        ],
        'RST' => [ // Error in model 'test()' and in restore section
            'E001' => [
                'message' => 'A system error has occurred. Please report the problem to the administrator and check the system again.',
                'hint'    => 'Empty ID.',
                'level'   => 'error',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
            'E002' => [
                'message' => 'Sorry, the data you are restoring already exists. Do you still want to replace it?',
                'hint'    => 'Duplicate data.',
                'level'   => 'question',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
        ],
        'CNU' => [ // Error model function create_and_edit()
            'E001' => [
                'message' => 'Sorry, the data you entered already exists.',
                'hint'    => "The data entered already exists in the database, search based on the 'test' column",
                'level'   => 'warning',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
            'E002' => [
                'message' => 'A system error has occurred. Please report the problem to the administrator and check the system again.',
                'hint'    => "insert data is empty.",
                'level'   => 'error',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
        ],
    ],

    // ===================== USRS =====================
    'USRS' => [
        'DTL' => [ // Error function detailed()
            'E001' => [
                'message' => 'The data you are looking for was not found',
                'hint'    => 'The data you are looking for does not exist in the database.',
                'level'   => 'warning',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
            'E002' => [
                'message' => 'A system error has occurred. Please report the problem to the administrator and check the system again.',
                'hint'    => 'Query error.',
                'level'   => 'error',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
            'E003' => [
                'message' => 'A system error has occurred. Please report the problem to the administrator and check the system again.',
                'hint'    => 'ID and WhereClaus are empty.',
                'level'   => 'warning',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
        ],
        'PTC' => [ // Error in model 'users()' and in patch section
            'E001' => [
                'message' => 'The data is up to date.',
                'hint'    => '',
                'level'   => 'info',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ]
        ],
        'PPC' => [ // Error in model 'profiles()' and in patch section
            'E001' => [
                'message' => 'The data is up to date.',
                'hint'    => '',
                'level'   => 'info',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ]
        ],
        'DEL' => [ // Error in model 'users()' and in delete section
            'E001' => [
                'message' => 'A system error has occurred. Please report the problem to the administrator and check the system again.',
                'hint'    => 'Query error.',
                'level'   => 'error',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
            'E002' => [
                'message' => 'A system error has occurred. Please report the problem to the administrator and check the system again.',
                'hint'    => 'Empty ID.',
                'level'   => 'error',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
        ],
        'PDL' => [ // Error in model 'profiles()' and in delete section
            'E001' => [
                'message' => 'A system error has occurred. Please report the problem to the administrator and check the system again.',
                'hint'    => 'Query error.',
                'level'   => 'error',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
            'E002' => [
                'message' => 'A system error has occurred. Please report the problem to the administrator and check the system again.',
                'hint'    => 'Empty ID.',
                'level'   => 'error',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
        ],
        'RST' => [ // Error in model 'users()' and in restore section
            'E001' => [
                'message' => 'A system error has occurred. Please report the problem to the administrator and check the system again.',
                'hint'    => 'Empty ID.',
                'level'   => 'error',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
            'E002' => [
                'message' => 'Sorry, the data you are restoring already exists. Do you still want to replace it?',
                'hint'    => 'Duplicate data.',
                'level'   => 'question',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
        ],
        'CNU' => [ // Error model function create_and_edit()
            'E001' => [
                'message' => 'Sorry, the data you entered already exists.',
                'hint'    => "The data entered already exists in the database, search based on the 'test' column",
                'level'   => 'warning',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
            'E002' => [
                'message' => 'A system error has occurred. Please report the problem to the administrator and check the system again.',
                'hint'    => "insert data is empty.",
                'level'   => 'error',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
        ],
    ],

    // ===================== GEOLOCATION =====================
    'GEO' => [
        'DTL' => [ // Error function detailed()
            'E001' => [
                'message' => 'The data you are looking for was not found',
                'hint'    => 'The data you are looking for does not exist in the database.',
                'level'   => 'warning',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
            'E002' => [
                'message' => 'A system error has occurred. Please report the problem to the administrator and check the system again.',
                'hint'    => 'Query error.',
                'level'   => 'error',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
            'E003' => [
                'message' => 'A system error has occurred. Please report the problem to the administrator and check the system again.',
                'hint'    => 'ID and WhereClaus are empty.',
                'level'   => 'warning',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
        ],
        'PTC' => [ // Error in model 'geolocation()' and in patch section
            'E001' => [
                'message' => 'The data is up to date.',
                'hint'    => '',
                'level'   => 'info',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ]
        ],
        'DEL' => [ // Error in model 'geolocation()' and in delete section
            'E001' => [
                'message' => 'A system error has occurred. Please report the problem to the administrator and check the system again.',
                'hint'    => 'Query error.',
                'level'   => 'error',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
            'E002' => [
                'message' => 'A system error has occurred. Please report the problem to the administrator and check the system again.',
                'hint'    => 'Empty ID.',
                'level'   => 'error',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
        ],
        'RST' => [ // Error in model 'geolocation()' and in restore section
            'E001' => [
                'message' => 'A system error has occurred. Please report the problem to the administrator and check the system again.',
                'hint'    => 'Empty ID.',
                'level'   => 'error',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
            'E002' => [
                'message' => 'Sorry, the data you are restoring already exists. Do you still want to replace it?',
                'hint'    => 'Duplicate data.',
                'level'   => 'question',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
        ],
        'CNU' => [ // Error model function create_and_edit()
            'E001' => [
                'message' => 'Sorry, the data you entered already exists.',
                'hint'    => "The data entered already exists in the database, search based on the 'test' column",
                'level'   => 'warning',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ]
        ],
    ],
    'VLG' => [
        'DTL' => [ // Error function detailed()
            'E001' => [
                'message' => 'The data you are looking for was not found',
                'hint'    => 'The data you are looking for does not exist in the database.',
                'level'   => 'warning',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
            'E002' => [
                'message' => 'A system error has occurred. Please report the problem to the administrator and check the system again.',
                'hint'    => 'Query error.',
                'level'   => 'error',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
            'E003' => [
                'message' => 'A system error has occurred. Please report the problem to the administrator and check the system again.',
                'hint'    => 'ID and WhereClaus are empty.',
                'level'   => 'warning',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
        ],
        'PTC' => [ // Error in model 'villages()' and in patch section
            'E001' => [
                'message' => 'The data is up to date.',
                'hint'    => '',
                'level'   => 'info',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ]
        ],
        'DEL' => [ // Error in model 'villages()' and in delete section
            'E001' => [
                'message' => 'A system error has occurred. Please report the problem to the administrator and check the system again.',
                'hint'    => 'Query error.',
                'level'   => 'error',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
            'E002' => [
                'message' => 'A system error has occurred. Please report the problem to the administrator and check the system again.',
                'hint'    => 'Empty ID.',
                'level'   => 'error',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
        ],
        'RST' => [ // Error in model 'villages()' and in restore section
            'E001' => [
                'message' => 'A system error has occurred. Please report the problem to the administrator and check the system again.',
                'hint'    => 'Empty ID.',
                'level'   => 'error',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
            'E002' => [
                'message' => 'Sorry, the data you are restoring already exists. Do you still want to replace it?',
                'hint'    => 'Duplicate data.',
                'level'   => 'question',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
        ],
        'CNU' => [ // Error model function create_and_edit()
            'E001' => [
                'message' => 'Sorry, the data you entered already exists.',
                'hint'    => "The data entered already exists in the database, search based on the 'test' column",
                'level'   => 'warning',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ]
        ],
    ],
    'DCT' => [
        'DTL' => [ // Error function detailed()
            'E001' => [
                'message' => 'The data you are looking for was not found',
                'hint'    => 'The data you are looking for does not exist in the database.',
                'level'   => 'warning',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
            'E002' => [
                'message' => 'A system error has occurred. Please report the problem to the administrator and check the system again.',
                'hint'    => 'Query error.',
                'level'   => 'error',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
            'E003' => [
                'message' => 'A system error has occurred. Please report the problem to the administrator and check the system again.',
                'hint'    => 'ID and WhereClaus are empty.',
                'level'   => 'warning',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
        ],
        'PTC' => [ // Error in model 'districts()' and in patch section
            'E001' => [
                'message' => 'The data is up to date.',
                'hint'    => '',
                'level'   => 'info',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ]
        ],
        'DEL' => [ // Error in model 'districts()' and in delete section
            'E001' => [
                'message' => 'A system error has occurred. Please report the problem to the administrator and check the system again.',
                'hint'    => 'Query error.',
                'level'   => 'error',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
            'E002' => [
                'message' => 'A system error has occurred. Please report the problem to the administrator and check the system again.',
                'hint'    => 'Empty ID.',
                'level'   => 'error',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
        ],
        'RST' => [ // Error in model 'districts()' and in restore section
            'E001' => [
                'message' => 'A system error has occurred. Please report the problem to the administrator and check the system again.',
                'hint'    => 'Empty ID.',
                'level'   => 'error',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
            'E002' => [
                'message' => 'Sorry, the data you are restoring already exists. Do you still want to replace it?',
                'hint'    => 'Duplicate data.',
                'level'   => 'question',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
        ],
        'CNU' => [ // Error model function create_and_edit()
            'E001' => [
                'message' => 'Sorry, the data you entered already exists.',
                'hint'    => "The data entered already exists in the database, search based on the 'test' column",
                'level'   => 'warning',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ]
        ],
    ],
    'CTY' => [
        'DTL' => [ // Error function detailed()
            'E001' => [
                'message' => 'The data you are looking for was not found',
                'hint'    => 'The data you are looking for does not exist in the database.',
                'level'   => 'warning',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
            'E002' => [
                'message' => 'A system error has occurred. Please report the problem to the administrator and check the system again.',
                'hint'    => 'Query error.',
                'level'   => 'error',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
            'E003' => [
                'message' => 'A system error has occurred. Please report the problem to the administrator and check the system again.',
                'hint'    => 'ID and WhereClaus are empty.',
                'level'   => 'warning',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
        ],
        'PTC' => [ // Error in model 'cities()' and in patch section
            'E001' => [
                'message' => 'The data is up to date.',
                'hint'    => '',
                'level'   => 'info',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ]
        ],
        'DEL' => [ // Error in model 'cities()' and in delete section
            'E001' => [
                'message' => 'A system error has occurred. Please report the problem to the administrator and check the system again.',
                'hint'    => 'Query error.',
                'level'   => 'error',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
            'E002' => [
                'message' => 'A system error has occurred. Please report the problem to the administrator and check the system again.',
                'hint'    => 'Empty ID.',
                'level'   => 'error',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
        ],
        'RST' => [ // Error in model 'cities()' and in restore section
            'E001' => [
                'message' => 'A system error has occurred. Please report the problem to the administrator and check the system again.',
                'hint'    => 'Empty ID.',
                'level'   => 'error',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
            'E002' => [
                'message' => 'Sorry, the data you are restoring already exists. Do you still want to replace it?',
                'hint'    => 'Duplicate data.',
                'level'   => 'question',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
        ],
        'CNU' => [ // Error model function create_and_edit()
            'E001' => [
                'message' => 'Sorry, the data you entered already exists.',
                'hint'    => "The data entered already exists in the database, search based on the 'test' column",
                'level'   => 'warning',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ]
        ],
    ],
    'STA' => [
        'DTL' => [ // Error function detailed()
            'E001' => [
                'message' => 'The data you are looking for was not found',
                'hint'    => 'The data you are looking for does not exist in the database.',
                'level'   => 'warning',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
            'E002' => [
                'message' => 'A system error has occurred. Please report the problem to the administrator and check the system again.',
                'hint'    => 'Query error.',
                'level'   => 'error',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
            'E003' => [
                'message' => 'A system error has occurred. Please report the problem to the administrator and check the system again.',
                'hint'    => 'ID and WhereClaus are empty.',
                'level'   => 'warning',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
        ],
        'PTC' => [ // Error in model 'states()' and in patch section
            'E001' => [
                'message' => 'The data is up to date.',
                'hint'    => '',
                'level'   => 'info',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ]
        ],
        'DEL' => [ // Error in model 'states()' and in delete section
            'E001' => [
                'message' => 'A system error has occurred. Please report the problem to the administrator and check the system again.',
                'hint'    => 'Query error.',
                'level'   => 'error',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
            'E002' => [
                'message' => 'A system error has occurred. Please report the problem to the administrator and check the system again.',
                'hint'    => 'Empty ID.',
                'level'   => 'error',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
        ],
        'RST' => [ // Error in model 'states()' and in restore section
            'E001' => [
                'message' => 'A system error has occurred. Please report the problem to the administrator and check the system again.',
                'hint'    => 'Empty ID.',
                'level'   => 'error',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
            'E002' => [
                'message' => 'Sorry, the data you are restoring already exists. Do you still want to replace it?',
                'hint'    => 'Duplicate data.',
                'level'   => 'question',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
        ],
        'CNU' => [ // Error model function create_and_edit()
            'E001' => [
                'message' => 'Sorry, the data you entered already exists.',
                'hint'    => "The data entered already exists in the database, search based on the 'test' column",
                'level'   => 'warning',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ]
        ],
    ],
    'CTR' => [
        'DTL' => [ // Error function detailed()
            'E001' => [
                'message' => 'The data you are looking for was not found',
                'hint'    => 'The data you are looking for does not exist in the database.',
                'level'   => 'warning',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
            'E002' => [
                'message' => 'A system error has occurred. Please report the problem to the administrator and check the system again.',
                'hint'    => 'Query error.',
                'level'   => 'error',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
            'E003' => [
                'message' => 'A system error has occurred. Please report the problem to the administrator and check the system again.',
                'hint'    => 'ID and WhereClaus are empty.',
                'level'   => 'warning',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
        ],
        'PTC' => [ // Error in model 'countries()' and in patch section
            'E001' => [
                'message' => 'The data is up to date.',
                'hint'    => '',
                'level'   => 'info',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ]
        ],
        'DEL' => [ // Error in model 'countries()' and in delete section
            'E001' => [
                'message' => 'A system error has occurred. Please report the problem to the administrator and check the system again.',
                'hint'    => 'Query error.',
                'level'   => 'error',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
            'E002' => [
                'message' => 'A system error has occurred. Please report the problem to the administrator and check the system again.',
                'hint'    => 'Empty ID.',
                'level'   => 'error',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
        ],
        'RST' => [ // Error in model 'countries()' and in restore section
            'E001' => [
                'message' => 'A system error has occurred. Please report the problem to the administrator and check the system again.',
                'hint'    => 'Empty ID.',
                'level'   => 'error',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
            'E002' => [
                'message' => 'Sorry, the data you are restoring already exists. Do you still want to replace it?',
                'hint'    => 'Duplicate data.',
                'level'   => 'question',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
        ],
        'CNU' => [ // Error model function create_and_edit()
            'E001' => [
                'message' => 'Sorry, the data you entered already exists.',
                'hint'    => "The data entered already exists in the database, search based on the 'test' column",
                'level'   => 'warning',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ]
        ],
    ],

    // ===================== AUTH =====================
    'AUTH' => [
        'LGN' => [ // Error controller function login()
            'E001' => [
                'message' => 'User not found or Password is incorrect.',
                'hint'    => "User not found",
                'level'   => 'warning',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
            'E002' => [
                'message' => 'User not found or Password is incorrect.',
                'hint'    => "Incorrect password",
                'level'   => 'warning',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
            'E003' => [
                'message' => 'Sorry, User is not verified. Please check your verification email to verify your account.',
                'hint'    => "User verification false",
                'level'   => 'warning',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
            'E004' => [
                'message' => 'Sorry, this user status is not active.',
                'hint'    => "User status in database off",
                'level'   => 'warning',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
        ],
    ],

    // ===================== APPS_MODULES =====================
    'MODL' => [
        'DTL' => [ // Error function detailed()
            'E001' => [
                'message' => 'The data you are looking for was not found',
                'hint'    => 'The data you are looking for does not exist in the database.',
                'level'   => 'warning',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
            'E002' => [
                'message' => 'A system error has occurred. Please report the problem to the administrator and check the system again.',
                'hint'    => 'Query error.',
                'level'   => 'error',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
            'E003' => [
                'message' => 'A system error has occurred. Please report the problem to the administrator and check the system again.',
                'hint'    => 'ID and WhereClaus are empty.',
                'level'   => 'warning',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
        ],
        'PTC' => [ // Error in model 'modules()' and in patch section
            'E001' => [
                'message' => 'The data is up to date.',
                'hint'    => '',
                'level'   => 'info',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ]
        ],
        'DEL' => [ // Error in model 'modules()' and in delete section
            'E001' => [
                'message' => 'A system error has occurred. Please report the problem to the administrator and check the system again.',
                'hint'    => 'Query error.',
                'level'   => 'error',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
            'E002' => [
                'message' => 'A system error has occurred. Please report the problem to the administrator and check the system again.',
                'hint'    => 'Empty ID.',
                'level'   => 'error',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
            'E003' => [ // Error in controller 'delete()'
                'message' => 'Sorry, this module cannot be deleted because it is a system module. If it is deleted, the system will not function properly.',
                'hint'    => 'Module system.',
                'level'   => 'error',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
        ],
        'RST' => [ // Error in model 'modules()' and in restore section
            'E001' => [
                'message' => 'A system error has occurred. Please report the problem to the administrator and check the system again.',
                'hint'    => 'Empty ID.',
                'level'   => 'error',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
            'E002' => [
                'message' => 'Sorry, the data you are restoring already exists. Do you still want to replace it?',
                'hint'    => 'Duplicate data.',
                'level'   => 'question',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
        ],
        'CNU' => [ // Error model function create_and_edit()
            'E001' => [
                'message' => 'Sorry, the data you entered already exists.',
                'hint'    => "The data entered already exists in the database, search based on the 'test' column",
                'level'   => 'warning',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
            'E002' => [
                'message' => 'A system error has occurred. Please report the problem to the administrator and check the system again.',
                'hint'    => "insert data is empty.",
                'level'   => 'error',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
        ],
    ],

    // ===================== APPS_MODULE_FEATURES =====================
    'FEAT' => [
        'DTL' => [ // Error function detailed()
            'E001' => [
                'message' => 'The data you are looking for was not found',
                'hint'    => 'The data you are looking for does not exist in the database.',
                'level'   => 'warning',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
            'E002' => [
                'message' => 'A system error has occurred. Please report the problem to the administrator and check the system again.',
                'hint'    => 'Query error.',
                'level'   => 'error',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
            'E003' => [
                'message' => 'A system error has occurred. Please report the problem to the administrator and check the system again.',
                'hint'    => 'ID and WhereClaus are empty.',
                'level'   => 'warning',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
        ],
        'PTC' => [ // Error in model 'features()' and in patch section
            'E001' => [
                'message' => 'The data is up to date.',
                'hint'    => '',
                'level'   => 'info',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ]
        ],
        'DEL' => [ // Error in model 'features()' and in delete section
            'E001' => [
                'message' => 'A system error has occurred. Please report the problem to the administrator and check the system again.',
                'hint'    => 'Query error.',
                'level'   => 'error',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
            'E002' => [
                'message' => 'A system error has occurred. Please report the problem to the administrator and check the system again.',
                'hint'    => 'Empty ID.',
                'level'   => 'error',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
            'E003' => [ // Error in controller 'delete()'
                'message' => 'Sorry, this feature cannot be deleted because it is a system feature. If it is deleted, the system will not function properly.',
                'hint'    => 'Feature system.',
                'level'   => 'error',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
        ],
        'RST' => [ // Error in model 'features()' and in restore section
            'E001' => [
                'message' => 'A system error has occurred. Please report the problem to the administrator and check the system again.',
                'hint'    => 'Empty ID.',
                'level'   => 'error',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
            'E002' => [
                'message' => 'Sorry, the data you are restoring already exists. Do you still want to replace it?',
                'hint'    => 'Duplicate data.',
                'level'   => 'question',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
        ],
        'CNU' => [ // Error model function create_and_edit()
            'E001' => [
                'message' => 'Sorry, the data you entered already exists.',
                'hint'    => "The data entered already exists in the database, search based on the 'code' column",
                'level'   => 'warning',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
            'E002' => [
                'message' => 'A system error has occurred. Please report the problem to the administrator and check the system again.',
                'hint'    => "insert data is empty.",
                'level'   => 'error',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
        ],
    ],

    // ===================== APPS_MENUS =====================
    'APMS' => [
        'DTL' => [ // Error function detailed()
            'E001' => [
                'message' => 'The data you are looking for was not found',
                'hint'    => 'The data you are looking for does not exist in the database.',
                'level'   => 'warning',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
            'E002' => [
                'message' => 'A system error has occurred. Please report the problem to the administrator and check the system again.',
                'hint'    => 'Query error.',
                'level'   => 'error',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
            'E003' => [
                'message' => 'A system error has occurred. Please report the problem to the administrator and check the system again.',
                'hint'    => 'ID and WhereClaus are empty.',
                'level'   => 'warning',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
        ],
        'PTC' => [ // Error in model 'menus()' and in patch section
            'E001' => [
                'message' => 'The data is up to date.',
                'hint'    => '',
                'level'   => 'info',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ]
        ],
        'DEL' => [ // Error in model 'menus()' and in delete section
            'E001' => [
                'message' => 'A system error has occurred. Please report the problem to the administrator and check the system again.',
                'hint'    => 'Query error.',
                'level'   => 'error',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
            'E002' => [
                'message' => 'A system error has occurred. Please report the problem to the administrator and check the system again.',
                'hint'    => 'Empty ID.',
                'level'   => 'error',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
        ],
        'PDL' => [ // Error in model 'menus()' and in delete section
            'E001' => [
                'message' => 'A system error has occurred. Please report the problem to the administrator and check the system again.',
                'hint'    => 'Query error.',
                'level'   => 'error',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
            'E002' => [
                'message' => 'A system error has occurred. Please report the problem to the administrator and check the system again.',
                'hint'    => 'Empty ID.',
                'level'   => 'error',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
        ],
        'RST' => [ // Error in model 'menus()' and in restore section
            'E001' => [
                'message' => 'A system error has occurred. Please report the problem to the administrator and check the system again.',
                'hint'    => 'Empty ID.',
                'level'   => 'error',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
            'E002' => [
                'message' => 'Sorry, the data you are restoring already exists. Do you still want to replace it?',
                'hint'    => 'Duplicate data.',
                'level'   => 'question',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
        ],
        'CNU' => [ // Error model function create_and_edit()
            'E001' => [
                'message' => 'Sorry, the data you entered already exists.',
                'hint'    => "The data entered already exists in the database, search based on the 'test' column",
                'level'   => 'warning',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
            'E002' => [
                'message' => 'A system error has occurred. Please report the problem to the administrator and check the system again.',
                'hint'    => "insert data is empty.",
                'level'   => 'error',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
        ],
    ],

    // ===================== APPS_PERMISSION_GROUP =====================
    'APRG' => [
        'DTL' => [ // Error function detailed()
            'E001' => [
                'message' => 'The data you are looking for was not found',
                'hint'    => 'The data you are looking for does not exist in the database.',
                'level'   => 'warning',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
            'E002' => [
                'message' => 'A system error has occurred. Please report the problem to the administrator and check the system again.',
                'hint'    => 'Query error.',
                'level'   => 'error',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
            'E003' => [
                'message' => 'A system error has occurred. Please report the problem to the administrator and check the system again.',
                'hint'    => 'ID and WhereClaus are empty.',
                'level'   => 'warning',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
        ],
        'PTC' => [ // Error in model 'permission_groups()' and in patch section
            'E001' => [
                'message' => 'The data is up to date.',
                'hint'    => '',
                'level'   => 'info',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ]
        ],
        'DEL' => [ // Error in model 'permission_groups()' and in delete section
            'E001' => [
                'message' => 'A system error has occurred. Please report the problem to the administrator and check the system again.',
                'hint'    => 'Query error.',
                'level'   => 'error',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
            'E002' => [
                'message' => 'A system error has occurred. Please report the problem to the administrator and check the system again.',
                'hint'    => 'Empty ID.',
                'level'   => 'error',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
        ],
        'PDL' => [ // Error in model 'permission_groups()' and in delete section
            'E001' => [
                'message' => 'A system error has occurred. Please report the problem to the administrator and check the system again.',
                'hint'    => 'Query error.',
                'level'   => 'error',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
            'E002' => [
                'message' => 'A system error has occurred. Please report the problem to the administrator and check the system again.',
                'hint'    => 'Empty ID.',
                'level'   => 'error',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
        ],
        'RST' => [ // Error in model 'permission_groups()' and in restore section
            'E001' => [
                'message' => 'A system error has occurred. Please report the problem to the administrator and check the system again.',
                'hint'    => 'Empty ID.',
                'level'   => 'error',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
            'E002' => [
                'message' => 'Sorry, the data you are restoring already exists. Do you still want to replace it?',
                'hint'    => 'Duplicate data.',
                'level'   => 'question',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
        ],
        'CNU' => [ // Error model function create_and_edit()
            'E001' => [
                'message' => 'Sorry, the data you entered already exists.',
                'hint'    => "The data entered already exists in the database, search based on the 'test' column",
                'level'   => 'warning',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
            'E002' => [
                'message' => 'A system error has occurred. Please report the problem to the administrator and check the system again.',
                'hint'    => "insert data is empty.",
                'level'   => 'error',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
        ],
    ],

    // ===================== APPS_PERMISSION_GROUP_RELATION =====================
    'APGR' => [
        'DTL' => [ // Error function detailed()
            'E001' => [
                'message' => 'The data you are looking for was not found',
                'hint'    => 'The data you are looking for does not exist in the database.',
                'level'   => 'warning',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
            'E002' => [
                'message' => 'A system error has occurred. Please report the problem to the administrator and check the system again.',
                'hint'    => 'Query error.',
                'level'   => 'error',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
            'E003' => [
                'message' => 'A system error has occurred. Please report the problem to the administrator and check the system again.',
                'hint'    => 'ID and WhereClaus are empty.',
                'level'   => 'warning',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
        ],
        'PTC' => [ // Error in model 'group_relations()' and in patch section
            'E001' => [
                'message' => 'The data is up to date.',
                'hint'    => '',
                'level'   => 'info',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ]
        ],
        'DEL' => [ // Error in model 'group_relations()' and in delete section
            'E001' => [
                'message' => 'A system error has occurred. Please report the problem to the administrator and check the system again.',
                'hint'    => 'Query error.',
                'level'   => 'error',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
            'E002' => [
                'message' => 'A system error has occurred. Please report the problem to the administrator and check the system again.',
                'hint'    => 'Empty ID.',
                'level'   => 'error',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
        ],
        'PDL' => [ // Error in model 'group_relations()' and in delete section
            'E001' => [
                'message' => 'A system error has occurred. Please report the problem to the administrator and check the system again.',
                'hint'    => 'Query error.',
                'level'   => 'error',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
            'E002' => [
                'message' => 'A system error has occurred. Please report the problem to the administrator and check the system again.',
                'hint'    => 'Empty ID.',
                'level'   => 'error',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
        ],
        'RST' => [ // Error in model 'group_relations()' and in restore section
            'E001' => [
                'message' => 'A system error has occurred. Please report the problem to the administrator and check the system again.',
                'hint'    => 'Empty ID.',
                'level'   => 'error',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
            'E002' => [
                'message' => 'Sorry, the data you are restoring already exists. Do you still want to replace it?',
                'hint'    => 'Duplicate data.',
                'level'   => 'question',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
        ],
        'CNU' => [ // Error model function create_and_edit()
            'E001' => [
                'message' => 'Sorry, the data you entered already exists.',
                'hint'    => "The data entered already exists in the database, search based on the 'test' column",
                'level'   => 'warning',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
            'E002' => [
                'message' => 'A system error has occurred. Please report the problem to the administrator and check the system again.',
                'hint'    => "insert data is empty.",
                'level'   => 'error',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
        ],
    ],

    // ===================== UNIVERSITY =====================
    'UNIV' => [
        'DTL' => [ // Error function detailed()
            'E001' => [
                'message' => 'The data you are looking for was not found',
                'hint'    => 'The data you are looking for does not exist in the database.',
                'level'   => 'warning',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
            'E002' => [
                'message' => 'A system error has occurred. Please report the problem to the administrator and check the system again.',
                'hint'    => 'Query error.',
                'level'   => 'error',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
            'E003' => [
                'message' => 'A system error has occurred. Please report the problem to the administrator and check the system again.',
                'hint'    => 'ID and WhereClaus are empty.',
                'level'   => 'warning',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
        ],
        'PTC' => [ // Error in model 'universities()' and in patch section
            'E001' => [
                'message' => 'The data is up to date.',
                'hint'    => '',
                'level'   => 'info',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ]
        ],
        'DEL' => [ // Error in model 'universities()' and in delete section
            'E001' => [
                'message' => 'A system error has occurred. Please report the problem to the administrator and check the system again.',
                'hint'    => 'Query error.',
                'level'   => 'error',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
            'E002' => [
                'message' => 'A system error has occurred. Please report the problem to the administrator and check the system again.',
                'hint'    => 'Empty ID.',
                'level'   => 'error',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
        ],
        'PDL' => [ // Error in model 'universities()' and in delete section
            'E001' => [
                'message' => 'A system error has occurred. Please report the problem to the administrator and check the system again.',
                'hint'    => 'Query error.',
                'level'   => 'error',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
            'E002' => [
                'message' => 'A system error has occurred. Please report the problem to the administrator and check the system again.',
                'hint'    => 'Empty ID.',
                'level'   => 'error',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
        ],
        'RST' => [ // Error in model 'universities()' and in restore section
            'E001' => [
                'message' => 'A system error has occurred. Please report the problem to the administrator and check the system again.',
                'hint'    => 'Empty ID.',
                'level'   => 'error',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
            'E002' => [
                'message' => 'Sorry, the data you are restoring already exists. Do you still want to replace it?',
                'hint'    => 'Duplicate data.',
                'level'   => 'question',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
        ],
        'CNU' => [ // Error model function create_and_edit()
            'E001' => [
                'message' => 'Sorry, the data you entered already exists.',
                'hint'    => "The data entered already exists in the database, search based on the 'test' column",
                'level'   => 'warning',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
            'E002' => [
                'message' => 'A system error has occurred. Please report the problem to the administrator and check the system again.',
                'hint'    => "insert data is empty.",
                'level'   => 'error',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
        ],
    ],

    // ===================== UNIVERSITY COURSE =====================
    'UCSE' => [
        'DTL' => [ // Error function detailed()
            'E001' => [
                'message' => 'The data you are looking for was not found',
                'hint'    => 'The data you are looking for does not exist in the database.',
                'level'   => 'warning',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
            'E002' => [
                'message' => 'A system error has occurred. Please report the problem to the administrator and check the system again.',
                'hint'    => 'Query error.',
                'level'   => 'error',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
            'E003' => [
                'message' => 'A system error has occurred. Please report the problem to the administrator and check the system again.',
                'hint'    => 'ID and WhereClaus are empty.',
                'level'   => 'warning',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
        ],
        'PTC' => [ // Error in model 'courses()' and in patch section
            'E001' => [
                'message' => 'The data is up to date.',
                'hint'    => '',
                'level'   => 'info',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ]
        ],
        'DEL' => [ // Error in model 'courses()' and in delete section
            'E001' => [
                'message' => 'A system error has occurred. Please report the problem to the administrator and check the system again.',
                'hint'    => 'Query error.',
                'level'   => 'error',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
            'E002' => [
                'message' => 'A system error has occurred. Please report the problem to the administrator and check the system again.',
                'hint'    => 'Empty ID.',
                'level'   => 'error',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
        ],
        'PDL' => [ // Error in model 'courses()' and in delete section
            'E001' => [
                'message' => 'A system error has occurred. Please report the problem to the administrator and check the system again.',
                'hint'    => 'Query error.',
                'level'   => 'error',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
            'E002' => [
                'message' => 'A system error has occurred. Please report the problem to the administrator and check the system again.',
                'hint'    => 'Empty ID.',
                'level'   => 'error',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
        ],
        'RST' => [ // Error in model 'courses()' and in restore section
            'E001' => [
                'message' => 'A system error has occurred. Please report the problem to the administrator and check the system again.',
                'hint'    => 'Empty ID.',
                'level'   => 'error',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
            'E002' => [
                'message' => 'Sorry, the data you are restoring already exists. Do you still want to replace it?',
                'hint'    => 'Duplicate data.',
                'level'   => 'question',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
        ],
        'CNU' => [ // Error model function create_and_edit()
            'E001' => [
                'message' => 'Sorry, the data you entered already exists.',
                'hint'    => "The data entered already exists in the database, search based on the 'test' column",
                'level'   => 'warning',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
            'E002' => [
                'message' => 'A system error has occurred. Please report the problem to the administrator and check the system again.',
                'hint'    => "insert data is empty.",
                'level'   => 'error',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
            'E003' => [
                'message' => 'Failed to upload logo.',
                'hint'    => 'Please ensure the file is valid and try again. If the issue persists, contact the administrator.',
                'level'   => 'error',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
            'E004' => [
                'message' => 'Invalid logo data.',
                'hint'    => 'The file name is missing or not detected. Please select a file and try again.',
                'level'   => 'error',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
        ],
    ],

    // ===================== UNIVERSITY COURSE FEE =====================
    'UCFE' => [
        'DTL' => [ // Error function detailed()
            'E001' => [
                'message' => 'The data you are looking for was not found',
                'hint'    => 'The data you are looking for does not exist in the database.',
                'level'   => 'warning',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
            'E002' => [
                'message' => 'A system error has occurred. Please report the problem to the administrator and check the system again.',
                'hint'    => 'Query error.',
                'level'   => 'error',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
            'E003' => [
                'message' => 'A system error has occurred. Please report the problem to the administrator and check the system again.',
                'hint'    => 'ID and WhereClaus are empty.',
                'level'   => 'warning',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
        ],
        'PTC' => [ // Error in model 'course_fees()' and in patch section
            'E001' => [
                'message' => 'The data is up to date.',
                'hint'    => '',
                'level'   => 'info',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ]
        ],
        'DEL' => [ // Error in model 'course_fees()' and in delete section
            'E001' => [
                'message' => 'A system error has occurred. Please report the problem to the administrator and check the system again.',
                'hint'    => 'Query error.',
                'level'   => 'error',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
            'E002' => [
                'message' => 'A system error has occurred. Please report the problem to the administrator and check the system again.',
                'hint'    => 'Empty ID.',
                'level'   => 'error',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
        ],
        'PDL' => [ // Error in model 'course_fees()' and in delete section
            'E001' => [
                'message' => 'A system error has occurred. Please report the problem to the administrator and check the system again.',
                'hint'    => 'Query error.',
                'level'   => 'error',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
            'E002' => [
                'message' => 'A system error has occurred. Please report the problem to the administrator and check the system again.',
                'hint'    => 'Empty ID.',
                'level'   => 'error',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
        ],
        'RST' => [ // Error in model 'course_fees()' and in restore section
            'E001' => [
                'message' => 'A system error has occurred. Please report the problem to the administrator and check the system again.',
                'hint'    => 'Empty ID.',
                'level'   => 'error',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
            'E002' => [
                'message' => 'Sorry, the data you are restoring already exists. Do you still want to replace it?',
                'hint'    => 'Duplicate data.',
                'level'   => 'question',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
        ],
        'CNU' => [ // Error model function create_and_edit()
            'E001' => [
                'message' => 'Sorry, the data you entered already exists.',
                'hint'    => "The data entered already exists in the database, search based on the 'test' column",
                'level'   => 'warning',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
            'E002' => [
                'message' => 'A system error has occurred. Please report the problem to the administrator and check the system again.',
                'hint'    => "insert data is empty.",
                'level'   => 'error',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
        ],
    ],

    // ===================== LEAD =====================
    'LEAD' => [
        'DTL' => [ // Error function detailed()
            'E001' => [
                'message' => 'The data you are looking for was not found',
                'hint'    => 'The data you are looking for does not exist in the database.',
                'level'   => 'warning',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
            'E002' => [
                'message' => 'A system error has occurred. Please report the problem to the administrator and check the system again.',
                'hint'    => 'Query error.',
                'level'   => 'error',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
            'E003' => [
                'message' => 'A system error has occurred. Please report the problem to the administrator and check the system again.',
                'hint'    => 'ID and WhereClaus are empty.',
                'level'   => 'warning',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
        ],
        'PTC' => [ // Error in model 'leads()' and in patch section
            'E001' => [
                'message' => 'The data is up to date.',
                'hint'    => '',
                'level'   => 'info',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ]
        ],
        'DEL' => [ // Error in model 'leads()' and in delete section
            'E001' => [
                'message' => 'A system error has occurred. Please report the problem to the administrator and check the system again.',
                'hint'    => 'Query error.',
                'level'   => 'error',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
            'E002' => [
                'message' => 'A system error has occurred. Please report the problem to the administrator and check the system again.',
                'hint'    => 'Empty ID.',
                'level'   => 'error',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
        ],
        'PDL' => [ // Error in model 'leads()' and in delete section
            'E001' => [
                'message' => 'A system error has occurred. Please report the problem to the administrator and check the system again.',
                'hint'    => 'Query error.',
                'level'   => 'error',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
            'E002' => [
                'message' => 'A system error has occurred. Please report the problem to the administrator and check the system again.',
                'hint'    => 'Empty ID.',
                'level'   => 'error',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
        ],
        'RST' => [ // Error in model 'leads()' and in restore section
            'E001' => [
                'message' => 'A system error has occurred. Please report the problem to the administrator and check the system again.',
                'hint'    => 'Empty ID.',
                'level'   => 'error',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
            'E002' => [
                'message' => 'Sorry, the data you are restoring already exists. Do you still want to replace it?',
                'hint'    => 'Duplicate data.',
                'level'   => 'question',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
        ],
        'CNU' => [ // Error model function create_and_edit()
            'E001' => [
                'message' => 'Sorry, the data you entered already exists.',
                'hint'    => "The data entered already exists in the database, search based on the 'test' column",
                'level'   => 'warning',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
            'E002' => [
                'message' => 'A system error has occurred. Please report the problem to the administrator and check the system again.',
                'hint'    => "insert data is empty.",
                'level'   => 'error',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
            'E003' => [
                'message' => 'Failed to upload document.',
                'hint'    => 'Please ensure the file is valid and try again. If the issue persists, contact the administrator.',
                'level'   => 'error',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
            'E004' => [
                'message' => 'Invalid document data.',
                'hint'    => 'The file name is missing or not detected. Please select a file and try again.',
                'level'   => 'error',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
        ],
    ],

    // ===================== LEAD SOURCE =====================
    'LDSC' => [
        'DTL' => [ // Error function detailed()
            'E001' => [
                'message' => 'The data you are looking for was not found',
                'hint'    => 'The data you are looking for does not exist in the database.',
                'level'   => 'warning',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
            'E002' => [
                'message' => 'A system error has occurred. Please report the problem to the administrator and check the system again.',
                'hint'    => 'Query error.',
                'level'   => 'error',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
            'E003' => [
                'message' => 'A system error has occurred. Please report the problem to the administrator and check the system again.',
                'hint'    => 'ID and WhereClaus are empty.',
                'level'   => 'warning',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
        ],
        'PTC' => [ // Error in model 'leads_sources()' and in patch section
            'E001' => [
                'message' => 'The data is up to date.',
                'hint'    => '',
                'level'   => 'info',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ]
        ],
        'DEL' => [ // Error in model 'leads_sources()' and in delete section
            'E001' => [
                'message' => 'A system error has occurred. Please report the problem to the administrator and check the system again.',
                'hint'    => 'Query error.',
                'level'   => 'error',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
            'E002' => [
                'message' => 'A system error has occurred. Please report the problem to the administrator and check the system again.',
                'hint'    => 'Empty ID.',
                'level'   => 'error',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
        ],
        'PDL' => [ // Error in model 'leads_sources()' and in delete section
            'E001' => [
                'message' => 'A system error has occurred. Please report the problem to the administrator and check the system again.',
                'hint'    => 'Query error.',
                'level'   => 'error',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
            'E002' => [
                'message' => 'A system error has occurred. Please report the problem to the administrator and check the system again.',
                'hint'    => 'Empty ID.',
                'level'   => 'error',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
        ],
        'RST' => [ // Error in model 'leads_sources()' and in restore section
            'E001' => [
                'message' => 'A system error has occurred. Please report the problem to the administrator and check the system again.',
                'hint'    => 'Empty ID.',
                'level'   => 'error',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
            'E002' => [
                'message' => 'Sorry, the data you are restoring already exists. Do you still want to replace it?',
                'hint'    => 'Duplicate data.',
                'level'   => 'question',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
        ],
        'CNU' => [ // Error model function create_and_edit()
            'E001' => [
                'message' => 'Sorry, the data you entered already exists.',
                'hint'    => "The data entered already exists in the database, search based on the 'test' column",
                'level'   => 'warning',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
            'E002' => [
                'message' => 'A system error has occurred. Please report the problem to the administrator and check the system again.',
                'hint'    => "insert data is empty.",
                'level'   => 'error',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
        ],
    ],

    // ===================== PAYMENT =====================
    'PYMT' => [
        'DTL' => [ // Error function detailed()
            'E001' => [
                'message' => 'The data you are looking for was not found',
                'hint'    => 'The data you are looking for does not exist in the database.',
                'level'   => 'warning',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
            'E002' => [
                'message' => 'A system error has occurred. Please report the problem to the administrator and check the system again.',
                'hint'    => 'Query error.',
                'level'   => 'error',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
            'E003' => [
                'message' => 'A system error has occurred. Please report the problem to the administrator and check the system again.',
                'hint'    => 'ID and WhereClaus are empty.',
                'level'   => 'warning',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
        ],
        'PTC' => [ // Error in model 'payments()' and in patch section
            'E001' => [
                'message' => 'The data is up to date.',
                'hint'    => '',
                'level'   => 'info',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ]
        ],
        'DEL' => [ // Error in model 'payments()' and in delete section
            'E001' => [
                'message' => 'A system error has occurred. Please report the problem to the administrator and check the system again.',
                'hint'    => 'Query error.',
                'level'   => 'error',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
            'E002' => [
                'message' => 'A system error has occurred. Please report the problem to the administrator and check the system again.',
                'hint'    => 'Empty ID.',
                'level'   => 'error',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
        ],
        'PDL' => [ // Error in model 'payments()' and in delete section
            'E001' => [
                'message' => 'A system error has occurred. Please report the problem to the administrator and check the system again.',
                'hint'    => 'Query error.',
                'level'   => 'error',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
            'E002' => [
                'message' => 'A system error has occurred. Please report the problem to the administrator and check the system again.',
                'hint'    => 'Empty ID.',
                'level'   => 'error',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
        ],
        'RST' => [ // Error in model 'payments()' and in restore section
            'E001' => [
                'message' => 'A system error has occurred. Please report the problem to the administrator and check the system again.',
                'hint'    => 'Empty ID.',
                'level'   => 'error',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
            'E002' => [
                'message' => 'Sorry, the data you are restoring already exists. Do you still want to replace it?',
                'hint'    => 'Duplicate data.',
                'level'   => 'question',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
        ],
        'CNU' => [ // Error model function create_and_edit()
            'E001' => [
                'message' => 'Sorry, the data you entered already exists.',
                'hint'    => "The data entered already exists in the database, search based on the 'test' column",
                'level'   => 'warning',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
            'E002' => [
                'message' => 'A system error has occurred. Please report the problem to the administrator and check the system again.',
                'hint'    => "insert data is empty.",
                'level'   => 'error',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
        ],
    ],

    // ===================== PAYMENT METHOD =====================
    'PMTD' => [
        'DTL' => [ // Error function detailed()
            'E001' => [
                'message' => 'The data you are looking for was not found',
                'hint'    => 'The data you are looking for does not exist in the database.',
                'level'   => 'warning',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
            'E002' => [
                'message' => 'A system error has occurred. Please report the problem to the administrator and check the system again.',
                'hint'    => 'Query error.',
                'level'   => 'error',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
            'E003' => [
                'message' => 'A system error has occurred. Please report the problem to the administrator and check the system again.',
                'hint'    => 'ID and WhereClaus are empty.',
                'level'   => 'warning',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
        ],
        'PTC' => [ // Error in model 'payment_method()' and in patch section
            'E001' => [
                'message' => 'The data is up to date.',
                'hint'    => '',
                'level'   => 'info',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ]
        ],
        'DEL' => [ // Error in model 'payment_method()' and in delete section
            'E001' => [
                'message' => 'A system error has occurred. Please report the problem to the administrator and check the system again.',
                'hint'    => 'Query error.',
                'level'   => 'error',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
            'E002' => [
                'message' => 'A system error has occurred. Please report the problem to the administrator and check the system again.',
                'hint'    => 'Empty ID.',
                'level'   => 'error',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
        ],
        'PDL' => [ // Error in model 'payment_method()' and in delete section
            'E001' => [
                'message' => 'A system error has occurred. Please report the problem to the administrator and check the system again.',
                'hint'    => 'Query error.',
                'level'   => 'error',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
            'E002' => [
                'message' => 'A system error has occurred. Please report the problem to the administrator and check the system again.',
                'hint'    => 'Empty ID.',
                'level'   => 'error',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
        ],
        'RST' => [ // Error in model 'payment_method()' and in restore section
            'E001' => [
                'message' => 'A system error has occurred. Please report the problem to the administrator and check the system again.',
                'hint'    => 'Empty ID.',
                'level'   => 'error',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
            'E002' => [
                'message' => 'Sorry, the data you are restoring already exists. Do you still want to replace it?',
                'hint'    => 'Duplicate data.',
                'level'   => 'question',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
        ],
        'CNU' => [ // Error model function create_and_edit()
            'E001' => [
                'message' => 'Sorry, the data you entered already exists.',
                'hint'    => "The data entered already exists in the database, search based on the 'test' column",
                'level'   => 'warning',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
            'E002' => [
                'message' => 'A system error has occurred. Please report the problem to the administrator and check the system again.',
                'hint'    => "insert data is empty.",
                'level'   => 'error',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
        ],
    ],

    // ===================== PAYMENT INVOICE =====================
    'INVC' => [
        'DTL' => [ // Error function detailed()
            'E001' => [
                'message' => 'The data you are looking for was not found',
                'hint'    => 'The data you are looking for does not exist in the database.',
                'level'   => 'warning',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
            'E002' => [
                'message' => 'A system error has occurred. Please report the problem to the administrator and check the system again.',
                'hint'    => 'Query error.',
                'level'   => 'error',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
            'E003' => [
                'message' => 'A system error has occurred. Please report the problem to the administrator and check the system again.',
                'hint'    => 'ID and WhereClaus are empty.',
                'level'   => 'warning',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
        ],
        'PTC' => [ // Error in model 'invoices()' and in patch section
            'E001' => [
                'message' => 'The data is up to date.',
                'hint'    => '',
                'level'   => 'info',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ]
        ],
        'DEL' => [ // Error in model 'invoices()' and in delete section
            'E001' => [
                'message' => 'A system error has occurred. Please report the problem to the administrator and check the system again.',
                'hint'    => 'Query error.',
                'level'   => 'error',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
            'E002' => [
                'message' => 'A system error has occurred. Please report the problem to the administrator and check the system again.',
                'hint'    => 'Empty ID.',
                'level'   => 'error',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
        ],
        'PDL' => [ // Error in model 'invoices()' and in delete section
            'E001' => [
                'message' => 'A system error has occurred. Please report the problem to the administrator and check the system again.',
                'hint'    => 'Query error.',
                'level'   => 'error',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
            'E002' => [
                'message' => 'A system error has occurred. Please report the problem to the administrator and check the system again.',
                'hint'    => 'Empty ID.',
                'level'   => 'error',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
        ],
        'RST' => [ // Error in model 'invoices()' and in restore section
            'E001' => [
                'message' => 'A system error has occurred. Please report the problem to the administrator and check the system again.',
                'hint'    => 'Empty ID.',
                'level'   => 'error',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
            'E002' => [
                'message' => 'Sorry, the data you are restoring already exists. Do you still want to replace it?',
                'hint'    => 'Duplicate data.',
                'level'   => 'question',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
        ],
        'CNU' => [ // Error model function create_and_edit()
            'E001' => [
                'message' => 'Sorry, the data you entered already exists.',
                'hint'    => "The data entered already exists in the database, search based on the 'invoice_number' column",
                'level'   => 'warning',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
            'E002' => [
                'message' => 'A system error has occurred. Please report the problem to the administrator and check the system again.',
                'hint'    => "insert data is empty.",
                'level'   => 'error',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
        ],
        'RIV' => [ // Error in controller function release_invoice()
            'E001' => [
                'message' => 'Sorry, student data was not found. Please check again.',
                'hint'    => "Download function failed on controller",
                'level'   => 'warning',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ]
        ]
    ],

    // ===================== STUDENT =====================
    'STDN' => [
        'DTL' => [ // Error function detailed()
            'E001' => [
                'message' => 'The data you are looking for was not found',
                'hint'    => 'The data you are looking for does not exist in the database.',
                'level'   => 'warning',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
            'E002' => [
                'message' => 'A system error has occurred. Please report the problem to the administrator and check the system again.',
                'hint'    => 'Query error.',
                'level'   => 'error',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
            'E003' => [
                'message' => 'A system error has occurred. Please report the problem to the administrator and check the system again.',
                'hint'    => 'ID and WhereClaus are empty.',
                'level'   => 'warning',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
        ],
        'PTC' => [ // Error in model 'students()' and in patch section
            'E001' => [
                'message' => 'The data is up to date.',
                'hint'    => '',
                'level'   => 'info',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ]
        ],
        'DEL' => [ // Error in model 'students()' and in delete section
            'E001' => [
                'message' => 'A system error has occurred. Please report the problem to the administrator and check the system again.',
                'hint'    => 'Query error.',
                'level'   => 'error',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
            'E002' => [
                'message' => 'A system error has occurred. Please report the problem to the administrator and check the system again.',
                'hint'    => 'Empty ID.',
                'level'   => 'error',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
        ],
        'PDL' => [ // Error in model 'students()' and in delete section
            'E001' => [
                'message' => 'A system error has occurred. Please report the problem to the administrator and check the system again.',
                'hint'    => 'Query error.',
                'level'   => 'error',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
            'E002' => [
                'message' => 'A system error has occurred. Please report the problem to the administrator and check the system again.',
                'hint'    => 'Empty ID.',
                'level'   => 'error',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
        ],
        'RST' => [ // Error in model 'students()' and in restore section
            'E001' => [
                'message' => 'A system error has occurred. Please report the problem to the administrator and check the system again.',
                'hint'    => 'Empty ID.',
                'level'   => 'error',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
            'E002' => [
                'message' => 'Sorry, the data you are restoring already exists. Do you still want to replace it?',
                'hint'    => 'Duplicate data.',
                'level'   => 'question',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
        ],
        'CNU' => [ // Error model function create_and_edit()
            'E001' => [
                'message' => 'Sorry, the data you entered already exists.',
                'hint'    => "The data entered already exists in the database, search based on the 'test' column",
                'level'   => 'warning',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
            'E002' => [
                'message' => 'A system error has occurred. Please report the problem to the administrator and check the system again.',
                'hint'    => "insert data is empty.",
                'level'   => 'error',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
            'E003' => [
                'message' => 'Failed to upload document.',
                'hint'    => 'Please ensure the file is valid and try again. If the issue persists, contact the administrator.',
                'level'   => 'error',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
            'E004' => [
                'message' => 'Invalid document data.',
                'hint'    => 'The file name is missing or not detected. Please select a file and try again.',
                'level'   => 'error',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
        ],
        'URP' => [ // Error function university_report()
            'E001' => [
                'message' => 'Report failed to generate. Please try again later, or contact the administrators.',
                'hint'    => 'Failed to generate excel report on controller.',
                'level'   => 'error',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
            'E002' => [
                'message' => 'The data searched is empty.',
                'hint'    => 'The student data you are looking for does not exist in the database.',
                'level'   => 'error',
                'cobtn' => FALSE,
                'cotext' => null,
                'cabtn' => TRUE,
                'catext' => 'OK',
                'redirectUrl' => null
            ],
        ],
    ],
];
