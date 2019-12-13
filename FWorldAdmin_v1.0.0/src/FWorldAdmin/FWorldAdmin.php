<?php
namespace FWorldAdmin;

use pocketmine\command\Command;
use pocketmine\command\CommandExecutor;
use pocketmine\command\CommandSender;
use pocketmine\event\Listener;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\Server;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat as Font;
use pocketmine\level\position;
use pocketmine\level\Level;

class FWorldAdmin extends PluginBase implements CommandExecutor, Listener {

	public function onEnable() {
		$this->getServer()->getLogger()->info(Font::BLUE . "FWorldAdmin多世界");
			$this->getServer()->getLogger()->info(Font::BLUE . "开始加载");
		  if($this->getServer()->getPluginManager()->getPlugin("FWatchDog") === null){
  
   $this->getServer()->getLogger()->info("§eFWorldAdmin>>>你没有安装FWatchDog反作弊插件,希望你可以购买专业版授权来支持一下我们团队");
  }	
			
		$this->LoadAllLevels();
 	}

	public function LoadAllLevels() {
		$level = $this->getServer()->getDefaultLevel();
   		$path = $level->getFolderName();
        $p2 = $this->getServer()->getDataPath() . "worlds/";
   		$dirnowfile = scandir($p2, 1);
        foreach ($dirnowfile as $dirfile){
	    	if($dirfile != '.' && $dirfile != '..' && $dirfile != $path && is_dir($p2.$dirfile)) {
				if (!$this->getServer()->isLevelLoaded($dirfile)) {  
					$this->getLogger()->info(Font::YELLOW . "FWorldAdminLoad>>$dirfile");
					$this->getServer()->generateLevel($dirfile);
					$this->getServer()->loadLevel($dirfile);
					$level = $this->getServer()->getLevelbyName($dirfile);
					if ($level->getName() != $dirfile) {  
						$this->getLogger()->info(Font::RED . "FWorldAdminLoad>>>$dirfile Name is can not read.Because the map name should is".$level->getName()."请尽快解决");
					}
				}
			}
	  	}
	}
	
  	public function onCommand(CommandSender $sender, Command $cmd, $label, array $args) {
    	switch($cmd->getName()) {
		  case "worldadmin":
				
				$sender->sendMessage("§d=====FWorldAdmin CommandsHelp=====");
				$sender->sendMessage("§d§lVersion: v1.0.0");
				$sender->sendMessage("§d§l1./load  Load the all world");
		        $sender->sendMessage("§d§l2./unloaf Unload the world");
		        $sender->sendMessage("§d§l3./lw seetheworld");
		        $sender->sendMessage("§d§l4./w Teleport the world");
		      	$sender->sendMessage("§d§l/wdbuild BuildTheBuilding");
      
	  		break;
			
			
			
			
			case "unload":
	 			if(isset($args[0])){
	    			$l = $args[0];
					if (!$this->getServer()->isLevelLoaded($l)) {  //如果这个世界未加载
						$sender->sendMessage(Font::RED . "FWorldAdmin>>>Map $l can not unload.Please use /load");
					}
					else {
						$level = $this->getServer()->getLevelbyName($l);
						$ok = $this->getServer()->unloadLevel($level); 
						if($ok !== true){
							$sender->sendMessage(Font::RED . "FWorldAdmin>>>[ERIC/SERVER]$l 未知Bug ");
						}else{
							$sender->sendMessage(Font::GREEN . "FWorldAdmin>>>map $l unpoad!");
						}
					}
				}
	 		break;
	 
	 		case "load":
	 			if(isset($args[0])){
					$level = $this->getServer()->getDefaultLevel();
   					$path = $level->getFolderName();
   					//$p1 = dirname($path);
   					//$p2 = $p1."/worlds/";
                    $p2 = $this->getServer()->getDataPath() . "worlds/";
					$path = $p2;
					//$path = dirname(dirname(dirname(dirname(dirname(__FILE__))))) . "\\loadLevel\\";
					$l = $args[0];
					if ($this->getServer()->isLevelLoaded($l)) {  //如果这个世界已加载
						$sender->sendMessage(Font::RED . "FWorldAdmin>>>Map ".$args[0]."Load!" );
					}
					elseif (is_dir($path.$l)){
						$sender->sendMessage(Font::YELLOW . "FWorldAdminLoad>>> ".$args[0]."." );
						$this->getServer()->generateLevel($l);
						$ok = $this->getServer()->loadLevel($l);
						if ($ok === false) {
							$sender->sendMessage(Font::RED . "FWorldAdminLoad[BUG]>>>".$args[0]." can not load");
						}
						else {
							$sender->sendMessage(Font::GREEN . "FWorldAdminLoad>>>".$args[0]."");
						}
					}else{
						$sender->sendMessage(Font::RED . "FWorldAdminLoad[BUG] >>>".$args[0]."can not find map");
					}
			 	}
	 		break;
	 
     		case "lw":
				$levels = $this->getServer()->getLevels();
				$sender->sendMessage("§d=====WORLD LIST=====");
       			foreach ($levels as $level){
	   				$sender->sendMessage(" - ".$level->getFolderName());
	  			}
	  		break;
	  
			case "w":
     			if ($sender instanceof Player){
					if(isset($args[0])){
	  					$l = $args[0];
	  					if ($this->getServer()->isLevelLoaded($l)) {  
      						$sender->teleport(Server::getInstance()->getLevelByName($l)->getSafeSpawn());
							$sender->sendMessage("FWorldAdmin>>>teleport : $l");
            			}else{
     						$sender->sendMessage("FWorldAdmin>>> ".$l." can not find.");
          				}
		  			}else{
   						$sender->sendMessage("FWorldAdmin>>>map?");
		  			}
	  			}else{
	  				$sender->sendMessage("FWorldAdmin>>>Please use commands in game");
	  			}
	  		break;
	  
  		}
	}
	
}
