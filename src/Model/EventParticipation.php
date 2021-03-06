<?php
/**
 * This file is part of the CalendArt package
 *
 * For the full copyright and license information, please view the LICENSE file
 * that was distributed with this source code.
 *
 * @copyright Wisembly
 * @license   http://www.opensource.org/licenses/MIT-License MIT License
 */

namespace CalendArt\Adapter\Office365\Model;

use CalendArt\EventParticipation as BaseEventParticipation;

use InvalidArgumentException;

use CalendArt\Adapter\Office365\ReflectionTrait;

/**
 * Office365 Attendee
 *
 * @link https://msdn.microsoft.com/office/office365/APi/complex-types-for-mail-contacts-calendar#Attendee
 * @author Baptiste Clavié <baptiste@wisembly.com>
 */
class EventParticipation extends BaseEventParticipation
{
    use ReflectionTrait;

    const TYPE_REQUIRED = 0;
    const TYPE_OPTIONAL = 1;
    //const TYPE_RESOURCE = 2;

    const STATUS_NONE = null;
    const STATUS_ORGANIZER = 2;

    private $type = self::TYPE_OPTIONAL;

    /** @return integer */
    public function getType()
    {
        return $this->type;
    }

    public function setType($type)
    {
        $this->type = $type;
    }

    /** {@inheritDoc} */
    public static function getAvailableStatuses()
    {
        return parent::getAvailableStatuses() + [static::STATUS_NONE, static::STATUS_ORGANIZER];
    }

    public static function translateStatus($status)
    {
        switch($status) {
            case null:
            case 'none':
            case 'notResponded':
                return static::STATUS_NONE;

            case 'tentativelyAccepted':
                return static::STATUS_TENTATIVE;

            case 'accepted':
                return static::STATUS_ACCEPTED;

            case 'declined':
                return static::STATUS_DECLINED;

            case 'organizer':
                return static::STATUS_ORGANIZER;

            default:
                throw new InvalidArgumentException(sprintf('Wrong status sent. Expected one of [\'none\', \'notResponded\', \'tentativelyAccepted\', \'accepted\', \'declined\', \'organizer\'], had "%s"', $status));
        }
    }

    public static function translateType($type) {
        return self::translateConstantToValue('TYPE_', $type);
    }

    public function export()
    {
        return [
            'emailAddress' => ['address' => $this->getUser()->getEmail()]
        ];
    }
}

