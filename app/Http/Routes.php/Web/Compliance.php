<?php

Route::group(['middleware' => ['auth', 'csrf']], function () {

    Route::group(['namespace' => 'Web'], function () {

        Route::group(['namespace' => 'Compliance', 'prefix' => 'compliance/reports'], function () {

            Route::get('', [
                'as'   => 'compliance.reports',
                'uses' => 'ReportsController@index',
            ]);

            Route::group(['namespace' => 'Reports'], function () {

                /**
                 * Breaks
                 */
                Route::get('breaks', [
                    'as'   => 'compliance.reports.breaks',
                    'uses' => 'BreaksController@index',
                ]);

                /**
                 * Sensor Failures
                 */
                Route::get('sensor_failures', [
                    'as'   => 'compliance.reports.sensorfailures',
                    'uses' => 'SensorFailuresController@index',
                ]);

                Route::get('sensor_failures/get_datatables_page', [
                    'as'   => 'compliance.reports.sensorfailures.getdatatablepage',
                    'uses' => 'SensorFailuresController@getDataTablePage',
                ]);

                /**
                 * Odometer Jumps
                 */
                Route::get('jumps', [
                    'as'   => 'compliance.reports.jumps',
                    'uses' => 'OdometerJumpsController@index',
                ]);

                /**
                 * Attestations
                 */
                Route::get('attestations', [
                    'as'   => 'compliance.reports.attestations',
                    'uses' => 'AttestationController@index',
                ]);

                /**
                 * Printed Log Transfers
                 */
                Route::get('printed_log_transfers', [
                    'as'   => 'compliance.reports.printedLogTransfers',
                    'uses' => 'PrintedLogTransfersController@index',
                ]);
                Route::get('printed_log_transfers/{id}', [
                    'as'   => 'compliance.reports.printedLogTransfers.viewPDF',
                    'uses' => 'PrintedLogTransfersController@viewPDF',
                ]);
                Route::post('printed_log_transfers/store', [
                    'as'   => 'compliance.reports.printedLogTransfers.store',
                    'uses' => 'PrintedLogTransfersController@store',
                ]);

                /*
                 * Driver Logs
                 */
                Route::get('driver_logs', [
                    'as'   => 'compliance.reports.driverlogs',
                    'uses' => 'DriverLogsController@index',
                ]);

                /**
                 * Edited logs
                 */
                Route::get('edited_logs', [
                    'as'   => 'compliance.reports.editedlogs',
                    'uses' => 'EditedLogsController@index',
                ]);

                /**
                 * Unassigned Driving History
                 */
                Route::get('unassigned_driving_history', [
                    'as'   => 'compliance.reports.unassigned_driving_history',
                    'uses' => 'UnassignedDrivingHistoryController@index',
                ]);

                /**
                 * Violations
                 */
                Route::get('violations', [
                    'as'   => 'compliance.reports.violations',
                    'uses' => 'ViolationsController@index',
                ]);

                Route::post('violations-search', [
                    'as'   => 'compliance.reports.violations-search',
                    'uses' => 'ViolationsController@show',
                ]);

                /**
                 * Non-Certified Events
                 */
                Route::get('uncertified_log', [
                    'as'   => 'compliance.reports.uncertified_log',
                    'uses' => 'UncertifiedLogController@index',
                ]);

                /**
                 * Missing Tablets
                 */
                Route::group(['prefix' => 'missing_tablets'], function () {
                    Route::get('', [
                        'as'   => 'compliance.reports.missing_tablets',
                        'uses' => 'MissingTabletsController@index',
                    ]);
                    Route::get('related-events', [
                        'as'   => 'compliance.reports.missing_tablets.related_events',
                        'uses' => 'MissingTabletsController@relatedEvents',
                    ]);
                });

                /**
                 * Stuck Report
                 */
                Route::get('stuck_report', [
                    'as'   => 'compliance.reports.stuck_report',
                    'uses' => 'StuckReportsController@index',
                ]);

                /**
                 * Printed Log Transfer
                 */
                Route::get('printed_log_transfer/{id}/retry', [
                    'as'   => 'compliance.reports.printedLogTransfers.retry',
                    'uses' => 'PrintedLogTransfersController@retry',
                ]);

                /**
                 * Search DVIR index page
                 */
                Route::get('dvir_reports', [
                    'as'   => 'compliance.reports.dvirreports',
                    'uses' => 'VIRController@index',
                ]);

                /**
                 * Search DVIR Results
                 */
                Route::get('get_dvir', [
                    'as'   => 'compliance.reports.getdvir',
                    'uses' => 'VIRController@getDVIRResults',
                ]);

                /**
                 * Email/Fax DVIR Results
                 */
                Route::post('send_dvir', [
                    'as'   => 'compliance.reports.sendDvir',
                    'uses' => 'VIRController@sendDVIRResults',
                ]);

            });
        });

    });

});
