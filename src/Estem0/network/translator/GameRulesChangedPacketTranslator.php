<?php

declare(strict_types=1);

namespace Estem0\network\translator;

use Estem0\network\Serializer;
use pocketmine\network\mcpe\protocol\GameRulesChangedPacket;

class GameRulesChangedPacketTranslator {

    public static function serialize(GameRulesChangedPacket $packet, int $protocol) {
        Serializer::putGameRules($packet, $packet->gameRules, $protocol);
    }
}
