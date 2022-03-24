<?php

namespace BeeAZZ\DeviceBlocker;

use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\network\mcpe\protocol\LoginPacket;
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\Server;
use pocketmine\player\Player;
use pocketmine\network\mcpe\JwtUtils;
use pocketmine\event\player\PlayerLoginEvent;

class Loader extends PluginBase implements Listener{

  private $deviceOS = [];

  public function onEnable(): void{
    
   $this->getServer()->getPluginManager()->registerEvents($this, $this);
   
   $this->saveDefaultConfig();
     
  }

  public function onPacketReceive(DataPacketReceiveEvent $event){
    $pk = $event->getPacket();
     if($pk instanceof LoginPacket){
      $data = JwtUtils::parse($pk->clientDataJwt)[1]["ThirdPartyName"];
      $this->deviceOS[$data] = JwtUtils::parse($pk->clientDataJwt)[1]["DeviceOS"];
  }
}

 public function onLogin(PlayerLoginEvent $event){
    $player = $event->getPlayer();
    $name = $player->getName();
     if(!$player->isOnline()) {
        unset($this->deviceOS[$name]);
        return;
       }
     if($this->deviceOS[$name] == 7){
     if($this->getConfig()->get("Window10") == true){
     $player->kick($this->getConfig()->get("kick-msg"));
     unset($this->deviceOS[$name]);
        }
    }
     elseif($this->deviceOS[$name] == 1){
     if($this->getConfig()->get("Android") == true){
      $player->kick($this->getConfig()->get("kick-msg"));
      unset($this->deviceOS[$name]);
         }
    }
     elseif($this->deviceOS[$name] == 2){
     if($this->getConfig()->get("IOS") == true){
     $player->kick($this->getConfig()->get("kick-msg"));
     unset($this->deviceOS[$name]);     
       }
     }
   }
}