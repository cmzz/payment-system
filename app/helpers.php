<?php

declare(strict_types=1);

use App\Keys;

/**
 * @return int
 */
function current_app_id(): int
{
    return (int)session(Keys::SES_APP_ID, 0);
}

function current_app(): \App\Models\App
{
    return session(Keys::SES_APP);
}
