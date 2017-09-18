<?php
/**
 * Interface for response objects
 * @Author: Emiliano Zublena - https://github.com/emilianozublena
 * @Package: Sheetsu PHP Library - https://github.com/emilianozublena/sheetsu-php
 */

namespace Sheetsu\Interfaces;


interface ResponseInterface
{
    function getErrors();

    function getError();

    function getExceptions();

    function getException();

    function getCollection();

    function getModel();
}