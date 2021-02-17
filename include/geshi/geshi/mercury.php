<?php
/*************************************************************************************
 * mercury.php
 * --------
 * Author: Sebastian Godelet (sebastian.godelet+github@gmail.com)
 * Copyright: (c) 2014
 * Release Version: 1.0.9.1
 * Date Started: 2014/10/30
 *
 * Mercury language file for GeSHi.
 *
 * CHANGES
 * -------
 * 2014/10/30 (1.0.8.13)
 *  -  First Release
 *
 * TODO (updated 2014/10/30)
 * -------------------------
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
    'LANG_NAME' => 'Mercury',
    'COMMENT_SINGLE' => [1 => '%'],
    'COMMENT_MULTI' => ['/*' => '*/'],
    'HARDQUOTE' => ["'", "'"],
    'HARDESCAPE' => ['"', "\'"],
    'HARDCHAR' => '"',
    'CASE_KEYWORDS' => GESHI_CAPS_NO_CHANGE,
    'QUOTEMARKS' => [],
    'ESCAPE_CHAR' => '\\',
    'NUMBERS' =>
        GESHI_NUMBER_INT_BASIC | GESHI_NUMBER_FLT_SCI_ZERO,
    'KEYWORDS' => [
        1 => [
            'end_module',
            'finalise',
            'finalize',
            'func',
            'implementation',
            'include_module',
            'initalisation',
            'initialization',
            'instance',
            'interface',
            'import_module',
            'module',
            'pragma',
            'pred',
            'type',
            'typeclass',
            'use_module',
        ],
        2 => [
            'atomic',
            'foreign_code',
            'foreign_export',
            'foreign_type',
            'memo',
        ],
    ],
    'SYMBOLS' => [
        0 => ['(', ')', '[', ']', '{', '}',],
        1 => ['?-', ':-', '=:='],
        2 => ['\-', '\+', '\*', '\/', '/\\'],
        3 => ['-', '+', '*', '/'],
        4 => ['.', ':', ',', ';'],
        5 => ['!', '@', '&', '|', '!.', '!:'],
        6 => ['<', '>', '='],
    ],
    'CASE_SENSITIVE' => [
        GESHI_COMMENTS => false,
        1 => false,
        2 => false,
    ],
    'STYLES' => [
        'KEYWORDS' => [
            1 => 'color: #990000;',
            2 => 'color: #99aa77;',
        ],
        'COMMENTS' => [
            1 => 'color: #666666; font-style: italic;',
            'MULTI' => 'color: #666666; font-style: italic;',
        ],
        'ESCAPE_CHAR' => [
            0 => 'color: #000099; font-weight: bold;',
            'HARD' => 'color: #000099; font-weight: bold;',
        ],
        'BRACKETS' => [
            0 => 'color: #009900;',
        ],
        'STRINGS' => [
            0 => 'color: #0000ff;',
            'HARD' => 'color: #0000ff;',
        ],
        'NUMBERS' => [
            0 => 'color: #800080;',
        ],
        'METHODS' => [],
        'SYMBOLS' => [
            0 => 'color: #339933;',
            1 => 'color: #339933;',
            2 => 'color: #339933;',
            3 => 'color: #339933;',
            4 => 'color: #339933;',
            5 => 'color: #339933;',
            6 => 'color: #339933;',
        ],
        'REGEXPS' => [
            0 => 'color: #008080;',
        ],
        'SCRIPT' => [],
    ],
    'URLS' => [
        1 => 'http://www.mercurylang.org',
        2 => 'http://www.mercurylang.org',
    ],
    'OOLANG' => false,
    'OBJECT_SPLITTERS' => [],
    'REGEXPS' => [
        //Variables
        0 => "(?<![a-zA-Z0-9_])(?!(?:PIPE|SEMI|DOT)[^a-zA-Z0-9_])[A-Z_][a-zA-Z0-9_]*(?![a-zA-Z0-9_])(?!\x7C)",
    ],
    'STRICT_MODE_APPLIES' => GESHI_NEVER,
    'SCRIPT_DELIMITERS' => [],
    'HIGHLIGHT_STRICT_BLOCK' => [],
    'TAB_WIDTH' => 4,
];
