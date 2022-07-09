<?php

declare(strict_types=1);

namespace Estem0\network\translator;

use Estem0\network\Serializer;
use pocketmine\network\mcpe\protocol\CreativeContentPacket;
use function count;

class CreativeContentPacketTranslator {

    public static function serialize(CreativeContentPacket $packet, int $protocol) {
        $packet->putUnsignedVarInt(count($packet->getEntries()));
        foreach($packet->getEntries() as $entry){
            $packet->writeGenericTypeNetworkId($entry->getEntryId());
            Serializer::putItemStackWithoutStackId($packet, $entry->getItem(), $protocol);
        }
    }
}
