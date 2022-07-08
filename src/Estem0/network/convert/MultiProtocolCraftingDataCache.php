<?php

declare(strict_types=1);

namespace Estem0\network\convert;

use Estem0\Loader;
use Estem0\network\ProtocolConstants;
use Estem0\network\Translator;
use pocketmine\inventory\CraftingManager;
use pocketmine\network\mcpe\protocol\BatchPacket;
use pocketmine\network\mcpe\protocol\CraftingDataPacket;
use pocketmine\Server;
use pocketmine\timings\Timings;

class MultiProtocolCraftingDataCache extends CraftingManager{

    /** @var BatchPacket[] */

    protected $multiProtocolCraftingDataCache = [];

    const PROTOCOL = [
        ProtocolConstants::BEDROCK_1_19_0,
        ProtocolConstants::BEDROCK_1_18_30,
        ProtocolConstants::BEDROCK_1_18_10,
        ProtocolConstants::BEDROCK_1_18_0,
        ProtocolConstants::BEDROCK_1_17_40,
        ProtocolConstants::BEDROCK_1_17_30,
        ProtocolConstants::BEDROCK_1_17_10,
        ProtocolConstants::BEDROCK_1_17_0,
        ProtocolConstants::BEDROCK_1_16_220,
    ];

    public function buildCraftingDataCache(): void{
        Timings::$craftingDataCacheRebuildTimer->startTiming();
        $c = Server::getInstance()->getCraftingManager();
        foreach(self::PROTOCOL as $protocol){
            if(Loader::getInstance()->isProtocolDisabled($protocol)) {
                continue;
            }
            $pk = new CraftingDataPacket();
            $pk->cleanRecipes = true;
            foreach($c->shapelessRecipes as $list){
                foreach($list as $recipe){
                    $pk->addShapelessRecipe($recipe);
                }
            }
            foreach($c->shapedRecipes as $list){
                foreach($list as $recipe){
                    $pk->addShapedRecipe($recipe);
                }
            }
            foreach($c->furnaceRecipes as $recipe){
                $pk->addFurnaceRecipe($recipe);
            }
            $pk = Translator::fromServer($pk, $protocol);
            $batch = new BatchPacket();
            $batch->addPacket($pk);
            $batch->setCompressionLevel(Server::getInstance()->networkCompressionLevel);
            $batch->encode();
            $this->multiVersionCraftingDataCache[$protocol] = $batch;
        }
        Timings::$craftingDataCacheRebuildTimer->stopTiming();
    }

    public function getCraftingDataPacketA(int $protocol): BatchPacket{
        return $this->multiVersionCraftingDataCache[$protocol];
    }
}
