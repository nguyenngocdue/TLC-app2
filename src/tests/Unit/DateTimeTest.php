<?php

namespace Tests\Unit;

use App\Utils\Support\DateTimeConcern;
use PHPUnit\Framework\TestCase;

class DateTimeTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_date_time_concern_convertForLoading()
    {
        $result = DateTimeConcern::convertForLoading("picker_datetime", "2022-01-31 00:11:22");
        $this->assertEquals($result, "31/01/2022 00:11");

        $result = DateTimeConcern::convertForLoading("picker_date", "2022-01-31");
        $this->assertEquals($result, "31/01/2022");

        $result = DateTimeConcern::convertForLoading("picker_time", "00:11:22");
        $this->assertEquals($result, "00:11");

        $result = DateTimeConcern::convertForLoading("picker_month", "2022-01-31");
        $this->assertEquals($result, "01/2022");

        $result = DateTimeConcern::convertForLoading("picker_week", "2022-01-31");
        $this->assertEquals($result, "W05/2022");

        $result = DateTimeConcern::convertForLoading("picker_quarter", "2022-01-31");
        $this->assertEquals($result, "Q1/2022");

        $result = DateTimeConcern::convertForLoading("picker_year", "2022-01-31");
        $this->assertEquals($result, "2022");
    }

    public function test_date_time_concern_convertForLoading_with_old()
    {
        $a = "31/01/2022 00:11";
        $result = DateTimeConcern::convertForLoading("picker_datetime", $a);
        $this->assertEquals($result, $a);

        $a = "31/01/2022";
        $result = DateTimeConcern::convertForLoading("picker_date", $a);
        $this->assertEquals($result, $a);

        $a = "00:11";
        $result = DateTimeConcern::convertForLoading("picker_time", $a);
        $this->assertEquals($result, $a);

        $a = "2022-01";
        $result = DateTimeConcern::convertForLoading("picker_month", $a);
        $this->assertEquals(substr($result, 0, 7), $a);

        $a = "W05/2022";
        $result = DateTimeConcern::convertForLoading("picker_week", $a);
        $this->assertEquals($result, $a);

        $a = "Q1/2022";
        $result = DateTimeConcern::convertForLoading("picker_quarter", $a);
        $this->assertEquals($result, $a);

        $a = "2022";
        $result = DateTimeConcern::convertForLoading("picker_year", $a);
        $this->assertEquals(substr($result, 0, 4), $a);
    }

    public function test_date_time_concern_convertForSaving()
    {
        $result = DateTimeConcern::convertForSaving("picker_datetime", "31/01/2022 00:11");
        $this->assertEquals($result, "2022-01-31 00:11:00");

        $result = DateTimeConcern::convertForSaving("picker_date", "31/01/2022");
        $this->assertEquals($result, "2022-01-31");

        $result = DateTimeConcern::convertForSaving("picker_time", "00:11");
        $this->assertEquals($result, "00:11:00");

        $result = DateTimeConcern::convertForSaving("picker_month", "01/2022");
        $this->assertEquals(substr($result, 0, 7), "2022-01");

        $result = DateTimeConcern::convertForSaving("picker_week", "W05/2022");
        $this->assertEquals($result, "2022-01-31");

        $result = DateTimeConcern::convertForSaving("picker_quarter", "Q1/2022");
        $this->assertEquals($result, "2022-01-01");

        $result = DateTimeConcern::convertForSaving("picker_year", "2022");
        $this->assertEquals(substr($result, 0, 4), "2022");
    }

    public function test_date_time_concern_convertForSaving_with_old()
    {
        $a = "2022-01-31 00:11:00";
        $result = DateTimeConcern::convertForSaving("picker_datetime", $a);
        $this->assertEquals($result, $a);

        $a = "2022-01-31";
        $result = DateTimeConcern::convertForSaving("picker_date", $a);
        $this->assertEquals($result, $a);

        $a = "00:11:00";
        $result = DateTimeConcern::convertForSaving("picker_time", $a);
        $this->assertEquals($result, $a);

        $a = "2022-01";
        $result = DateTimeConcern::convertForSaving("picker_month", $a);
        $this->assertEquals(substr($result, 0, 7), $a);

        $a = "2022-01-31";
        $result = DateTimeConcern::convertForSaving("picker_week", $a);
        $this->assertEquals($result, $a);

        $a = "2022-01-01";
        $result = DateTimeConcern::convertForSaving("picker_quarter", $a);
        $this->assertEquals($result, $a);

        $a = "2022";
        $result = DateTimeConcern::convertForSaving("picker_year", $a);
        $this->assertEquals(substr($result, 0, 4), $a);
    }

    public function test_date_time_concern_convertForLoading_with_timezone()
    {
        $a = "2022-01-31 00:11:00";
        $result = DateTimeConcern::convertForSaving("picker_datetime", $a);
        $this->assertEquals($result, $a);
    }
}
