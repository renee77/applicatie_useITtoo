<?php
use PHPUnit\Framework\TestCase;
use App\Models\User;

class UserTest extends TestCase {
    public function testGetName(): void {
        $user = new User(1, 'Eva', 'eva@example.com');
        $this->assertEquals('Eva', $user->getName());
    }

    public function testGetEmail(): void {
        $user = new User(1, 'Eva', 'eva@example.com');
        $this->assertEquals('eva@example.com', $user->getEmail());
    }
}
