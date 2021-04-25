<?php

namespace ItzLightyHD\KnockbackFFA\command\subcommands;

use ItzLightyHD\KnockbackFFA\Loader;
use ItzLightyHD\KnockbackFFA\EventListener;
use CortexPE\Commando\BaseSubCommand;
use pocketmine\command\CommandSender;
use pocketmine\level\Level;
use pocketmine\Player;
use pocketmine\Server;

class LeaveCommand extends BaseSubCommand {

    private $plugin;

    public function __construct(Loader $plugin)
    {
        $this->plugin = $plugin;
        parent::__construct("leave", "Leave the minigame");
    }

    protected function prepare(): void
    {
        
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
    {
        if(!$sender instanceof Player) {
            $sender->sendMessage("§cOnly players are allowed to use this subcommand!");
            return;
        }
        if($this->plugin->getGameData()->get("leave-to-lobby") == true) {
            $lobbyWorld = $this->plugin->getGameData()->get("lobby-world");
            if(Server::getInstance()->getLevelByName($lobbyWorld) instanceof Level) {
                if($sender instanceof Player) {
                    $sender->teleport(Server::getInstance()->getLevelByName($lobbyWorld)->getSpawnLocation());
                }
            } else {
                $sender->sendMessage("§cThe lobby world doesn't exist. It was probably removed or unloaded. If you are the server administrator, load the level again and retry.");
            }
        } else {
            if(Server::getInstance()->getLevelByName(EventListener::getInstance()->lastworld[strtolower($sender->getName())]) instanceof Level) {
                if($sender instanceof Player) {
                    $sender->teleport(Server::getInstance()->getLevelByName(EventListener::getInstance()->lastworld[strtolower($sender->getName())])->getSpawnLocation());
                }
            } else {
                $sender->sendMessage("§cThe world you joined the minigame from doesn't exist. It was probably removed or unloaded. If you are the server administrator, load the level again and retry.");
            }
        }
    }

}