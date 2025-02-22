<?php
require_once dirname(__FILE__) . '/cdash_test_case.php';
require_once 'include/common.php';

use CDash\Model\Build;
use CDash\Model\BuildInformation;

class SubmissionAssignBuildIdTestCase extends KWWebTestCase
{
    public function testSubmissionAssignBuildId()
    {
        $begin_test_time = time();
        $file_to_submit = dirname(__FILE__) .  '/data/AssignBuildId/Configure.xml';
        $buildid = $this->submission_assign_buildid(
            $file_to_submit, 'InsightExample', 'assign_buildid',
            'localhost', '20180918-0100-Nightly');
        if (!$buildid || !is_numeric($buildid)) {
            $this->fail('Did not receive a numeric buildid for submission');
        }

        $build = new Build();
        $build->Id = $buildid;
        if (!$build->Exists()) {
            $this->fail("Build #$buildid does not exist");
        }

        $build->FillFromId($buildid);
        $this->assertEqual('assign_buildid', $build->Name);
        $this->assertTrue(strtotime($build->SubmitTime) >= $begin_test_time);
        $this->assertEqual('2018-09-18 14:27:07', $build->StartTime);
        $this->assertEqual('2018-09-18 14:27:08', $build->EndTime);
        $this->assertEqual('ctest-3.13.0', $build->Generator);

        // Make sure a buildinformation row was created too.
        $buildinformation = new BuildInformation();
        $buildinformation->BuildId = $buildid;
        $buildinformation->Fill();
        $this->assertEqual('Linux', $buildinformation->OSName);
        $this->assertEqual('x86_64', $buildinformation->OSPlatform);
        $this->assertEqual('4.4.0', $buildinformation->OSRelease);
        $this->assertEqual('#166', $buildinformation->OSVersion);
        $this->assertEqual('gcc', $buildinformation->CompilerName);
        $this->assertEqual('5.5.0', $buildinformation->CompilerVersion);

        remove_build($buildid);
    }
}
