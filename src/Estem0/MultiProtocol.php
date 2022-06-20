<?php

declare(strict_types=1);

namespace Estem0\MultiProtocol;

use Estem0\session\SessionManager;
use pocketmine\network\mcpe\protocol\ProtocolInfo;
use pocketmine\Player;

class MultiProtocol{

    public static function getProtocol(Player $player): int{
        return SessionManager::getProtocol($player) ?? ProtocolInfo::CURRENT_PROTOCOL;
    }
}
