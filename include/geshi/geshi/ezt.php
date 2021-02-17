<?php
/*************************************************************************************
 * ezt.php
 * -----------
 * Author: Ramesh Vishveshwar (ramesh.vishveshwar@gmail.com)
 * Copyright: (c) 2012 Ramesh Vishveshwar (http://thecodeisclear.in)
 * Release Version: 1.0.9.1
 * Date Started: 2012/09/01
 *
 * Easytrieve language file for GeSHi.
 *
 * CHANGES
 * -------
 * 2012/09/22 (1.0.0)
 *   - First Release
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
    'LANG_NAME' => 'EZT',
    'COMMENT_SINGLE' => [],
    'COMMENT_MULTI' => [],
    'CASE_KEYWORDS' => GESHI_CAPS_UPPER,
    'COMMENT_REGEXP' => [
        // First character of the line is an asterisk. Rest of the line is spaces/null
        0 => '/\*(\s|\D)?(\n)/',
        // Asterisk followed by any character & then a non numeric character.
        // This is to prevent expressions such as 25 * 4 from being marked as a comment
        // Note: 25*4 - 100 will mark *4 - 100 as a comment. Pls. space out expressions
        // In any case, 25*4 will result in an Easytrieve error
        1 => '/\*.([^0-9\n])+.*(\n)/',
    ],
    'QUOTEMARKS' => ["'", '"'],
    'ESCAPE_CHAR' => '',
    'KEYWORDS' => [
        1 => [
            'CONTROL',
            'DEFINE',
            'DISPLAY',
            'DO',
            'ELSE',
            'END-DO',
            'END-IF',
            'END-PROC',
            'FILE',
            'GET',
            'GOTO',
            'HEADING',
            'IF',
            'JOB',
            'LINE',
            'PARM',
            'PERFORM',
            'POINT',
            'PRINT',
            'PROC',
            'PUT',
            'READ',
            'RECORD',
            'REPORT',
            'RETRIEVE',
            'SEARCH',
            'SELECT',
            'SEQUENCE',
            'SORT',
            'STOP',
            'TITLE',
            'WRITE',
        ],
        // Procedure Keywords (Names of specific procedures)
        2 => [
            'AFTER-BREAK',
            'AFTER-LINE',
            'BEFORE-BREAK',
            'BEFORE-LINE',
            'ENDPAGE',
            'REPORT-INPUT',
            'TERMINATION',
        ],
        // Macro names, Parameters
        3 => [
            'COMPILE',
            'CONCAT',
            'DESC',
            'GETDATE',
            'MASK',
            'PUNCH',
            'VALUE',
            'SYNTAX',
            'NEWPAGE',
            'SKIP',
            'COL',
            'TALLY',
            'WITH',
        ],
    ],
    'SYMBOLS' => [
        '(',
        ')',
        '=',
        '&',
        ',',
        '*',
        '>',
        '<',
        '%',
    ],
    'CASE_SENSITIVE' => [
        GESHI_COMMENTS => false,
        1 => false,
        2 => false,
        3 => false
        //4 => false,
    ],
    'STYLES' => [
        'KEYWORDS' => [
            1 => 'color: #FF0000;',
            2 => 'color: #21A502;',
            3 => 'color: #FF00FF;',
        ],
        'COMMENTS' => [
            0 => 'color: #0000FF; font-style: italic;',
            1 => 'color: #0000FF; font-style: italic;',
        ],
        'ESCAPE_CHAR' => [
            0 => '',
        ],
        'BRACKETS' => [
            0 => 'color: #FF7400;',
        ],
        'STRINGS' => [
            0 => 'color: #66CC66;',
        ],
        'NUMBERS' => [
            0 => 'color: #736205;',
        ],
        'METHODS' => [
            1 => '',
            2 => '',
        ],
        'SYMBOLS' => [
            0 => 'color: #FF7400;',
        ],
        'REGEXPS' => [
            0 => 'color: #E01B6A;',
        ],
        'SCRIPT' => [
            0 => '',
        ],
    ],
    'URLS' => [
        1 => '',
        2 => '',
        3 => '',
    ],
    'OOLANG' => false,
    'OBJECT_SPLITTERS' => [],
    'REGEXPS' => [
        // We are trying to highlight Macro names here which preceded by %
        0 => '(%)([a-zA-Z0-9])+(\s|\n)',
    ],
    'STRICT_MODE_APPLIES' => GESHI_NEVER,
    'SCRIPT_DELIMITERS' => [
    ],
    'HIGHLIGHT_STRICT_BLOCK' => [],
];
