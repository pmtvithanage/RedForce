<?php

namespace Tests\Unit\Models;

require_once __DIR__ . '/../../TestCase.php';

// Provide a lightweight Database stub for mocking when real Database requires environment constants
// Create a lightweight global Database stub with the methods used by the model
if (!class_exists('\\Database')) {
    eval('namespace { class Database { public function query($sql) {} public function bind($param, $value, $type = null) {} public function single() {} public function resultSet() {} public function execute() {} public function rowCount() {} public function lastInsertId() {} public function beginTransaction() {} public function commit() {} public function rollBack() {} } }');
}

use Tests\TestCase;

#[\PHPUnit\Framework\Attributes\AllowMockObjectsWithoutExpectations]
class UserTest extends TestCase
{
    private $dbMock;
    private $model;

    protected function setUp(): void
    {
        parent::setUp();

        // Ensure the model file is loaded (models are not namespaced)
        require_once __DIR__ . '/../../../app/models/M_users.php';

        $this->dbMock = $this->createMock(\Database::class);
        $this->model = new \M_users($this->dbMock);
    }

    /** @test */
    public function testGetUsersReturnsResultset()
    {
        $expected = [(object)['id' => 1, 'name' => 'John']];

        $this->dbMock->expects($this->once())
                     ->method('query')
                     ->with("SELECT * FROM Users");

        $this->dbMock->expects($this->once())
                     ->method('resultSet')
                     ->willReturn($expected);

        $this->assertSame($expected, $this->model->getUsers());
    }

    /** @test */
    public function testFindUserByUserIDFound()
    {
        $this->dbMock->expects($this->once())
                     ->method('query')
                     ->with("SELECT * FROM Users WHERE userID = :userID");

        $this->dbMock->expects($this->once())
                     ->method('bind')
                     ->with(':userID', 'U1');

        $this->dbMock->method('single')->willReturn((object)['userID' => 'U1']);
        $this->dbMock->method('rowCount')->willReturn(1);

        $this->assertTrue($this->model->findUserByUserID('U1'));
    }

    /** @test */
    public function testFindUserByUserIDNotFound()
    {
        $this->dbMock->expects($this->once())
                     ->method('query')
                     ->with("SELECT * FROM Users WHERE userID = :userID");

        $this->dbMock->expects($this->once())
                     ->method('bind')
                     ->with(':userID', 'U1');

        $this->dbMock->method('single')->willReturn(null);
        $this->dbMock->method('rowCount')->willReturn(0);

        $this->assertFalse($this->model->findUserByUserID('U1'));
    }

    /** @test */
    public function testLoginAuthenticatesWithCorrectPassword()
    {
        $hashed = password_hash('secret', PASSWORD_DEFAULT);
        $userObj = (object)['userID' => 'U1', 'password' => $hashed];

        $this->dbMock->method('query');
        $this->dbMock->method('bind');
        $this->dbMock->method('single')->willReturn($userObj);
        $this->dbMock->method('rowCount')->willReturn(1);

        $result = $this->model->login('U1', 'secret');
        $this->assertSame($userObj, $result);
    }

    /** @test */
    public function testLoginReturnsFalseWithWrongPassword()
    {
        $this->dbMock->method('query');
        $this->dbMock->method('bind');
        $this->dbMock->method('single')->willReturn((object)['userID' => 'U1', 'password' => password_hash('other', PASSWORD_DEFAULT)]);
        $this->dbMock->method('rowCount')->willReturn(1);

        $this->assertFalse($this->model->login('U1', 'secret'));
    }

    /** @test */
    public function testCreateUserReturnsTrueOnSuccess()
    {
        $data = ['userID' => 'U1', 'name' => 'John', 'email' => 'john@example.com', 'password' => 'secret', 'role' => 'user'];

        $this->dbMock->expects($this->once())
                     ->method('query')
                     ->with($this->stringContains('INSERT INTO Users'));

        $this->dbMock->expects($this->exactly(5))
                     ->method('bind');

        $this->dbMock->expects($this->once())
                     ->method('execute')
                     ->willReturn(true);

        $this->assertTrue($this->model->createUser($data));
    }

    /** @test */
    public function testUpdatePasswordValidatesCurrentAndUpdates()
    {
        $hashed = password_hash('old', PASSWORD_DEFAULT);
        $this->dbMock->method('query');
        $this->dbMock->method('bind');
        $this->dbMock->method('single')->willReturn((object)['password' => $hashed]);
        $this->dbMock->expects($this->once())
                     ->method('execute')
                     ->willReturn(true);

        $this->assertTrue($this->model->updatePassword(1, 'old', 'newpass'));
    }

    /** @test */
    public function testUpdatePasswordFailsWithWrongCurrentPassword()
    {
        $this->dbMock->method('query');
        $this->dbMock->method('bind');
        $this->dbMock->method('single')->willReturn((object)['password' => password_hash('different', PASSWORD_DEFAULT)]);

        $this->assertFalse($this->model->updatePassword(1, 'old', 'newpass'));
    }

    /** @test */
    public function testRegisterCommitsOnSuccess()
    {
        $data = ['userID' => 'U1', 'name' => 'John', 'email' => 'john@example.com', 'password' => 'pwd', 'role' => 'user'];

        $this->dbMock->expects($this->once())->method('beginTransaction')->willReturn(true);
        $this->dbMock->expects($this->exactly(2))->method('execute')->willReturn(true);
        $this->dbMock->expects($this->once())->method('lastInsertId')->willReturn(1);
        $this->dbMock->expects($this->once())->method('commit')->willReturn(true);

        $this->assertTrue($this->model->register($data));
    }

    /** @test */
    public function testRegisterReturnsFalseOnFailure()
    {
        $data = ['userID' => 'U1', 'name' => 'John', 'email' => 'john@example.com', 'password' => 'pwd', 'role' => 'user'];

        $this->dbMock->expects($this->once())->method('beginTransaction')->willReturn(true);
        $this->dbMock->expects($this->once())->method('execute')->willReturn(false);
        $this->dbMock->expects($this->once())->method('rollBack')->willReturn(true);

        $this->assertFalse($this->model->register($data));
    }

    /** @test */
    public function testGetUserByIdReturnsSingleRecord()
    {
        $expected = (object)['id' => 5, 'name' => 'Alice'];

        $this->dbMock->expects($this->once())->method('query')->with("SELECT * FROM Users WHERE id = :id");
        $this->dbMock->expects($this->once())->method('bind')->with(':id', 5);
        $this->dbMock->expects($this->once())->method('single')->willReturn($expected);

        $this->assertSame($expected, $this->model->getUserById(5));
    }

    /** @test */
    public function testGetUserByUserIDReturnsSingleRecord()
    {
        $expected = (object)['userID' => 'U1', 'name' => 'Bob'];

        $this->dbMock->expects($this->once())->method('query')->with("SELECT * FROM Users WHERE userID = :userID");
        $this->dbMock->expects($this->once())->method('bind')->with(':userID', 'U1');
        $this->dbMock->expects($this->once())->method('single')->willReturn($expected);

        $this->assertSame($expected, $this->model->getUserByUserID('U1'));
    }

    /** @test */
    public function testUpdateUserReturnsTrueOnSuccess()
    {
        $data = ['id' => 2, 'name' => 'New', 'email' => 'new@example.com', 'role' => 'user'];

        $this->dbMock->expects($this->once())->method('query')->with($this->stringContains('UPDATE Users'));
        $this->dbMock->expects($this->exactly(4))->method('bind');
        $this->dbMock->expects($this->once())->method('execute')->willReturn(true);

        $this->assertTrue($this->model->updateUser($data));
    }

    /** @test */
    public function testDeleteUserReturnsTrueOnSuccess()
    {
        $this->dbMock->expects($this->once())->method('query')->with("DELETE FROM Users WHERE id = :id");
        $this->dbMock->expects($this->once())->method('bind')->with(':id', 3);
        $this->dbMock->expects($this->once())->method('execute')->willReturn(true);

        $this->assertTrue($this->model->deleteUser(3));
    }

    /** @test */
    public function testUpdateContactReturnsTrue()
    {
        $this->dbMock->expects($this->once())->method('query')->with($this->stringContains('UPDATE Users SET contact'));
        $this->dbMock->expects($this->exactly(2))->method('bind');
        $this->dbMock->expects($this->once())->method('execute')->willReturn(true);

        $this->assertTrue($this->model->updateContact(1, '0771234567'));
    }

    /** @test */
    public function testUpdateEmailFailsWhenEmailExistsAndSucceedsOtherwise()
    {
        // Email already exists -> return false
        $this->dbMock->expects($this->atLeastOnce())->method('query');
        $this->dbMock->method('bind');
        $this->dbMock->method('single')->willReturn((object)['id' => 99]);

        $this->assertFalse($this->model->updateEmail(1, 'taken@example.com'));

        // Email not exists -> execute returns true
        $this->dbMock = $this->createMock(\Database::class);
        $this->model = new \M_users($this->dbMock);

        // updateEmail performs a SELECT to check existing email, then UPDATE, so expect 2 queries
        $this->dbMock->expects($this->exactly(2))->method('query');
        // Two binds for SELECT and two for UPDATE = 4 binds total
        $this->dbMock->expects($this->exactly(4))->method('bind');
        $this->dbMock->expects($this->once())->method('execute')->willReturn(true);

        $this->assertTrue($this->model->updateEmail(1, 'free@example.com'));
    }

    /** @test */
    public function testUpdateProfileImageReturnsTrue()
    {
        $this->dbMock->expects($this->once())->method('query');
        $this->dbMock->expects($this->exactly(2))->method('bind');
        $this->dbMock->expects($this->once())->method('execute')->willReturn(true);

        $this->assertTrue($this->model->updateProfileImage(1, 'path/to/image.png'));
    }

    /** @test */
    public function testGetUsersByRoleReturnsResultset()
    {
        $expected = [(object)['id' => 1, 'role' => 'admin']];

        $this->dbMock->expects($this->once())->method('query')->with("SELECT * FROM Users WHERE role = :role");
        $this->dbMock->expects($this->once())->method('bind')->with(':role', 'admin');
        $this->dbMock->expects($this->once())->method('resultSet')->willReturn($expected);

        $this->assertSame($expected, $this->model->getUsersByRole('admin'));
    }

    /** @test */
    public function testUserAndEmailExistenceAndCountHelpers()
    {
        // userIDExists and emailExists -> true when rowCount > 0
        $this->dbMock->expects($this->any())->method('query');
        $this->dbMock->method('bind');
        $this->dbMock->method('single')->willReturn((object)['any' => 'val']);
        $this->dbMock->method('rowCount')->willReturn(1);

        $this->assertTrue($this->model->userIDExists('U1'));
        $this->assertTrue($this->model->emailExists('x@example.com'));
        $this->assertTrue($this->model->findUserByEmail('x@example.com'));

        // getUserCountByRole
        $this->dbMock = $this->createMock(\Database::class);
        $this->model = new \M_users($this->dbMock);

        $this->dbMock->expects($this->once())->method('query');
        $this->dbMock->expects($this->once())->method('bind')->with(':role', 'user');
        $this->dbMock->expects($this->once())->method('single')->willReturn((object)['count' => 5]);

        $this->assertEquals(5, $this->model->getUserCountByRole('user'));
    }
}
