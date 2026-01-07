<?php

namespace Tests\Unit\Controllers;

require_once __DIR__ . '/../../TestCase.php';

// Small Admin model stub specific for getJobApplication
if (!class_exists('\\Tests\\Unit\\Controllers\\AdminJobModelStub')) {
    class AdminJobModelStub { public function getJobApplication($role) {} }
}

use Tests\TestCase;

#[\PHPUnit\Framework\Attributes\AllowMockObjectsWithoutExpectations]
class HomeControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        // Ensure base Controller class is available
        require_once __DIR__ . '/../../../app/libraries/Controller.php';
        require_once __DIR__ . '/../../../app/controllers/Home.php';
    }

    /** @test */
    public function testIndexRendersView()
    {
        $home = $this->getMockBuilder(\Home::class)
                     ->disableOriginalConstructor()
                     ->onlyMethods(['view'])
                     ->getMock();

        $home->expects($this->once())
             ->method('view')
             ->with('home/index', $this->callback(function($data){
                 return isset($data['title']) && strpos($data['title'], 'Welcome') !== false;
             }));

        $home->index();
    }

    /** @test */
    public function testServiceGetRendersServicesView()
    {
        $home = $this->getMockBuilder(\Home::class)
                     ->disableOriginalConstructor()
                     ->onlyMethods(['view'])
                     ->getMock();

        $home->expects($this->once())->method('view')->with('home/v_services', $this->isArray());
        // Ensure a GET request
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $home->service();
    }

    /** @test */
    public function testSubmitApplicationNotOpenedShowsNotOpenedView()
    {
        $home = $this->getMockBuilder(\Home::class)
                     ->disableOriginalConstructor()
                     ->onlyMethods(['view'])
                     ->getMock();

        $adminModel = $this->createMock(AdminJobModelStub::class);
        $adminModel->expects($this->once())->method('getJobApplication')->with('po')->willReturn(null);

        $ref = new \ReflectionClass(\Home::class);
        $prop = $ref->getProperty('adminModel'); $prop->setAccessible(true); $prop->setValue($home, $adminModel);

        $home->expects($this->once())->method('view')->with('home/v_not_opened');

        $home->submit_application('po');
    }

    /** @test */
    public function testSubmitApplicationOpenRendersJobApplicationViewOnGet()
    {
        $home = $this->getMockBuilder(\Home::class)
                     ->disableOriginalConstructor()
                     ->onlyMethods(['view'])
                     ->getMock();

        $exist = (object)[ 'status' => 'open', 'description' => 'desc', 'qualifications' => 'qual', 'due_date' => '2099-12-31' ];

        $adminModel = $this->createMock(AdminJobModelStub::class);
        $adminModel->expects($this->once())->method('getJobApplication')->with('po')->willReturn($exist);

        $ref = new \ReflectionClass(\Home::class);
        $prop = $ref->getProperty('adminModel'); $prop->setAccessible(true); $prop->setValue($home, $adminModel);

        $home->expects($this->once())
             ->method('view')
             ->with('home/v_job_application', $this->callback(function($data) use ($exist) {
                 return isset($data['description']) && $data['description'] === $exist->description && $data['role'] === 'po';
             }));

        $home->submit_application('po');
    }

    /** @test */
    public function testSubmitApplicationPostIncompleteShowsJobApplicationWithErrors()
    {
        $home = $this->getMockBuilder(\Home::class)
                     ->disableOriginalConstructor()
                     ->onlyMethods(['view'])
                     ->getMock();

        $exist = (object)[ 'status' => 'open', 'description' => 'desc', 'qualifications' => 'qual', 'due_date' => '2099-12-31' ];

        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST = [ 'name' => '', 'email' => '', 'phone' => '' ];
        $_FILES = [ 'image' => ['name' => '','tmp_name'=>'','size' => 0], 'cv' => ['name'=>'','tmp_name'=>'','size'=>0] ];

        $adminModel = $this->createMock(AdminJobModelStub::class);
        $adminModel->expects($this->once())->method('getJobApplication')->with('po')->willReturn($exist);

        $ref = new \ReflectionClass(\Home::class);
        $prop = $ref->getProperty('adminModel'); $prop->setAccessible(true); $prop->setValue($home, $adminModel);

        $home->expects($this->once())
             ->method('view')
             ->with('home/v_job_application', $this->callback(function($data){
                 return isset($data['name_err']) && $data['name_err'] !== '';
             }));

        $home->submit_application('po');

        // cleanup
        unset($_SERVER['REQUEST_METHOD']); $_POST = []; $_FILES = [];
    }

    /** @test */
    public function testPremiseOfficerDelegatesToSubmitApplication()
    {
        $home = $this->getMockBuilder(\Home::class)
                     ->disableOriginalConstructor()
                     ->onlyMethods(['submit_application'])
                     ->getMock();

        $home->expects($this->once())->method('submit_application')->with('po');

        $home->premise_officer();
    }
}

