<?php

use PHPUnit\Framework\TestCase;
use App\RedisExample;

class RedisExampleTest extends TestCase
{
    private RedisExample $redis;

    protected function setUp(): void
    {
       
        $maxAttempts = 30;
        $connected = false;
        
        for ($i = 0; $i < $maxAttempts; $i++) {
            try {
                $this->redis = new RedisExample('redis', 6379);
                $this->redis->getPlayerScore('test');
                $connected = true;
                break;
            } catch (\Exception $e) {
                sleep(1);
            }
        }
        
        if (!$connected) {
            throw new \RuntimeException('Не удалось подключиться к Redis');
        }
        
        $this->redis->clearAll();
    }

    public function testSaveAndGetGameScore(): void
    {
        $this->redis->saveGameScore('Player1', 1000);
        $this->redis->saveGameScore('Player2', 2500);
        $this->redis->saveGameScore('Player3', 500);

        $this->assertEquals(2500, $this->redis->getPlayerScore('Player2'));
        $this->assertEquals(1000, $this->redis->getPlayerScore('Player1'));
        $this->assertNull($this->redis->getPlayerScore('Unknown'));
    }

    public function testGetTopPlayers(): void
    {
        $this->redis->saveGameScore('Alice', 3000);
        $this->redis->saveGameScore('Bob', 1500);
        $this->redis->saveGameScore('Charlie', 4500);
        $this->redis->saveGameScore('Diana', 2000);

        $top = $this->redis->getTopPlayers(3);

        $this->assertCount(3, $top);
        $this->assertEquals('Charlie', array_key_first($top));
        $this->assertEquals(4500, $top['Charlie']);
    }

    public function testSetAndGetGameSession(): void
    {
        $sessionData = [
            'game' => 'Chess',
            'level' => 5,
            'moves' => 120
        ];

        $this->redis->setGameSession('session123', $sessionData);
        $retrieved = $this->redis->getGameSession('session123');

        $this->assertNotNull($retrieved);
        $this->assertEquals('Chess', $retrieved['game']);
        $this->assertEquals(5, $retrieved['level']);
        $this->assertEquals(120, $retrieved['moves']);
    }

    public function testGetNonExistentSession(): void
    {
        $result = $this->redis->getGameSession('nonexistent');
        $this->assertNull($result);
    }

    public function testTopPlayersLimit(): void
    {
        for ($i = 1; $i <= 20; $i++) {
            $this->redis->saveGameScore("Player{$i}", $i * 100);
        }

        $top10 = $this->redis->getTopPlayers(10);
        $this->assertCount(10, $top10);
    }
}