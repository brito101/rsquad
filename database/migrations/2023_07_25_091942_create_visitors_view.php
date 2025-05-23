<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateVisitorsView extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("
        CREATE OR REPLACE VIEW `visitors_view` AS
        SELECT v.id, v.url, v.ip, v.method, v.languages, v.useragent, v.platform, v.browser, v.visitor_type, v.visitor_id, v.created_at, u.name, v.request
        FROM shetabit_visits  as v
        LEFT JOIN users as u ON u.id = v.visitor_id
        WHERE DATE_FORMAT(v.created_at, '%Y-%m-%d') = CURDATE()
        ");

        DB::statement("
        CREATE OR REPLACE VIEW `visitors_yesterday_view` AS
        SELECT v.id, v.url, v.ip, v.method, v.languages, v.useragent, v.platform, v.browser, v.visitor_type, v.visitor_id, v.created_at, u.name
        FROM shetabit_visits  as v
        LEFT JOIN users as u ON u.id = v.visitor_id
        WHERE DATE_FORMAT(v.created_at, '%Y-%m-%d') = DATE_SUB(CURDATE(), INTERVAL 1 DAY)
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('DROP VIEW visitors_yesterday_view');
        DB::statement('DROP VIEW visitors_view');
    }
}
