<?php

use PHPUnit\Framework\TestCase;
use App\MasterClass;

class MasterClassTest extends TestCase
{
    private MasterClass $masterClass;

    protected function setUp(): void
    {
        $this->masterClass = new MasterClass();
    }

    public function testSuccessfulRegistration(): void
    {
        $result = $this->masterClass->register(
            'Иван Петров',
            25,
            'Керамика',
            true,
            'offline'
        );

        $this->assertTrue($result['success']);
        $this->assertEquals('Иван Петров', $result['data']['name']);
        $this->assertEquals(25, $result['data']['age']);
        $this->assertEquals('Керамика', $result['data']['theme']);
        $this->assertTrue($result['data']['materials_included']);
        $this->assertEquals('offline', $result['data']['format']);
    }

    public function testEmptyName(): void
    {
        $result = $this->masterClass->register('', 25, 'Рисование', false, 'online');
        $this->assertFalse($result['success']);
        $this->assertContains('Имя не может быть пустым', $result['errors']);
    }

    public function testInvalidAge(): void
    {
        $result = $this->masterClass->register('Анна', 3, 'Лепка', true, 'offline');
        $this->assertFalse($result['success']);
        $this->assertContains('Возраст должен быть от 5 до 120 лет', $result['errors']);
    }

    public function testInvalidFormat(): void
    {
        $result = $this->masterClass->register('Петр', 30, 'Фотография', false, 'unknown');
        $this->assertFalse($result['success']);
        $this->assertContains('Неверный формат участия', $result['errors']);
    }

    public function testGetAllRegistrations(): void
    {
        $this->masterClass->register('Иван', 20, 'Керамика', true, 'offline');
        $this->masterClass->register('Мария', 22, 'Рисование', false, 'online');
        $this->masterClass->register('Алексей', 28, 'Фотография', true, 'offline');

        $all = $this->masterClass->getAll();
        $this->assertCount(3, $all);
        $this->assertEquals(3, $this->masterClass->getCount());
    }

    public function testFilterByFormat(): void
    {
        $this->masterClass->register('Иван', 20, 'Керамика', true, 'offline');
        $this->masterClass->register('Мария', 22, 'Рисование', false, 'online');
        $this->masterClass->register('Алексей', 28, 'Фотография', true, 'offline');

        $online = $this->masterClass->getByFormat('online');
        $offline = $this->masterClass->getByFormat('offline');

        $this->assertCount(1, $online);
        $this->assertCount(2, $offline);
    }
}