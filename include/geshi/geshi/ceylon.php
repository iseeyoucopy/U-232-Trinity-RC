<?php
/*************************************************************************************
 * ceylon.php
 * ----------
 * Author: Lucas Werkmeister (mail@lucaswerkmeister.de)
 * Copyright: (c) 2015 Lucas Werkmeister (http://lucaswerkmeister.de)
 * Release Version: 1.0.9.1
 * Date Started: 2015-01-08
 *
 * Ceylon language file for GeSHi.
 *
 * CHANGES
 * -------
 *
 * TODO (updated 2015-06-19)
 * ------------------
 * * Regexes match and break help URLs, so those are commented out for now
 * * Ceylon supports nested block comments
 * * The Ceylon compiler correctly parses
 *       "\{FICTITIOUS CHARACTER WITH " IN NAME}"
 *   as a single string literal.
 *   (However, that's not really important
 *   since Unicode character names never contain quotes.)
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
    'LANG_NAME' => 'Ceylon',
    'COMMENT_SINGLE' => [1 => '//', 2 => '#!'],
    'COMMENT_MULTI' => ['/*' => '*/'],
    'COMMENT_REGEXP' => [
        /*
         * 1. regular line comments (see COMMENT_SINGLE)
         * 2. shebang line comments (see COMMENT_SINGLE)
         * 3. strings (including string templates)
         */
        3 => '/(?:"|``).*?(?:``|")/',
    ],
    'CASE_KEYWORDS' => GESHI_CAPS_NO_CHANGE,
    'QUOTEMARKS' => ["'"],
    'ESCAPE_CHAR' => '\\',
    'KEYWORDS' => [
        /*
         * 1. lexer keywords (class, else, etc.)
         * 2. language modifiers (shared, formal, etc.)
         * 3. language doc modifiers (doc, see, etc.)
         */
        1 => [
            'assembly',
            'module',
            'package',
            'import',
            'alias',
            'class',
            'interface',
            'object',
            'given',
            'value',
            'assign',
            'void',
            'function',
            'new',
            'of',
            'extends',
            'satisfies',
            'abstracts',
            'in',
            'out',
            'return',
            'break',
            'continue',
            'throw',
            'assert',
            'dynamic',
            'if',
            'else',
            'switch',
            'case',
            'for',
            'while',
            'try',
            'catch',
            'finally',
            'then',
            'let',
            'this',
            'outer',
            'super',
            'is',
            'exists',
            'nonempty',
        ],
        2 => [
            'shared',
            'abstract',
            'formal',
            'default',
            'actual',
            'variable',
            'late',
            'native',
            'deprecated',
            'final',
            'sealed',
            'annotation',
            'small',
        ],
        3 => [
            'doc',
            'by',
            'license',
            'see',
            'throws',
            'tagged',
        ],
    ],
    'SYMBOLS' => [
        ',',
        ';',
        '...',
        '{',
        '}',
        '[',
        ']',
        '`',
        '?.',
        '*.',
        '?',
        '-&gt;',
        '=&gt;',
        '**',
        '++',
        '--',
        '..',
        ':',
        '&&',
        '||',
        '+=',
        '-=',
        '*=',
        '/=',
        '%=',
        '|=',
        '&=',
        '~=',
        '||=',
        '&&=',
        '+',
        '-',
        '*',
        '/',
        '%',
        '^',
        '~',
        '&',
        '|',
        '===',
        '==',
        '=',
        '!=',
        '!',
        '&lt;=&gt;',
        '&lt;=',
        '&gt;=',
        '&lt;',
        '&gt;',
        '.',
    ],
    'STYLES' => [
        'KEYWORDS' => [
            1 => 'font-weight:bold;color:#4C4C4C;',
            2 => 'color:#39C',
            3 => 'color:#39C',
        ],
        'COMMENTS' => [
            1 => 'color:darkgray;',
            2 => 'color:darkgray;',
            3 => 'color:blue',
            'MULTI' => 'color:darkgray;',
        ],
        'STRINGS' => [
            0 => 'color:blue;',
        ],
        'REGEXPS' => [
            1 => 'color:#639;',
            2 => 'color:#039;',
            3 => 'color:#906;',
        ],
        'ESCAPE_CHAR' => [],
        'BRACKETS' => [],
        'NUMBERS' => [],
        'METHODS' => [],
        'SYMBOLS' => [],
        'SCRIPT' => [],
    ],
    'REGEXPS' => [
        /*
         * 1. qualified lidentifiers
         * 2. lidentifiers
         * 3. uidentifiers
         *
         * All of these contain various lookahead and -behind to ensure
         * that we don't match various stuff that GeSHi escapes
         * (for instance, we see semicolons as <SEMI>).
         */
        1 => [
            GESHI_SEARCH => '\\b((\?|\*)?\.[[:space:]]*)([[:lower:]][[:alnum:]]*|\\\\i[[:alnum:]]*)\\b',
            GESHI_REPLACE => '\\3',
            GESHI_MODIFIERS => '',
            GESHI_BEFORE => '\\1',
            GESHI_AFTER => '',
        ],
        2 => [
            GESHI_SEARCH => '(?<![|<>&![:alnum:]])([[:lower:]][[:alnum:]]*|\\\\i[[:alnum:]]*)(?![>[:alnum:]])',
            GESHI_REPLACE => '\\1',
            GESHI_MODIFIERS => '',
            GESHI_BEFORE => '',
            GESHI_AFTER => '',
        ],
        3 => [
            GESHI_SEARCH => '(?<![|<>&![:alnum:]])([[:upper:]][[:alnum:]]*|\\\\I[[:alnum:]]*)(?![>[:alnum:]])',
            GESHI_REPLACE => '\\1',
            GESHI_MODIFIERS => '',
            GESHI_BEFORE => '',
            GESHI_AFTER => '',
        ],
    ],
    'OOLANG' => false,
    'OBJECT_SPLITTERS' => [],
    'URLS' => [
        1 => '',
        2 => '',
        3 => '' // the real URLs are commented out because syntax highlighting breaks them
//      2 => 'https://modules.ceylon-lang.org/repo/1/ceylon/language/1.1.0/module-doc/api/index.html#{FNAME}',
//      3 => 'https://modules.ceylon-lang.org/repo/1/ceylon/language/1.1.0/module-doc/api/index.html#{FNAME}',
    ],
    'CASE_SENSITIVE' => [1 => true, 2 => true, 3 => true],
    'STRICT_MODE_APPLIES' => GESHI_NEVER,
    'SCRIPT_DELIMITERS' => [],
    'HIGHLIGHT_STRICT_BLOCK' => [],
];
