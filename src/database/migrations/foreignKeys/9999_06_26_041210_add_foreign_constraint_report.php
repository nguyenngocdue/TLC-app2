<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::table('rp_pages', function (Blueprint $table) {
        //     $table->foreign('report_id')->references('id')->on('rp_reports');
        //     $table->foreign('letter_head_id')->references('id')->on('rp_letter_heads');
        //     $table->foreign('letter_footer_id')->references('id')->on('rp_letter_footers');
        // });
        // Schema::table('ym2m_rp_column_rp_report_filter', function (Blueprint $table) {
        //     // $table->foreign('report_id')->references('id')->on('rp_reports');
        //     $table->foreign('column_id')->references('id')->on('rp_columns');
        //     $table->foreign('black_or_white')->references('id')->on('terms');
        //     $table->foreign('control_type')->references('id')->on('terms');
        // });
        // Schema::table('ym2m_rp_block_rp_page', function (Blueprint $table) {
        //     $table->foreign('page_id')->references('id')->on('rp_pages');
        //     $table->foreign('block_id')->references('id')->on('rp_blocks');
        // });
        // Schema::table('rp_filter_mode', function (Blueprint $table) {
        //     $table->foreign('report_id')->references('id')->on('rp_reports');
        // });
        // Schema::table('rp_types', function (Blueprint $table) {
        //     $table->foreign('block_id')->references('id')->on('rp_blocks');
        //     $table->foreign('renderer_type')->references('id')->on('rp_blocks');
        //     $table->foreign('top_left_control')->references('id')->on('terms');
        //     $table->foreign('top_center_control')->references('id')->on('terms');
        //     $table->foreign('top_right_control')->references('id')->on('terms');
        //     $table->foreign('bottom_left_control')->references('id')->on('terms');
        //     $table->foreign('bottom_center_control')->references('id')->on('terms');
        //     $table->foreign('bottom_right_control')->references('id')->on('terms');
        //     $table->foreign('chart_type')->references('id')->on('terms');
        // });
        // Schema::table('rp_columns', function (Blueprint $table) {
        //     $table->foreign('type_id')->references('id')->on('rp_types');
        //     $table->foreign('parent_id')->references('id')->on('rp_columns');
        //     $table->foreign('icon_position')->references('id')->on('terms');
        //     $table->foreign('row_icon_position')->references('id')->on('terms');
        //     $table->foreign('row_renderer')->references('id')->on('terms');
        //     $table->foreign('agg_footer')->references('id')->on('terms');
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
};
