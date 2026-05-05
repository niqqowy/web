<?php

namespace App;

use Predis\Client;

class RedisExample
{
    private Client $redis;

    public function __construct(string $host = 'redis', int $port = 6379)
    {
        $this->redis = new Client([
            'scheme' => 'tcp',
            'host'   => $host,
            'port'   => $port,
        ]);
    }

    public function saveGameScore(string $playerName, int $score): void
    {
        $this->redis->zadd('games:leaderboard', [$playerName => $score]);
    }

    public function getTopPlayers(int $limit = 10): array
    {
        return $this->redis->zrevrange('games:leaderboard', 0, $limit - 1, ['withscores' => true]);
    }

    public function getPlayerScore(string $playerName): ?int
    {
        $score = $this->redis->zscore('games:leaderboard', $playerName);
        return $score !== null ? (int)$score : null;
    }

    public function setGameSession(string $sessionId, array $data): void
    {
        $this->redis->setex("game:session:{$sessionId}", 3600, json_encode($data));
    }

    public function getGameSession(string $sessionId): ?array
    {
        $data = $this->redis->get("game:session:{$sessionId}");
        return $data ? json_decode($data, true) : null;
    }

    public function clearAll(): void
    {
        $this->redis->flushdb();
    }
}