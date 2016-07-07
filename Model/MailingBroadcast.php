<?php

namespace Creonit\MailingBundle\Model;

use Creonit\MailingBundle\Model\Base\MailingBroadcast as BaseMailingBroadcast;

/**
 * Skeleton subclass for representing a row from the 'mailing_broadcast' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class MailingBroadcast extends BaseMailingBroadcast
{

    const STATUS_CREATED = 0;
    const STATUS_READY = 1;
    const STATUS_SUCCESS = 2;
    const STATUS_FAIL = 3;

}
