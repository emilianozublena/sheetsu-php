<?php
/**
 * Interface for error handler object
 * @Author: Emiliano Zublena - https://github.com/emilianozublena
 * @Package: Sheetsu PHP Library - https://github.com/emilianozublena/sheetsu-php
 */

namespace Sheetsu\Interfaces;

use ErrorException;

interface ErrorHandlerInterface
{
    public function getErrors();

    public function getFirstError();

    public function getExceptions();

    public function getFirstException();

    static function tryStatic($data, $arguments);

    static function create($exception);

    static function checkForErrorsInCurl($curl);
}