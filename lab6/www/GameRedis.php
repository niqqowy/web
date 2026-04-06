<?php
namespace App;

use Predis\Client;

class GameRedis {
    private Client $redis;

    public function __construct() {
        $this->redis = new Client('tcp://redis:6379');
    }

    public function setPlayerProfile(string $playerId, string $name, int $level, int $score): void {
        $this->redis->hmset("player:$playerId", [
            'name'  => $name,
            'level' => $level,
            'score' => $score
        ]);
    }

    public function getPlayerProfile(string $playerId): array {
        $profile = $this->redis->hgetall("player:$playerId");
        return is_array($profile) ? $profile : [];
    }

    public function updateLevel(string $playerId, int $newLevel): void {
        $this->redis->hset("player:$playerId", 'level', $newLevel);
    }

    public function addScore(string $playerId, int $points): int {
        $newScore = $this->redis->hincrby("player:$playerId", 'score', $points);
        $this->redis->zadd("leaderboard", [$playerId => $newScore]);
        return $newScore;
    }

    public function getTopPlayers(int $limit = 10): array {
        $result = $this->redis->zrevrange("leaderboard", 0, $limit - 1, 'WITHSCORES');

        if (empty($result) || !is_array($result)) {
            return [];
        }
        
        $top = [];
        $keys = array_keys($result);
        
        for ($i = 0; $i < count($keys); $i += 2) {
            $playerId = $keys[$i];
            $score = $result[$keys[$i]];
        
            if (empty($playerId)) {
                continue;
            }
            
            $profile = $this->getPlayerProfile((string)$playerId);
            $top[] = [
                'id'    => (string)$playerId,
                'name'  => $profile['name'] ?? 'Unknown',
                'score' => (int)$score,
                'level' => isset($profile['level']) ? (int)$profile['level'] : 0
            ];
        }
        
        return $top;
    }

    public function deletePlayer(string $playerId): void {
        $this->redis->del("player:$playerId");
        $this->redis->zrem("leaderboard", $playerId);
    }

    public function getTotalPlayers(): int {
        $count = $this->redis->zcard("leaderboard");
        return is_int($count) ? $count : 0;
    }
}