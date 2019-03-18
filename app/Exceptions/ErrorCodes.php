<?php

declare(strict_types=1);

namespace App\Exceptions;

/**
 * Class ErrorCodes
 * @package App\Core\Response
 */
final class ErrorCodes
{
    const INVALID_ARGUMENT_ERROR = 41000;
    const RECORD_NOT_FOUND = 43001;
    const RECORD_ALREADY_EXISTS = 43002;

    const INTERNAL_ERROR = 50000;
    const DUPLICATE_ERROR = 50001;
    const THIRD_PARTY_ERROR = 51000;
    const UNION_PARAM_ERROR = 52000;
    const UPDATE_RECORD_FAIL_ERROR = 53000;
}
