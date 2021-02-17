<?php
/*************************************************************************************
 * dart.php
 * --------
 * Author: Edward Hart (edward.dan.hart@gmail.com)
 * Copyright: (c) 2013 Edward Hart
 * Release Version: 1.0.9.1
 * Date Started: 2013/10/25
 *
 * Dart language file for GeSHi.
 *
 * CHANGES
 * -------
 * 2013/10/25
 *   -  First Release
 *
 * TODO (updated 2013/10/25)
 * -------------------------
 *   -  Highlight standard library types.
 *
 *************************************************************************************
 *
 *     This file is part of GeSHi.
 *
 *   GeSHi is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 *   GeSHi is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *   GNU General Public License for more details.
 *
 *   You should have received a copy of the GNU General Public License
 *   along with GeSHi; if not, write to the Free Software
 *   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 *
 ************************************************************************************/

$language_data = [
    'LANG_NAME' => 'Dart',

    'COMMENT_SINGLE' => ['//'],
    'COMMENT_MULTI' => ['/*' => '*/'],
    'COMMENT_REGEXP' => [],

    'CASE_KEYWORDS' => GESHI_CAPS_NO_CHANGE,
    'QUOTEMARKS' => ["'", '"'],
    'ESCAPE_CHAR' => '',
    'ESCAPE_REGEXP' => [
        //Simple Single Char Escapes
        1 => "#\\\\[\\\\nrfbtv\'\"?\n]#i",
        //Hexadecimal Char Specs
        2 => "#\\\\x[\da-fA-F]{2}#",
        //Hexadecimal Char Specs
        3 => "#\\\\u[\da-fA-F]{4}#",
        4 => "#\\\\u\\{[\da-fA-F]*\\}#",
    ],
    'NUMBERS' =>
        GESHI_NUMBER_INT_BASIC | GESHI_NUMBER_INT_CSTYLE |
        GESHI_NUMBER_HEX_PREFIX | GESHI_NUMBER_FLT_NONSCI |
        GESHI_NUMBER_FLT_NONSCI_F | GESHI_NUMBER_FLT_SCI_SHORT | GESHI_NUMBER_FLT_SCI_ZERO,

    'KEYWORDS' => [
        1 => [
            'abstract',
            'as',
            'assert',
            'break',
            'case',
            'catch',
            'class',
            'const',
            'continue',
            'default',
            'do',
            'dynamic',
            'else',
            'export',
            'extends',
            'external',
            'factory',
            'false',
            'final',
            'finally',
            'for',
            'get',
            'if',
            'implements',
            'import',
            'in',
            'is',
            'library',
            'new',
            'null',
            'operator',
            'part',
            'return',
            'set',
            'static',
            'super',
            'switch',
            'this',
            'throw',
            'true',
            'try',
            'typedef',
            'var',
            'while',
            'with',
        ],
        2 => [
            'double',
            'bool',
            'int',
            'num',
            'void',
        ],
    ],

    'SYMBOLS' => [
        0 => ['(', ')', '{', '}', '[', ']'],
        1 => ['+', '-', '*', '/', '%', '~'],
        2 => ['&', '|', '^'],
        3 => ['=', '!', '<', '>'],
        4 => ['?', ':'],
        5 => ['..'],
        6 => [';', ','],
    ],

    'CASE_SENSITIVE' => [
        GESHI_COMMENTS => false,
        1 => true,
        2 => true,
    ],

    'STYLES' => [
        'KEYWORDS' => [
            1 => 'font-weight: bold;',
            2 => 'color: #445588; font-weight: bold;',
        ],
        'COMMENTS' => [
            0 => 'color: #999988; font-style: italic;',
            'MULTI' => 'color: #999988; font-style: italic;',
        ],
        'ESCAPE_CHAR' => [
            0 => 'color: #000099; font-weight: bold;',
            1 => 'color: #000099; font-weight: bold;',
            2 => 'color: #660099; font-weight: bold;',
            3 => 'color: #660099; font-weight: bold;',
            4 => 'color: #660099; font-weight: bold;',
            5 => 'color: #006699; font-weight: bold;',
            'HARD' => '',
        ],
        'STRINGS' => [
            0 => 'color: #d14;',
        ],
        'NUMBERS' => [
            0 => 'color: #009999;',
            GESHI_NUMBER_HEX_PREFIX => 'color: #208080;',
            GESHI_NUMBER_FLT_SCI_SHORT => 'color:#800080;',
            GESHI_NUMBER_FLT_SCI_ZERO => 'color:#800080;',
            GESHI_NUMBER_FLT_NONSCI_F => 'color:#800080;',
            GESHI_NUMBER_FLT_NONSCI => 'color:#800080;',
        ],
        'BRACKETS' => [''],
        'METHODS' => [
            1 => 'color: #006633;',
        ],
        'SYMBOLS' => [
            0 => 'font-weight: bold;',
            1 => 'font-weight: bold;',
            2 => 'font-weight: bold;',
            3 => 'font-weight: bold;',
            4 => 'font-weight: bold;',
            5 => 'font-weight: bold;',
            6 => 'font-weight: bold;',
        ],
        'REGEXPS' => [
        ],
        'SCRIPT' => [
        ],
    ],
    'URLS' => [
        1 => '',
        2 => '',
    ],
    'OOLANG' => true,
    'OBJECT_SPLITTERS' => [
        1 => '.',
    ],
    'REGEXPS' => [
    ],
    'STRICT_MODE_APPLIES' => GESHI_NEVER,
    'SCRIPT_DELIMITERS' => [
    ],
    'HIGHLIGHT_STRICT_BLOCK' => [
    ],
    'TAB_WIDTH' => 4,
];
