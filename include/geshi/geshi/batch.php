<?php
/*************************************************************************************
 * batch.php
 * ------------
 * Author: FraidZZ ( fraidzz [@] bk.ru )
 * Copyright: (c) 2015 FraidZZ ( http://vk.com/fraidzz , http://www.cyberforum.ru/members/340557.html )
 * Release Version: 1.0.9.1
 * Date Started: 2015/03/28
 *
 * Windows batch file language file for GeSHi.
 *
 *************************************************************************************
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
 ************************************************************************************/

$language_data = [
    'LANG_NAME' => 'Windows Batch file',
    'COMMENT_SINGLE' => [],
    'COMMENT_MULTI' => [],
    'COMMENT_REGEXP' => [
        100 => '/(?:^|[&|])\\s*(?:rem|::)[^\\n]*/msi',
        101 => '/[\\/-]\\S*/si',
        102 => '/^\s*:[^:]\\S*/msi',
        103 => '/(?:([%!])[^"\'~ ][^"\' ]*\\1|%%?(?:~[dpnxsatz]*)?[^"\'])/si',
    ],
    'CASE_KEYWORDS' => GESHI_CAPS_NO_CHANGE,
    'QUOTEMARKS' => ["'", '"'],
    'ESCAPE_CHAR' => '',
    'ESCAPE_REGEXP' => [
        100 => '/(?:([%!])\\S+\\1|%%(?:~[dpnxsatz]*)?[^"\'])/si',
    ],
    'KEYWORDS' => [
        1 => [
            'echo',
            'set',
            'for',
            'if',
            'exit',
            'else',
            'do',
            'not',
            'defined',
            'exist',
        ],
        2 => [
            "ASSOC",
            "ATTRIB",
            "BREAK",
            "BCDEDIT",
            "CACLS",
            "CD",
            "CHCP",
            "CHDIR",
            "CHKDSK",
            "CHKNTFS",
            "CLS",
            "CMD",
            "COLOR",
            "COMP",
            "COMPACT",
            "CONVERT",
            "COPY",
            "DATE",
            "DEL",
            "DIR",
            "DISKCOMP",
            "DISKCOPY",
            "DISKPART",
            "DOSKEY",
            "DRIVERQUERY",
            "ECHO",
            "ENDLOCAL",
            "ERASE",
            "EXIT",
            "FC",
            "FIND",
            "FINDSTR",
            "FOR",
            "FORMAT",
            "FSUTIL",
            "FTYPE",
            "GPRESULT",
            "GRAFTABL",
            "HELP",
            "ICACLS",
            "IF",
            "LABEL",
            "MD",
            "MKDIR",
            "MKLINK",
            "MODE",
            "MORE",
            "MOVE",
            "OPENFILES",
            "PATH",
            "PAUSE",
            "POPD",
            "PRINT",
            "PROMPT",
            "PUSHD",
            "RD",
            "RECOVER",
            "REN",
            "RENAME",
            "REPLACE",
            "RMDIR",
            "ROBOCOPY",
            "SET",
            "SETLOCAL",
            "SC",
            "SCHTASKS",
            "SHIFT",
            "SHUTDOWN",
            "SORT",
            "START",
            "SUBST",
            "SYSTEMINFO",
            "TASKLIST",
            "TASKKILL",
            "TIME",
            "TITLE",
            "TREE",
            "TYPE",
            "VER",
            "VERIFY",
            "VOL",
            "XCOPY",
            "WMIC",
            "CSCRIPT",
        ],
        3 => [
            "enabledelayedexpansion",
            "enableextensions",
        ],
    ],
    'SYMBOLS' => [
        '(',
        ')',
        '+',
        '-',
        '~',
        '^',
        '@',
        '&',
        '*',
        '|',
        '/',
        '<',
        '>',
    ],
    'CASE_SENSITIVE' => [
        GESHI_COMMENTS => false,
        1 => false,
        2 => false,
        3 => false,
    ],
    'STYLES' => [
        'KEYWORDS' => [
            1 => 'color: #800080; font-weight: bold;',
            2 => 'color: #0080FF; font-weight: bold;',
            3 => 'color: #0000FF; font-weight: bold;',
        ],
        'COMMENTS' => [
            101 => 'color: #44aa44; font-weight: bold;',
            100 => 'color: #888888;',
            102 => 'color: #990000; font-weight: bold;',
            103 => 'color: #000099; font-weight: bold;',
            'MULTI' => 'color: #808080; font-style: italic;',
        ],
        'ESCAPE_CHAR' => [
            100 => 'color: #000099; font-weight: bold;',
        ],
        'BRACKETS' => [
            0 => 'color: #66cc66; font-weight: bold;',
        ],
        'STRINGS' => [
            0 => 'color: #ff0000;',
        ],
        'NUMBERS' => [
            0 => 'color: #cc66cc;',
        ],
        'METHODS' => [
            0 => 'color: #006600;',
        ],
        'SYMBOLS' => [
            0 => 'color: #44aa44; font-weight: bold;',
        ],
        'REGEXPS' => [
            0 => 'color: #990000; font-weight: bold',
            1 => 'color: #800080; font-weight: bold;',
        ],
        'SCRIPT' => [],
    ],
    'URLS' => [
        1 => '',
        2 => '',
        3 => '',
    ],
    'OOLANG' => false,
    'OBJECT_SPLITTERS' => [],
    'REGEXPS' => [
        0 => [
            GESHI_SEARCH => "((?:goto|call)\\s*)(\\S+)",
            GESHI_REPLACE => "\\2",
            GESHI_BEFORE => "\\1",
            GESHI_MODIFIERS => "si",
            GESHI_AFTER => "",
        ],
        1 => "goto|call",
    ],
    'STRICT_MODE_APPLIES' => GESHI_MAYBE,
    'SCRIPT_DELIMITERS' => [],
    'HIGHLIGHT_STRICT_BLOCK' => [],
];
