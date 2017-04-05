<?php
/**
 * Interface for response objects
 * @Author: Emiliano Zublena - https://github.com/emilianozublena
 * @Package: Sheetsu PHP Library - https://github.com/emilianozublena/sheetsu-php
 */

namespace Sheetsu\Interfaces;


interface ResponseInterface
{
    function getHttpStatusCode();
    function getCollection();
    function getModel();
}