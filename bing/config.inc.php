<?php

/**
 * @var string Microsoft/Bing Primary Account Key
 */
//ACCOUNT_KEY you have to get from https://datamarket.azure.com/dataset/1899a118-d202-492c-aa16-ba21c33c06cb

if (!defined('ACCOUNT_KEY')) {
    define('ACCOUNT_KEY', 'Cyqhv0PW2s3UsK0pUhoVd21n5qx3+wD9MKV8xREt41Q');
}
if (!defined('CACHE_DIRECTORY')) {
    define('CACHE_DIRECTORY', 'bing/translate/cache/');
}
if (!defined('LANG_CACHE_FILE')) {
    define('LANG_CACHE_FILE', 'lang.cache');
}
if (!defined('ENABLE_CACHE')) {
    define('ENABLE_CACHE', true);
}
if (!defined('UNEXPECTED_ERROR')) {
    define('UNEXPECTED_ERROR', 'There is some un expected error . please check the code');
}
if (!defined('MISSING_ERROR')) {
    define('MISSING_ERROR', 'Missing Required Parameters ( Language or Text) in Request');
}
