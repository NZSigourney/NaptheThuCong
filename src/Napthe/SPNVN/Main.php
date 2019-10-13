<?php

/* -----[NaptheUI]-----
* Updated Main UI System
* Author: BlackPMFury
* Current Plugin: NaptheUI/Phuongaz
* Version 3.0-SPECIALS
*/

namespace Napthe\SPNVN;

use pocketmine\plugin\PluginBase;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\{Player, Server};
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\Listener;
use pocketmine\utils\Config;
use pocketmine\math\Vector3;
use jojoe7777\FormAPI;
use Napthe\SPNVN\Main;

class Main extends PluginBase implements Listener{
	public $tag = "§6[§aNapthe§cUI§6]§r";
	public $config;
	
	public function onEnable(){
		$this->getServer()->getLogger()->info($this->tag . "§l§a Enable Plugin...");
		$this->dnt = new Config($this->getDataFolder(). "Donation.yml", Config::YAML);
		$this->pp = $this->getServer()->getPluginManager()->getPlugin("PurePerms");
		
		@mkdir($this->getDataFolder());
		$this->saveDefaultConfig();
		$this->getResource("Config.yml");
	}
	
	public function onJoin(PlayerJoinEvent $ev){
		$player = $ev->getPlayer();
		$name = $player->getName();
		if($player->isOp()){
			foreach($this->getServer()->getOnlinePlayers() as $dnt){
				if($this->dnt->exists($name)){
				    $dnt->sendMessage($this->tag . "§b Found a donater at Donation.yml -". $this->dnt->get($name));
				    return true;
				}
			}
			return true;
		}else{
			$player->sendPopup("§d/napthe§a Để ủng hộ Server nhé <3");
			return true;
		}
	}
	
	public function onLoad(): void{
		$this->getServer()->getLogger()->info("§l§b-=-=-=-=| ".$this->tag."§l§b |=-=-=-=-");
		$this->getServer()->getLogger()->notice($this->tag . "§l§a Code By BlackPMFury");
	}
	
	public function onCommand(CommandSender $sender, Command $cmd, string $label, array $args): bool{
		switch($cmd->getName()){
			case "napthe":
			if(!($sender instanceof Player)){
				$this->getServer()->getLogger()->info($this->tag . "§l§c You can not use this command In Here!");
				return true;
			}
			$api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
			$form = $api->createSimpleForm(Function (Player $sender, $data){
				
				$result = $data;
				if ($result == null){
				}
				switch ($result) {
					case 0:
					$sender->sendMessage("§l§a Thank For Donate!");
					break;
					case 1:
					$this->infoCard($sender);
					break;
					case 2:
					$this->napthe($sender);
					break;
					case 3:
					$this->versionPlugin($sender);
					break;
					case 4:
					$this->checkStatus($sender);
					break;
					case 5:
					$sender->sendMessage("§c§l•§a Facebook Admin: https://www.facebook.com/RepublicOf.Vietnam.92 Or Thái Thiên Long");
					break;
				}
			});
			$form->setTitle($this->getConfig()->get("plugin.title"));
			$form->setContent($this->tag . "§l§a Donate to Buy Vip!");
			$form->addButton("§cEXIT", 0);
			$form->addButton($this->getConfig()->get("Profile.title"), 1);
			$form->addButton($this->getConfig()->get("Donation.title"), 2);
			$form->addButton("§eVersion", 3);
			$form->addButton("§aAdmin-Tools", 4);
			$form->addButton("§c-==§d•§e Facebook Admin§d •§c==-", 5);
			$form->sendToPlayer($sender);
		}
		return true;
	}
	
	/**public function thongTin($sender){
		$api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
		$form = $api->createCustomForm(Function (Player $sender, $data){
		});
		$form->setTitle($this->getConfig()->get("Profile.title"));
		$form->addLabel("§a Nạp Thẻ Giúp Bạn Mua Rank và Các Mặt Hàng Bằng SCoin.");
		$form->addLabel("§cNOTE:§e Trường Hợp Thẻ Sai sẽ Bị Xoá Thẻ (Nếu Cố Ý gửi Thẻ Sai)");
		$form->sendToPlayer($sender);
	}*/
	
	public function napthe($sender){
		$api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
		$form = $api->createCustomForm(Function (Player $sender, $data){
			switch($data[0]){
				case 0:
				$loaithe = "Mobiphone";
				break;
				case 1:
				$loaithe = "Vinaphone";
				break;
				case 2:
				$loaithe = "Viettel";
				break;
				case 3:
				$loaithe = "Zing";
				break;
			}
			switch($data[1]){
				case 0:
				$menhgia = "20000";
				break;
				case 1:
				$menhgia = "50000";
				break;
				case 2:
				$menhgia = "100000";
				break;
			}
			if(!(is_numeric($data[2]) || is_numeric($data[3]))){
				$sender->sendMessage("§a§l Phải Là Số!");
				return true;
			}
			$this->getServer()->getLogger()->notice("Donate By ".$sender->getName().", Check In Donation.yml");
			$sender->sendMessage($this->tag . " §l§aSeri:§e ".$data[1].",§a Code: §e".$data[3]."\n§a Typer:§b ".$loaithe.", §aMệnh Giá: §e". $menhgia);
			$this->dnt->set( $sender->getName(), ["Typer" => $loaithe, "Mệnh Giá" => $menhgia, "Seri" => $data[2], "Code" => $data[3]]);
			$this->dnt->save();
			if($data[0] == "Vinaphone" || $data[0] == "Zing"){
				$sender->sendMessage($this->tag . "§c Đang Bảo Trì!");
				return true;
			}
		});
		$form->setTitle($this->getConfig()->get("Donation.title"));
		$form->addDropdown("§c•§aLoại Thẻ§c•", ["Mobiphone", "Vinaphone", "Viettel", "Zing"]);
		$form->addDropdown("§c•§dMệnh Giá§c•", ["20000", "50000", "100000"]);
		//$form->addInput("§aMệnh Giá:");
		$form->addInput("§aSeri:");
		$form->addInput("§aCode:");
		$form->sendToPlayer($sender);
		return true;
	}
	
	public function versionPlugin($sender){
		$sender->sendMessage($this->tag . " §e3.0\n§aAuthor: BlackPMFury");
	}
	
	public function checkStatus($sender){
		$api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
		$form = $api->createCustomForm(Function (Player $sender, $data){
			switch($data[1]){
				case 0:
				$thongtin = "Thành Công";
				break;
				case 1:
				$thongtin = "Thất Bại";
				break;
			}
			if($sender->hasPermission("Checkcard.admintools")){
				if($thongtin == "Thất Bại"){
					$this->getServer()->dispatchCommand(new ConsoleCommandSender(), "tell ".$data[0]." §bVui Lòng Check lại thẻ cào của bạn! Lý Do: §c". $thongtin);
				    //$this->dnt->remove($data[0]);
					return true;
				}elseif($thongtin == "Thành Công"){
					$this->getServer()->dispatchCommand(new ConsoleCommandSender(), "tell ".$data[0]." §aThông Tin Thẻ Của Bạn: §c". $thongtin);
				    //$this->dnt->remove($data[0]);
					return true;
				}
			}else{
				$sender->sendMessage($this->tag . "§l§c You do not have permission for use this command!");
			}
		});
		$form->setTitle($this->getConfig()->get("Checkcard.title"));
		$form->addInput("§aChecker");
		$form->addDropdown("§c❤️ §aResult §c❤️", ["Thành Công", "Thất Bại"]);
		$form->sendToPlayer($sender);
	}
	
	public function infoCard($sender){
		$name = $this->dnt->get($sender->getName());
		$type = $name["Typer"];
		$cost = $name["Mệnh Giá"];
		$seri = $name["Seri"];
		$code = $name["Code"];
		$api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
		$form = $api->createCustomForm(Function (Player $sender, $data){
			/**$rank = $this->pp->getUserDataMgr()->getGroup($sender);
			if(is_null($data[6])){
				switch($data[6]){
					case 0:
					$sender->sendMessage($this->tag . "§l§a Thanks For Donation!");
					break;
					case 1:
					$this->dnt->remove($this->dnt->get($sender->getName()));
					break;
				}
				return true;
			}*/
		});
		$form->setTitle($this->getConfig()->get("Profile.title"));
		$form->addLabel("§aInfo of your card:");
		$form->addLabel("§c •§a Loại Thẻ:§e ". $type);
		$form->addLabel("§c •§a Mệnh Giá:§e ". $cost);
		$form->addLabel("§c •§a Code:§e ". $code);
		$form->addLabel("§c •§a Seri:§e ". $seri);
		//$form->addDropdown("Bạn có muốn xoá donation?", ["Don't Delete", "Delete it"]);
		$form->sendToPlayer($sender);
	}
	
	
}