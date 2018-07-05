<?php
/**
 *
 *  Achachi XML Builder - Framework and applications builder tool
 *  Copyright (C) 2010-2011, Llankay Achachi
 *
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 */

    function format_code($_code) {
      return substr(format_string("<?php ".$_code),7);
    }


    /**
    * The format_string method
    *
    * This method format the given PHP source code.
    * @param string $code the source code to be formatted.
    * @return mixed it returns the formated code or false if it fails.
    */
    function format_string ($code = '') {
//return " ".$code;
        $t_count = 0;
        $in_object = false;
        $in_at = false;
        $in_php = false;
        
        $result = '';
        $tokens = token_get_all ($code); 
        foreach ($tokens as $i => $token) { 
            if (is_string ($token)) { 
                $token = trim ($token);
                if ($token == '{') {
                    $t_count++; 
                    $result = rtrim ($result) . ' ' . $token . "\r\n" . str_repeat ("\t", $t_count);                    
                } elseif ($token == '}') {
                    $t_count--; 
                    $result = rtrim ($result) . "\r\n" . str_repeat ("\t", $t_count) . $token . "\r\n" . str_repeat ("\t", $t_count);                                        
                } elseif ($token == ';') {
                    $result .= $token . "\r\n" . str_repeat ("\t", $t_count);                                        
                } elseif ($token == ':') {
                    $result .= $token;// . "\r\n" . str_repeat ("\t", $t_count);                                        
                } elseif ($token == '(') {
                    $result .= ' ' . $token;                                        
                } elseif ($token == ')') {
                    $result .= $token;                                        
                } elseif ($token == '@') {
                    $in_at = true;
                    $result .= $token;                                        
                } elseif ($token == '.') {
                    $result .= ' ' . $token . ' ';                                        
                } elseif ($token == '=') {
                    $result .= ' ' . $token . ' ';                                        
                } else {
                    $result .= $token;
                }
                
            } else { 
                list ($id, $text) = $token; 
                switch ($id) { 
                case T_OPEN_TAG:
                case T_OPEN_TAG_WITH_ECHO:
                    $in_php = true;
                    $result .=  ($text);                    
                    break; 
                case T_CLOSE_TAG:
                    $in_php = false;
                    $result .= trim ($text);                    
                    break; 
                case T_OBJECT_OPERATOR:
                    $result .= trim ($text);                    
                    $in_object = true;
                    break; 
                case T_STRING:
                    if ($in_object) {
                        $result = rtrim ($result) . trim ($text);                    
                        $in_object = false;
                    } elseif ($in_at) {
                        $result = rtrim ($result) . trim ($text);                    
                        $in_at = false;
                    } else {
                        $result = $result . trim ($text);                    
                    }
                    break; 
                case T_ENCAPSED_AND_WHITESPACE:
                case T_WHITESPACE:
                    $result .= trim ($text);                    
                    break; 
                case T_RETURN:
                    $result = ($result) . trim ($text) . ' ';             
                    break; 
                case T_ELSE:
                case T_ELSEIF:
                    $result = rtrim ($result) . ' '  . trim ($text) . ' ';             
                    break; 
                case T_CASE:
                case T_DEFAULT:
                    $result = rtrim ($result) . "\r\n" . str_repeat ("\t", $t_count - 1) . trim ($text) . ' ';             
                    break;
                case T_EXTENDS:
                    $result .= " " . trim ($text) . ' ';             
                    break; 
                case T_PUBLIC: 
                    $result .= "\r\n" . str_repeat ("\t", $t_count) . trim ($text).' ';
                    break; 
                case T_FUNCTION: 
                case T_CLASS: 
                    $result .= (isset($tokens[$i-2])&&($tokens[$i-2][0]==T_PUBLIC)?"":("\r\n" . str_repeat ("\t", $t_count))) . trim ($text) . ' ';
                    break; 
                case T_AND_EQUAL:
                case T_AS:
                case T_BOOLEAN_AND:
                case T_BOOLEAN_OR:
                case T_CONCAT_EQUAL:
                case T_DIV_EQUAL:
                case T_DOUBLE_ARROW:
                case T_IS_EQUAL:
                case T_IS_GREATER_OR_EQUAL:
                case T_IS_IDENTICAL:
                case T_IS_NOT_EQUAL:
                case T_IS_NOT_IDENTICAL:
                // case T_SMALLER_OR_EQUAL: // undefined constant ???
                case T_LOGICAL_AND:
                case T_LOGICAL_OR:
                case T_LOGICAL_XOR:
                case T_MINUS_EQUAL:
                case T_MOD_EQUAL:
                case T_MUL_EQUAL:
                case T_OR_EQUAL:
                case T_PLUS_EQUAL:
                case T_SL:
                case T_SL_EQUAL:
                case T_SR:
                case T_SR_EQUAL:
                case T_START_HEREDOC:
                case T_XOR_EQUAL:
                    $result = rtrim ($result) . ' ' . trim ($text) . ' ';             
                    break; 
                case T_COMMENT:
                    $result = rtrim ($result) . "\r\n" . str_repeat ("\t", $t_count) . trim ($text) . "\r\n" . str_repeat ("\t", $t_count);
                    break;
                /*case T_ML_COMMENT:
                    $result = rtrim ($result) . "\r\n";
                    $lines = explode ("\n", $text);             
                    foreach ($lines as $line) {
                        $result .= str_repeat ("\t", $t_count) . trim ($line);
                    }
                    $result .= "\r\n";
                    break;*/
                case T_INLINE_HTML:
                    $result .= $text;                    
                    break; 
                default: 
                    $result .=  trim($text).' ';
                    break; 
                } // switch($id) { 
            } // if (is_string ($token)) { 
        } // foreach ($tokens as $token) {             
        return $result;        
    } // function format_string ($code = '') {

