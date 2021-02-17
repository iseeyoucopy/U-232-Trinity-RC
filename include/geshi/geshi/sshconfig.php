<?php
/*************************************************************************************
 * sshconfig.php
 * --------
 * Author: Kevin Ernst (kevin.ernst -at- cchmc.org)
 * Copyright: (c) 2017 Kevin Ernst
 * Release Version: 1.0.9.1
 * Date Started: 2017/12/01
 *
 * OpenSSH config file (~/.ssh/config) language file for GeSHi.
 *
 * CHANGES
 * -------
 * 2017/12/02 (1.0.0)
 *   -  First release; couldn't figure out how to separately highlight negated
 *      hostnames/wildcards, but it's good enough for a basic ~/.ssh/config
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
    'LANG_NAME' => 'sshconfig',
    'COMMENT_SINGLE' => [],
    'COMMENT_MULTI' => [],
    'COMMENT_REGEXP' => [0 => '/^\s*#.*?$/m'],
    'CASE_KEYWORDS' => GESHI_CAPS_NO_CHANGE,
    'QUOTEMARKS' => ['"'],
    'ESCAPE_CHAR' => '',
    'KEYWORDS' => [
        0 => [
            'Host',
        ],
        1 => [
            "Hostname",
            "Match",
            "AddKeysToAgent",
            "AddressFamily",
            "BatchMode",
            "BindAddress",
            "CanonicalDomains",
            "CanonicalizeFallbackLocal",
            "CanonicalizeHostname",
            "CanonicalizeMaxDots",
            "CanonicalizePermittedCNAMEs",
            "CertificateFile",
            "ChallengeResponseAuthentication",
            "CheckHostIP",
            "Ciphers",
            "ClearAllForwardings",
            "Compression",
            "ConnectionAttempts",
            "ConnectTimeout",
            "ControlMaster",
            "ControlPath",
            "ControlPersist",
            "DynamicForward",
            "EnableSSHKeysign",
            "EscapeChar",
            "ExitOnForwardFailure",
            "FingerprintHash",
            "ForwardAgent",
            "ForwardX11",
            "ForwardX11Timeout",
            "ForwardX11Trusted",
            "GatewayPorts",
            "GlobalKnownHostsFile",
            "GSSAPIAuthentication",
            "GSSAPIDelegateCredentials",
            "HashKnownHosts",
            "HostbasedAuthentication",
            "HostbasedKeyTypes",
            "HostKeyAlgorithms",
            "HostKeyAlias",
            "HostName",
            "IdentitiesOnly",
            "IdentityAgent",
            "IdentityFile",
            "IgnoreUnknown",
            "Include",
            "IPQoS",
            "KbdInteractiveAuthentication",
            "KbdInteractiveDevices",
            "KexAlgorithms",
            "LocalCommand",
            "LocalForward",
            "LogLevel",
            "MACs",
            "NoHostAuthenticationForLocalhost",
            "NumberOfPasswordPrompts",
            "PasswordAuthentication",
            "PermitLocalCommand",
            "PKCS11Provider",
            "Port",
            "PreferredAuthentications",
            "ProxyCommand",
            "ProxyJump",
            "ProxyUseFdpass",
            "PubkeyAcceptedKeyTypes",
            "PubkeyAuthentication",
            "RekeyLimit",
            "RemoteCommand",
            "RemoteForward",
            "RequestTTY",
            "RevokedHostKeys",
            "SendEnv",
            "ServerAliveCountMax",
            "ServerAliveInterval",
            "StreamLocalBindMask",
            "StreamLocalBindUnlink",
            "StrictHostKeyChecking",
            "SyslogFacility",
            "TCPKeepAlive",
            "Tunnel",
            "TunnelDevice",
            "UpdateHostKeys",
            "UsePrivilegedPort",
            "User",
            "UserKnownHostsFile",
            "VerifyHostKeyDNS",
            "VisualHostKey",
            "XAuthLocation",
        ],
    ],
    'SYMBOLS' => [
        0 => [
            '%h',
            '%p',
        ],
        // these get clobbered by regexes anyway
        //1 => array( '!'),
        //2 => array( '*')
    ],
    'CASE_SENSITIVE' => [
        GESHI_COMMENTS => false,
        0 => true,
        1 => true,
    ],
    'STYLES' => [
        'KEYWORDS' => [
            0 => 'color: green; font-weight: bold',
            1 => 'color: blue',
        ],
        'COMMENTS' => [
            0 => 'color: #666666; font-style: italic;',
        ],
        'ESCAPE_CHAR' => [
            0 => '',
        ],
        'BRACKETS' => [
            0 => '',
        ],
        'STRINGS' => [
            0 => 'color: #933;',
        ],
        'NUMBERS' => [
            0 => '',
        ],
        'METHODS' => [
            0 => '',
        ],
        'SYMBOLS' => [
            0 => 'color: lightseagreen; font-weight: bold',
            // these two get clobbered by regexes below anyway
            1 => 'color: red; font-weight: bold',
            1 => 'color: darkmagenta; font-weight: bold',
        ],
        'REGEXPS' => [
            0 => 'color: darkmagenta;',
            //1 => 'color: red; font-weight: bold',
            2 => 'color: magenta; font-weight: bold',
        ],
        'SCRIPT' => [
            0 => '',
        ],
    ],
    'URLS' => [
        0 => 'https://man.openbsd.org/ssh_config#{FNAME}',
        1 => 'https://man.openbsd.org/ssh_config#{FNAME}',
    ],
    'OOLANG' => false,
    'OBJECT_SPLITTERS' => [
    ],
    'REGEXPS' => [
        // Hostnames
        0 => [
            GESHI_SEARCH => '(Host(name)?.*?)(?<=\s)(.*)',
            GESHI_REPLACE => '\\3',
            GESHI_MODIFIERS => '',
            GESHI_BEFORE => '\\1',
            GESHI_AFTER => '',
        ],
        // Negated hostanmes (doesn't work)
        //1 => array(
        //    GESHI_SEARCH => '([([{,<+*-\/=\s!]|&lt;)(?!(?:PIPE|SEMI|DOT|NUM|REG3XP\d*)\W)(![a-zA-Z*]\w*)(?!\w)',
        //    GESHI_SEARCH => '(?<=!)(.*?)',
        //    GESH_REPLACE => '\\2',
        //    GESHI_MODIFIERS => '',
        //    GESHI_BEFORE => '\\1',
        //    GESHI_AFTER => ''
        //    ),
        // Wildcards
        2 => '\*',
    ],
    'STRICT_MODE_APPLIES' => GESHI_NEVER,
    'SCRIPT_DELIMITERS' => [
    ],
    'HIGHLIGHT_STRICT_BLOCK' => [
    ],
];
