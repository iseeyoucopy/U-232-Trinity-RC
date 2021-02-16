<?php
/**
 * -------   U-232 Codename Trinity   ----------*
 * ---------------------------------------------*
 * --------  @authors U-232 Team  --------------*
 * ---------------------------------------------*
 * -----  @site https://u-232.duckdns.org/  ----*
 * ---------------------------------------------*
 * -----  @copyright 2020 U-232 Team  ----------*
 * ---------------------------------------------*
 * ------------  @version V6  ------------------*
 */
function searchfield($entry)
{
    static $drop_char_match = array(
        '^',
        '$',
        '&',
        '(',
        ')',
        '<',
        '>',
        '`',
        '"',
        '|',
        ',',
        '@',
        '_',
        '?',
        '%',
        '-',
        '~',
        '+',
        '.',
        '[',
        ']',
        '{',
        '}',
        ':',
        '\\',
        '/',
        '=',
        '#',
        '\'',
        ';',
        '!',
        '+',
        '-',
        '|'
    );
    static $drop_char_replace = array(
        ' ',
        ' ',
        ' ',
        ' ',
        ' ',
        ' ',
        ' ',
        '',
        ' ',
        ' ',
        ' ',
        ' ',
        '',
        ' ',
        ' ',
        ' ',
        ' ',
        ' ',
        ' ',
        ' ',
        ' ',
        ' ',
        ' ',
        ' ',
        ' ',
        ' ',
        ' ',
        ' ',
        ' ',
        ' ',
        ' ',
        ' ',
        ' ',
        ' '
    );
    $entry = strip_tags(utf_strtolower($entry));
    $entry = str_replace(' +', ' and ', $entry);
    $entry = str_replace(' -', ' not ', $entry);
    $entry = str_replace(' |', ' or ', $entry);
    //
    // Filter out strange characters like ^, $, &, change "it's" to "its"
    //
    for ($i = 0; $i < (is_countable($drop_char_match) ? count($drop_char_match) : 0); $i++) {
        $entry = str_replace($drop_char_match[$i], $drop_char_replace[$i], $entry);
    }
    return $entry;
}
function split_words($entry, $mode = 'post')
{
    return explode(' ', trim(preg_replace('#\s+#', ' ', $entry)));
}
function search_text_in_db($searchstr, $base_sql, $where_search, $add_where = array() , $strict = false)
{
    global $db, $config;
    //$stopword_array = @file($root_path . 'languages/lang_' . $config['default_lang'] . '/search_stopwords.txt');
    //$synonym_array = @file($root_path . 'languages/lang_' . $config['default_lang'] . '/search_synonyms.txt');
    $match_types = array(
        'or',
        'not',
        'and'
    );
    $add_where = ((is_countable($add_where) ? count($add_where) : 0) !== 0 ? ' AND ' . implode(' AND ', $add_where) : '');
    $cleansearchstr = searchfield($searchstr);
    $lower_searchstr = utf_strtolower($searchstr);
    if ($strict) {
        $split_search = array(
            $lower_searchstr
        );
    } else {
        $split_search = split_words($cleansearchstr);
        if ($lower_searchstr != $searchstr) {
            $search_full_string = true;
            foreach ($match_types AS $_null => $match_type) {
                if (strpos($lower_searchstr, (string) $match_type) !== false) {
                    $search_full_string = false;
                }
            }
            if ($search_full_string) {
                $split_search = (array) $split_search;
                $split_search[] = $lower_searchstr;
            }
        }
    }
    $word_count = 0;
    $current_match_type = 'and';
    $word_match = array();
    $result_list = array();
    for ($i = 0; $i < (is_countable($split_search) ? count($split_search) : 0); $i++) {
        if (utf_strlen(str_replace(array(
            '*',
            '%'
        ) , '', trim($split_search[$i]))) < $config['search_min_chars'] && !in_array($split_search[$i], $match_types)) {
            $split_search[$i] = '';
            continue;
        }
        switch ($split_search[$i]) {
        case 'and':
            $current_match_type = 'and';
            break;
        case 'or':
            $current_match_type = 'or';
            break;
        case 'not':
            $current_match_type = 'not';
            break;
        default:
            if (!empty($search_terms)) {
                $current_match_type = 'and';
            }
            if ($strict) {
                $search = $where_search . ' = \'' . sqlesc($split_search[$i]) . '\'' . $add_where;
            } else {
                $match_word = str_replace('*', '%', $split_search[$i]);
                $search = $where_search . ' LIKE \'%' . sqlesc($match_word) . '%\'' . $add_where;
                //$search = $where_search . ' REGEXP \'[[:<:]]' . $db->sql_escape($match_word) . '[[:>:]]\'' . $add_where;
            }
            $sql = $base_sql . ' WHERE ' . $search;
            $result = sql_query($sql);
            $row = array();
            while ($temp_row = $result->fetch_row()) {
                $row[$temp_row['id']] = 1;
                if ($word_count === 0) {
                    $result_list[$temp_row['id']] = 1;
                } elseif ($current_match_type == 'or') {
                    $result_list[$temp_row['id']] = 1;
                } elseif ($current_match_type == 'not') {
                    $result_list[$temp_row['id']] = 0;
                }
            }
            if ($current_match_type == 'and' && $word_count) {
                @reset($result_list);
                foreach (array_keys($result_list) AS $id) {
                    if (!isset($row[$id]) || !$row[$id]) {
                        //$result_list[$id] = 0;
                        @$result_list[$id]-= 1;
                    } else {
                        @$result_list[$id]+= 1;
                    }
                }
            }
            $word_count++;
            $result->fetch_assoc();
        }
    }
    @reset($result_list);
    $search_ids = array();
    foreach ($result_list AS $id => $matches) {
        if ($matches > 0) {
            //if ( $matches ) {
            $search_ids[] = $id;
        }
    }
    unset($result_list);
    return $search_ids;
}
?>
