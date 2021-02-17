<?php
/*************************************************************************************
 * twig.php
 * ----------
 * Author: Keyvan Akbary (keyvan@kiwwito.com)
 * Copyright: (c) 2011 Keyvan Akbary (http://www.kiwwito.com/)
 * Release Version: 1.0.9.1
 * Date Started: 2011/12/05
 *
 * Twig template language file for GeSHi.
 *
 * CHANGES
 * -------
 * 2012/09/28 (1.9.0 by José Andrés Puertas y Javier Eguiluz)
 *   - Added new tags, filters and functions
 *   - Added regexps for variables, objects and properties
 *   - Lots of other minor tweaks (delimites, comments, ...)
 *
 * 2011/12/05 (1.0.0 by Keyvan Akbary)
 *   -  Initial Release
 *
 * TODO
 * ----
 *
 *************************************************************************************
 *
 *   This file is part of GeSHi.
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
    'LANG_NAME' => 'Twig',
    'COMMENT_SINGLE' => [],
    'COMMENT_MULTI' => ['{#' => '#}'],
    'CASE_KEYWORDS' => GESHI_CAPS_NO_CHANGE,
    'QUOTEMARKS' => ["'", '"'],
    'ESCAPE_CHAR' => '',
    'KEYWORDS' => [
        //TWIG
        //Tags
        1 => [
            'autoescape',
            'endautoescape',
            'block',
            'endblock',
            'do',
            'embed',
            'endembed',
            'extends',
            'filter',
            'endfilter',
            'for',
            'endfor',
            'from',
            'if',
            'else',
            'elseif',
            'endif',
            'import',
            'include',
            'macro',
            'endmacro',
            'raw',
            'endraw',
            'sandbox',
            'set',
            'endset',
            'spaceless',
            'endspaceless',
            'use',
            'verbatim',
            'endverbatim',
            'trans',
            'endtrans',
            'transchoice',
            'endtranschoice',
        ],
        //Filters
        2 => [
            'abs',
            'batch',
            'capitalize',
            'convert_encoding',
            'date',
            'date_modify',
            'default',
            'escape',
            'first',
            'format',
            'join',
            'json_encode',
            'keys',
            'last',
            'length',
            'lower',
            'merge',
            'nl2br',
            'number_format',
            'replace',
            'reverse',
            'slice',
            'sort',
            'split',
            'striptags',
            'title',
            'trim',
            'upper',
            'url_encode',
        ],
        //Functions
        3 => [
            'attribute',
            'cycle',
            'dump',
            'parent',
            'random',
            'range',
            'source',
            'template_from_string',
        ],
        //Tests
        4 => [
            'constant',
            'defined',
            'divisibleby',
            'empty',
            'even',
            'iterable',
            'null',
            'odd',
            'sameas',
        ],
        //Operators
        5 => [
            'in',
            'is',
            'and',
            'b-and',
            'or',
            'b-or',
            'b-xor',
            'not',
            'into',
            'starts with',
            'ends with',
            'matches',
        ],
    ],
    'SYMBOLS' => [
        '{{',
        '}}',
        '{%',
        '%}',
        '+',
        '-',
        '/',
        '/',
        '*',
        '**', //Math operators
        '==',
        '!=',
        '<',
        '>',
        '>=',
        '<=',
        '===', //Logic operators
        '..',
        '|',
        '~',
        '[',
        ']',
        '.',
        '?',
        ':',
        '(',
        ')', //Other
        '=' //HTML (attributes)
    ],
    'CASE_SENSITIVE' => [
        GESHI_COMMENTS => false,
        //Twig
        1 => true,
        2 => true,
        3 => true,
        4 => true,
        5 => true,
    ],
    'STYLES' => [
        'KEYWORDS' => [
            1 => 'color: #0600FF;', //Tags
            2 => 'color: #008000;', //Filters
            3 => 'color: #0600FF;', //Functions
            4 => 'color: #804040;', //Tests
            5 => 'color: #008000;',
        ],
        'COMMENTS' => [
            'MULTI' => 'color: #008080; font-style: italic;',
        ],
        'ESCAPE_CHAR' => [
            0 => 'color: #000099; font-weight: bold;',
        ],
        'BRACKETS' => [
            0 => 'color: #D36900;',
        ],
        'STRINGS' => [
            0 => 'color: #ff0000;',
        ],
        'NUMBERS' => [
            0 => 'color: #cc66cc;',
        ],
        'METHODS' => [
            1 => 'color: #006600;',
        ],
        'SYMBOLS' => [
            0 => 'color: #D36900;',
        ],
        'SCRIPT' => [
            0 => '',
            1 => 'color: #808080; font-style: italic;',
            2 => 'color: #009000;',
        ],
        'REGEXPS' => [
            0 => 'color: #00aaff;',
            1 => 'color: #00aaff;',
        ],
    ],
    'URLS' => [
        1 => 'http://twig.sensiolabs.org/doc/tags/{FNAMEL}.html',
        2 => 'http://twig.sensiolabs.org/doc/filters/{FNAMEL}.html',
        3 => 'http://twig.sensiolabs.org/doc/functions/{FNAMEL}.html',
        4 => 'http://twig.sensiolabs.org/doc/tests/{FNAMEL}.html',
        5 => '',
    ],
    'OOLANG' => false,
    'OBJECT_SPLITTERS' => [
        1 => '.',
    ],
    'REGEXPS' => [
        0 => [
            GESHI_SEARCH => "([[:space:]])([a-zA-Z_][a-zA-Z0-9_]*)",
            GESHI_REPLACE => '\\2',
            GESHI_MODIFIERS => '',
            GESHI_BEFORE => '\\1',
            GESHI_AFTER => '',
        ],
        1 => [
            GESHI_SEARCH => "\.([a-zA-Z_][a-zA-Z0-9_]*)",
            GESHI_REPLACE => '.\\1',
            GESHI_MODIFIERS => '',
            GESHI_BEFORE => '',
            GESHI_AFTER => '',
        ],
    ],
    'STRICT_MODE_APPLIES' => GESHI_ALWAYS,
    'SCRIPT_DELIMITERS' => [
        0 => [
            '{{' => '}}',
            '{%' => '%}',
        ],
        1 => [
            '{#' => '#}',
        ],
    ],
    'HIGHLIGHT_STRICT_BLOCK' => [
        0 => true,
        1 => true,
        2 => true,
    ],
    'PARSER_CONTROL' => [
        'KEYWORDS' => [],
    ],
];
