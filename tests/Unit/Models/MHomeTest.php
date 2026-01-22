<?php

namespace Tests\Unit\Models;

require_once __DIR__ . '/../../TestCase.php';

// Lightweight Database stub for testing
if (!class_exists('\\Database')) {
    eval('namespace { class Database { public function query($sql) {} public function bind($p,$v,$t=null) {} public function single() {} public function resultSet() {} public function execute() {} public function rowCount() {} } }');
}

use Tests\TestCase;

#[\PHPUnit\Framework\Attributes\AllowMockObjectsWithoutExpectations]
class MHomeTest extends TestCase
{
    private $dbMock;
    private $model;

    protected function setUp(): void
    {
        parent::setUp();

        require_once __DIR__ . '/../../../app/models/M_home.php';

        $this->dbMock = $this->createMock(\Database::class);
        $this->model = new \M_home();

        // Inject mock DB
        $ref = new \ReflectionClass($this->model);
        $prop = $ref->getProperty('db');
        $prop->setAccessible(true);
        $prop->setValue($this->model, $this->dbMock);
    }

    /** @test */
    public function testSaveJobApplicationExecutes()
    {
        $data = ['name'=>'N','email'=>'e','phone'=>'p','birthday'=>'b','national_id'=>'n','gender'=>'g','address'=>'a','district'=>'d','city'=>'c','image_name'=>'i','cv_name'=>'cv'];

        $this->dbMock->expects($this->once())->method('query');
        $this->dbMock->expects($this->exactly(12))->method('bind');
        $this->dbMock->expects($this->once())->method('execute')->willReturn(true);

        $this->assertTrue($this->model->saveJobApplication($data, 'po'));
    }

    /** @test */
    public function testGetPendingAndApprovedRejectedApplications()
    {
        // pending by role
        $this->dbMock = $this->createMock(\Database::class);
        $ref = new \ReflectionClass($this->model);
        $prop = $ref->getProperty('db'); $prop->setAccessible(true); $prop->setValue($this->model, $this->dbMock);
        $this->dbMock->expects($this->once())->method('query');
        $this->dbMock->expects($this->once())->method('bind')->with(':role', 'po');
        $this->dbMock->expects($this->once())->method('resultSet')->willReturn([]);
        $this->assertSame([], $this->model->getPendingOfficerApplications('po'));

        // all pending
        $this->dbMock = $this->createMock(\Database::class);
        $prop->setValue($this->model, $this->dbMock);
        $this->dbMock->expects($this->once())->method('query');
        $this->dbMock->expects($this->once())->method('resultSet')->willReturn([]);
        $this->assertSame([], $this->model->getAllPendingOfficerApplications());

        // approved by role
        $this->dbMock = $this->createMock(\Database::class);
        $prop->setValue($this->model, $this->dbMock);
        $this->dbMock->expects($this->once())->method('query');
        $this->dbMock->expects($this->once())->method('bind')->with(':role', 'po');
        $this->dbMock->expects($this->once())->method('resultSet')->willReturn([]);
        $this->assertSame([], $this->model->getApprovedOfficerApplications('po'));

        // all approved
        $this->dbMock = $this->createMock(\Database::class);
        $prop->setValue($this->model, $this->dbMock);
        $this->dbMock->expects($this->once())->method('query');
        $this->dbMock->expects($this->once())->method('resultSet')->willReturn([]);
        $this->assertSame([], $this->model->getAllApprovedOfficerApplications());

        // rejected by role
        $this->dbMock = $this->createMock(\Database::class);
        $prop->setValue($this->model, $this->dbMock);
        $this->dbMock->expects($this->once())->method('query');
        $this->dbMock->expects($this->once())->method('bind')->with(':role', 'po');
        $this->dbMock->expects($this->once())->method('resultSet')->willReturn([]);
        $this->assertSame([], $this->model->getRejectedOfficerApplications('po'));

        // all rejected
        $this->dbMock = $this->createMock(\Database::class);
        $prop->setValue($this->model, $this->dbMock);
        $this->dbMock->expects($this->once())->method('query');
        $this->dbMock->expects($this->once())->method('resultSet')->willReturn([]);
        $this->assertSame([], $this->model->getAllRejectedOfficerApplications());
    }

    /** @test */
    public function testServiceRequestSaveAndGet()
    {
        $data = ['company_name'=>'C','email'=>'e','phone_number'=>'p','contact_person_name'=>'cp','image_name'=>'i'];
        $this->dbMock->expects($this->once())->method('query');
        $this->dbMock->expects($this->exactly(5))->method('bind');
        $this->dbMock->expects($this->once())->method('execute')->willReturn(true);
        $this->assertTrue($this->model->saveServiceRequest($data));

        $this->dbMock = $this->createMock(\Database::class);
        $ref = new \ReflectionClass($this->model);
        $prop = $ref->getProperty('db'); $prop->setAccessible(true); $prop->setValue($this->model, $this->dbMock);
        $this->dbMock->expects($this->once())->method('query');
        $this->dbMock->expects($this->once())->method('resultSet')->willReturn([]);
        $this->assertSame([], $this->model->getPendingRequest());

        $this->dbMock = $this->createMock(\Database::class);
        $prop->setValue($this->model, $this->dbMock);
        $this->dbMock->expects($this->once())->method('query');
        $this->dbMock->expects($this->once())->method('resultSet')->willReturn([]);
        $this->assertSame([], $this->model->getApprovedRequest());

        $this->dbMock = $this->createMock(\Database::class);
        $prop->setValue($this->model, $this->dbMock);
        $this->dbMock->expects($this->once())->method('query');
        $this->dbMock->expects($this->once())->method('resultSet')->willReturn([]);
        $this->assertSame([], $this->model->getRejectedRequest());
    }
}
