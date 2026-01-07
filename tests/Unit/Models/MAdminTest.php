<?php

namespace Tests\Unit\Models;

require_once __DIR__ . '/../../TestCase.php';

// Lightweight Database stub for testing
if (!class_exists('\\Database')) {
    eval('namespace { class Database { public function query($sql) {} public function bind($p,$v,$t=null) {} public function single() {} public function resultSet() {} public function execute() {} public function rowCount() {} public function lastInsertId() {} public function beginTransaction() {} public function commit() {} public function rollBack() {} } }');
}

use Tests\TestCase;

#[\PHPUnit\Framework\Attributes\AllowMockObjectsWithoutExpectations]
class MAdminTest extends TestCase
{
    private $dbMock;
    private $model;

    protected function setUp(): void
    {
        parent::setUp();

        require_once __DIR__ . '/../../../app/models/M_admin.php';

        $this->dbMock = $this->createMock(\Database::class);
        $this->model = new \M_admin();

        // Inject mock DB
        $ref = new \ReflectionClass($this->model);
        $prop = $ref->getProperty('db');
        $prop->setAccessible(true);
        $prop->setValue($this->model, $this->dbMock);
    }

    /** @test */
    public function testGetRecentActivitiesReturnsResultset()
    {
        $expected = [(object)['id' => 1, 'activity_titel' => 'A']];

        $this->dbMock->expects($this->once())
                     ->method('query');
        $this->dbMock->expects($this->once())
                     ->method('bind')
                     ->with(':limit', 50);
        $this->dbMock->expects($this->once())
                     ->method('resultSet')
                     ->willReturn($expected);

        $this->assertSame($expected, $this->model->getRecentActivities(50));
    }

    /** @test */
    public function testInsertRecentActivityExecutes()
    {
        $this->dbMock->expects($this->once())->method('query');
        $this->dbMock->expects($this->exactly(4))->method('bind');
        $this->dbMock->expects($this->once())->method('execute')->willReturn(true);

        $this->assertTrue($this->model->insertRecentActivity('T','D','type', 2));
    }

    /** @test */
    public function testGetPOByIdReturnsSingle()
    {
        $expected = (object)['premise_officer_id' => 7, 'name' => 'X'];
        $this->dbMock->expects($this->once())->method('query');
        $this->dbMock->expects($this->once())->method('bind')->with(':id', 7);
        $this->dbMock->expects($this->once())->method('single')->willReturn($expected);

        $this->assertSame($expected, $this->model->getPOById(7));
    }

    /** @test */
    public function testInsertJobApplicationExecutes()
    {
        $data = ['completed' => '', 'description' => 'd', 'qualifications' => 'q', 'due_date' => '2026-01-01'];

        $this->dbMock->expects($this->once())->method('query');
        $this->dbMock->expects($this->exactly(5))->method('bind');
        $this->dbMock->expects($this->once())->method('execute')->willReturn(true);
        $this->assertTrue($this->model->insertJobApplication($data, 'po'));
    }

    /** @test */
    public function testEditJobApplicationExecutes()
    {
        $data = ['completed' => '', 'description' => 'd', 'qualifications' => 'q', 'due_date' => '2026-01-01'];

        $this->dbMock = $this->createMock(\Database::class);
        $ref = new \ReflectionClass($this->model);
        $prop = $ref->getProperty('db');
        $prop->setAccessible(true);
        $prop->setValue($this->model, $this->dbMock);

        $this->dbMock->expects($this->once())->method('query');
        $this->dbMock->expects($this->exactly(5))->method('bind');
        $this->dbMock->expects($this->once())->method('execute')->willReturn(true);
        $this->assertTrue($this->model->editJobApplication($data, 'po'));
    }

    /** @test */
    public function testDeleteJobApplicationExecutes()
    {
        $this->dbMock = $this->createMock(\Database::class);
        $ref = new \ReflectionClass($this->model);
        $prop = $ref->getProperty('db');
        $prop->setAccessible(true);
        $prop->setValue($this->model, $this->dbMock);

        $this->dbMock->expects($this->once())->method('query');
        $this->dbMock->expects($this->once())->method('bind')->with(':role', 'po');
        $this->dbMock->expects($this->once())->method('execute')->willReturn(true);
        $this->assertTrue($this->model->deleteJobApplication('po'));
    }

    /** @test */
    public function testGetJobApplicationReturnsSingle()
    {
        $single = (object)['id' => 1, 'role' => 'po'];
        $this->dbMock->expects($this->once())->method('query');
        $this->dbMock->expects($this->once())->method('bind')->with(':role', 'po');
        $this->dbMock->expects($this->once())->method('single')->willReturn($single);

        $this->assertSame($single, $this->model->getJobApplication('po'));
    }

    /** @test */
    public function testChangeStatusExecutes()
    {
        $this->dbMock = $this->createMock(\Database::class);
        $ref = new \ReflectionClass($this->model);
        $prop = $ref->getProperty('db');
        $prop->setAccessible(true);
        $prop->setValue($this->model, $this->dbMock);

        $this->dbMock->expects($this->once())->method('query');
        $this->dbMock->expects($this->exactly(2))->method('bind');
        $this->dbMock->expects($this->once())->method('execute')->willReturn(true);

        $this->assertTrue($this->model->changeStatus('po', 'closed'));
    }

    /** @test */
    public function testGetAdvertisementByIdAndList()
    {
        $expected = (object)['id' => 5, 'title' => 'Ad'];
        $this->dbMock->expects($this->once())->method('query');
        $this->dbMock->expects($this->once())->method('bind')->with(':id', 5);
        $this->dbMock->expects($this->once())->method('single')->willReturn($expected);
        $this->assertSame($expected, $this->model->getAdvertisementById(5));

        // Recreate mock to isolate next call
        $this->dbMock = $this->createMock(\Database::class);
        $ref = new \ReflectionClass($this->model);
        $prop = $ref->getProperty('db'); $prop->setAccessible(true); $prop->setValue($this->model, $this->dbMock);

        $this->dbMock->expects($this->once())->method('query');
        $this->dbMock->expects($this->once())->method('resultSet')->willReturn([$expected]);
        $this->assertSame([$expected], $this->model->getAdvertisements());
    }

    /** @test */
    public function testCreateUpdateDeleteToggleAdvertisement()
    {
        $data = ['title' => 'T','image_path' => 'p','target_roles' => 'r','created_by'=>1,'status'=>'active'];

        $this->dbMock = $this->createMock(\Database::class);
        $ref = new \ReflectionClass($this->model);
        $prop = $ref->getProperty('db'); $prop->setAccessible(true); $prop->setValue($this->model, $this->dbMock);

        $this->dbMock->expects($this->once())->method('query');
        $this->dbMock->expects($this->exactly(5))->method('bind');
        $this->dbMock->expects($this->once())->method('execute')->willReturn(true);
        $this->assertTrue($this->model->createAdvertisement($data));

        $this->dbMock = $this->createMock(\Database::class);
        $prop->setValue($this->model, $this->dbMock);

        $this->dbMock->expects($this->once())->method('query');
        $this->dbMock->expects($this->exactly(5))->method('bind');
        $this->dbMock->expects($this->once())->method('execute')->willReturn(true);
        $this->assertTrue($this->model->updateAdvertisement(1, $data));

        $this->dbMock = $this->createMock(\Database::class);
        $prop->setValue($this->model, $this->dbMock);

        $this->dbMock->expects($this->once())->method('query');
        $this->dbMock->expects($this->once())->method('bind')->with(':id', 1);
        $this->dbMock->expects($this->once())->method('execute')->willReturn(true);
        $this->assertTrue($this->model->deleteAdvertisement(1));

        $this->dbMock = $this->createMock(\Database::class);
        $prop->setValue($this->model, $this->dbMock);

        $this->dbMock->expects($this->once())->method('query');
        $this->dbMock->expects($this->once())->method('bind')->with(':id', 1);
        $this->dbMock->expects($this->once())->method('execute')->willReturn(true);
        $this->assertTrue($this->model->toggleAdvertisementStatus(1));
    }

    /** @test */
    public function testAddSiteReturnsId()
    {
        $data = ['client_id'=>2,'site_name'=>'s','site_address'=>'a','site_city'=>'c','phone_number'=>'077','image_name'=>'i'];
        $this->dbMock->expects($this->once())->method('query');
        $this->dbMock->expects($this->exactly(6))->method('bind');
        $this->dbMock->expects($this->once())->method('execute')->willReturn(true);
        $this->dbMock->expects($this->once())->method('lastInsertId')->willReturn(10);

        $this->assertEquals(10, $this->model->addSite($data));
    }

    /** @test */
    public function testDeleteSiteAndUpdateSiteExecutes()
    {
        $this->dbMock = $this->createMock(\Database::class);
        $ref = new \ReflectionClass($this->model);
        $prop = $ref->getProperty('db'); $prop->setAccessible(true); $prop->setValue($this->model, $this->dbMock);

        $this->dbMock->expects($this->once())->method('query');
        $this->dbMock->expects($this->once())->method('bind')->with(':site_id', 10);
        $this->dbMock->expects($this->once())->method('execute')->willReturn(true);
        $this->assertTrue($this->model->deleteSite(10));

        $dataUp = ['site_name'=>'s','site_address'=>'a','site_city'=>'c','phone_number'=>'077','image_name'=>'i','site_id'=>10];
        $this->dbMock = $this->createMock(\Database::class);
        $prop->setValue($this->model, $this->dbMock);

        $this->dbMock->expects($this->once())->method('query');
        $this->dbMock->expects($this->exactly(6))->method('bind');
        $this->dbMock->expects($this->once())->method('execute')->willReturn(true);
        $this->assertTrue($this->model->updateSite($dataUp));
    }

    /** @test */
    public function testGetSiteByClientIdReturnsArray()
    {
        $this->dbMock = $this->createMock(\Database::class);
        $ref = new \ReflectionClass($this->model);
        $prop = $ref->getProperty('db'); $prop->setAccessible(true); $prop->setValue($this->model, $this->dbMock);

        $this->dbMock->expects($this->once())->method('query');
        $this->dbMock->expects($this->once())->method('bind')->with(':client_id', 2);
        $this->dbMock->expects($this->once())->method('resultSet')->willReturn([(object)[]]);
        $this->assertIsArray($this->model->getSiteByClientId(2));
    }

    /** @test */
    public function testGetAdminsReturnsResultset()
    {
        $this->dbMock->expects($this->once())->method('query');
        $this->dbMock->expects($this->once())->method('resultSet')->willReturn([]);
        $this->assertSame([], $this->model->getAdmins());
    }

    /** @test */
    public function testGetUserByIDReturnsSingle()
    {
        $this->dbMock->expects($this->once())->method('query');
        $this->dbMock->expects($this->once())->method('bind')->with(':userID','U1');
        $this->dbMock->expects($this->once())->method('single')->willReturn((object)['userID'=>'U1']);
        $this->assertNotNull($this->model->getUserByID('U1'));
    }

    /** @test */
    public function testGetUsersByRoleReturnsResultset()
    {
        $this->dbMock->expects($this->once())->method('query');
        $this->dbMock->expects($this->once())->method('bind')->with(':role','admin');
        $this->dbMock->expects($this->once())->method('resultSet')->willReturn([]);
        $this->assertSame([], $this->model->getUsersByRole('admin'));
    }

    /** @test */
    public function testUpdateUserStatusExecutes()
    {
        $this->dbMock = $this->createMock(\Database::class);
        $ref = new \ReflectionClass($this->model);
        $prop = $ref->getProperty('db'); $prop->setAccessible(true); $prop->setValue($this->model, $this->dbMock);

        $this->dbMock->expects($this->once())->method('query');
        $this->dbMock->expects($this->once())->method('query');
        $expected = [[':userID','U99'], [':status','inactive']];
        $call = 0;
        $self = $this;
        $this->dbMock->expects($this->exactly(2))->method('bind')
            ->willReturnCallback(function($param, $value, $type = null) use (&$call, $expected, $self) {
                $self->assertEquals($expected[$call][0], $param);
                $self->assertEquals($expected[$call][1], $value);
                $call++;
            });
        $this->dbMock->expects($this->once())->method('execute')->willReturn(true);
        $this->assertTrue($this->model->updateUserStatus('U99','inactive'));
    }

    /** @test */
    public function testApproveLeaveRequestReturnsTrue()
    {
        $this->dbMock = $this->createMock(\Database::class);
        $ref = new \ReflectionClass($this->model);
        $prop = $ref->getProperty('db'); $prop->setAccessible(true); $prop->setValue($this->model, $this->dbMock);

        $this->dbMock->expects($this->once())->method('query');
        $expected = [[':id',1], [':admin_id',2]];
        $call = 0;
        $self = $this;
        $this->dbMock->expects($this->exactly(2))->method('bind')
            ->willReturnCallback(function($param, $value, $type = null) use (&$call, $expected, $self) {
                $self->assertEquals($expected[$call][0], $param);
                $self->assertEquals($expected[$call][1], $value);
                $call++;
            });
        $this->dbMock->expects($this->once())->method('execute')->willReturn(true);
        $this->dbMock->expects($this->once())->method('rowCount')->willReturn(1);
        $this->assertTrue($this->model->approveLeaveRequest(1, 2));
    }

    /** @test */
    public function testRejectLeaveRequestReturnsTrue()
    {
        $this->dbMock = $this->createMock(\Database::class);
        $ref = new \ReflectionClass($this->model);
        $prop = $ref->getProperty('db'); $prop->setAccessible(true); $prop->setValue($this->model, $this->dbMock);

        $this->dbMock->expects($this->once())->method('query');
        $expected = [[':id',1], [':admin_id',2], [':reason','r']];
        $call = 0;
        $self = $this;
        $this->dbMock->expects($this->exactly(3))->method('bind')
            ->willReturnCallback(function($param, $value, $type = null) use (&$call, $expected, $self) {
                $self->assertEquals($expected[$call][0], $param);
                $self->assertEquals($expected[$call][1], $value);
                $call++;
            });
        $this->dbMock->expects($this->once())->method('execute')->willReturn(true);
        $this->dbMock->expects($this->once())->method('rowCount')->willReturn(1);
        $this->assertTrue($this->model->rejectLeaveRequest(1,2,'r'));
    }

    /** @test */
    public function testGetAllServiceRequestsReturnsEmpty()
    {
        $this->dbMock->expects($this->once())->method('query');
        $this->dbMock->expects($this->once())->method('resultSet')->willReturn([]);
        $this->assertSame([], $this->model->getAllServiceRequests());
    }

    /** @test */
    public function testGetServiceRequestByIdReturnsSingle()
    {
        $this->dbMock->expects($this->once())->method('query');
        $this->dbMock->expects($this->once())->method('bind')->with(':id', 1);
        $this->dbMock->expects($this->once())->method('single')->willReturn((object)['id'=>1]);
        $this->assertNotNull($this->model->getServiceRequestById(1));
    }

    /** @test */
    public function testUpdateServiceRequestStatusExecutes()
    {
        $this->dbMock = $this->createMock(\Database::class);
        $ref = new \ReflectionClass($this->model);
        $prop = $ref->getProperty('db'); $prop->setAccessible(true); $prop->setValue($this->model, $this->dbMock);

        $expected = [[':id',1], [':status','Approved']];
        $call = 0;
        $self = $this;
        $this->dbMock->expects($this->once())->method('query');
        $this->dbMock->expects($this->exactly(2))->method('bind')
            ->willReturnCallback(function($param, $value, $type = null) use (&$call, $expected, $self) {
                $self->assertEquals($expected[$call][0], $param);
                $self->assertEquals($expected[$call][1], $value);
                $call++;
            });
        $this->dbMock->expects($this->once())->method('execute')->willReturn(true);
        $this->assertTrue($this->model->updateServiceRequestStatus(1,'Approved'));
    }

    /** @test */
    public function testGetServiceRequestStatsReturnsObject()
    {
        $this->dbMock->expects($this->once())->method('query');
        $this->dbMock->expects($this->once())->method('single')->willReturn((object)['pending'=>1,'approved'=>2,'rejected'=>0,'total'=>3]);
        $this->assertIsObject($this->model->getServiceRequestStats());
    }
}
