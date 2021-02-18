<?php
/*************************************************************************************
 * vbscript.php
 * ------
 * Author: Roberto Rossi (rsoftware@altervista.org)
 * Copyright: (c) 2004 Roberto Rossi (http://rsoftware.altervista.org),
 *                     Nigel McNie (http://qbnz.com/highlighter),
 *                     Rory Prendergast (http://www.tanium.com)
 * Release Version: 1.0.9.1
 * Date Started: 2012/08/20
 *
 * VBScript language file for GeSHi.
 *
 * CHANGES
 * -------
 * 2012/08/20 (1.0.0)
 *  -  First Release
 *
 * TODO (updated 2004/11/27)
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
    'LANG_NAME' => 'VBScript',
    'COMMENT_SINGLE' => [],
    'COMMENT_MULTI' => [],
    'COMMENT_REGEXP' => [
        // Comments (either single or multiline with _
        1 => '/\'.*(?<! _)\n/sU',
    ],
    'CASE_KEYWORDS' => GESHI_CAPS_NO_CHANGE,
    'QUOTEMARKS' => ['"'],
    'ESCAPE_CHAR' => '',
    'KEYWORDS' => [
        1 => [
            'Empty',
            'Nothing',
            'Null',
            'vbArray',
            'vbBoolean',
            'vbByte',
            'vbCr',
            'vbCrLf',
            'vbCurrency',
            'vbDate',
            'vbDouble',
            'vbEmpty',
            'vbError',
            'vbFirstFourDays',
            'vbFirstFullWeek',
            'vbFirstJan1',
            'vbFormFeed',
            'vbFriday',
            'vbInteger',
            'vbLf',
            'vbLong',
            'vbMonday',
            'vbNewLine',
            'vbNull',
            'vbNullChar',
            'vbNullString',
            'vbObject',
            'vbSaturday',
            'vbSingle',
            'vbString',
            'vbSunday',
            'vbTab',
            'vbThursday',
            'vbTuesday',
            'vbUseSystem',
            'vbUseSystemDayOfWeek',
            'vbVariant',
            'vbWednesday',
            'FALSE',
            'TRUE',
        ],
        2 => [
            'bs',
            'Array',
            'Asc',
            'Atn',
            'CBool',
            'CByte',
            'CDate',
            'CDbl',
            'Chr',
            'CInt',
            'CLng',
            'Cos',
            'CreateObject',
            'CSng',
            'CStr',
            'Date',
            'DateAdd',
            'DateDiff',
            'DatePart',
            'DateSerial',
            'DateValue',
            'Day',
            'Eval',
            'Exp',
            'Filter',
            'Fix',
            'FormatDateTime',
            'FormatNumber',
            'FormatPercent',
            'GetObject',
            'Hex',
            'Hour',
            'InputBox',
            'InStr',
            'InstrRev',
            'Int',
            'IsArray',
            'IsDate',
            'IsEmpty',
            'IsNull',
            'IsNumeric',
            'IsObject',
            'Join',
            'LBound',
            'LCase',
            'Left',
            'Len',
            'Log',
            'LTrim',
            'Mid',
            'Minute',
            'Month',
            'MonthName',
            'MsgBox',
            'Now',
            'Oct',
            'Replace',
            'RGB',
            'Right',
            'Rnd',
            'Round',
            'RTrim',
            'ScriptEngine',
            'ScriptEngineBuildVersion',
            'ScriptEngineMajorVersion',
            'ScriptEngineMinorVersion',
            'Second',
            'Sgn',
            'Sin',
            'Space',
            'Split',
            'Sqr',
            'StrComp',
            'String',
            'StrReverse',
            'Tan',
            'Time',
            'TimeSerial',
            'TimeValue',
            'Trim',
            'TypeName',
            'UBound',
            'UCase',
            'VarType',
            'Weekday',
            'WeekdayName',
            'Year',
        ],
        3 => [
            'Call',
            'Case',
            'Const',
            'Dim',
            'Do',
            'Each',
            'Else',
            'End',
            'Erase',
            'Execute',
            'Exit',
            'For',
            'Function',
            'Gosub',
            'Goto',
            'If',
            'Loop',
            'Next',
            'On Error',
            'Option Explicit',
            'Private',
            'Public',
            'Randomize',
            'ReDim',
            'Rem',
            'Resume',
            'Select',
            'Set',
            'Sub',
            'Then',
            'Wend',
            'While',
            'With',
            'In',
            'To',
            'Step',
        ],
        4 => [
            'And',
            'Eqv',
            'Imp',
            'Is',
            'Mod',
            'Not',
            'Or',
            'Xor',
        ],
    ],
    'SYMBOLS' => [
        '-',
        '&',
        '*',
        '/',
        '\\',
        '^',
        '+',
        '<',
        '<=',
        '<>',
        '=',
        '>',
        '>=',
    ],
    'CASE_SENSITIVE' => [
        GESHI_COMMENTS => false,
        1 => false,
        2 => false,
        3 => false,
        4 => false,
    ],
    'STYLES' => [
        'KEYWORDS' => [
            1 => 'color: #F660AB; font-weight: bold;',
            2 => 'color: #E56717; font-weight: bold;',
            3 => 'color: #8D38C9; font-weight: bold;',
            4 => 'color: #151B8D; font-weight: bold;',
        ],
        'COMMENTS' => [
            1 => 'color: #008000;',
        ],
        'BRACKETS' => [
        ],
        'STRINGS' => [
            0 => 'color: #800000;',
        ],
        'NUMBERS' => [
        ],
        'METHODS' => [
        ],
        'SYMBOLS' => [
        ],
        'ESCAPE_CHAR' => [
            0 => 'color: #800000; font-weight: bold;',
        ],
        'SCRIPT' => [
        ],
        'REGEXPS' => [
        ],
    ],
    'URLS' => [
        1 => '',
        2 => '',
        3 => '',
        4 => '',
    ],
    'OOLANG' => false,
    'OBJECT_SPLITTERS' => [
    ],
    'REGEXPS' => [
    ],
    'STRICT_MODE_APPLIES' => GESHI_NEVER,
    'SCRIPT_DELIMITERS' => [
    ],
    'HIGHLIGHT_STRICT_BLOCK' => [
    ],
    'PARSER_CONTROL' => [
        'KEYWORDS' => [
            'SPACE_AS_WHITESPACE' => true,
        ],
        'ENABLE_FLAGS' => [
            'BRACKETS' => GESHI_NEVER,
        ],
    ],
];
