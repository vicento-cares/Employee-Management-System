<!-- Data Info Modal -->
<div class="modal fade" id="attendance_summary_report_details" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-dark">
                <h4 class="modal-title">Attendance Summary Report Details</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span class="text-white" aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-8">
                        <div class="form-group mb-0">
                            <label>Attendance Date : </label>
                            <span id="day_asrd" class="ml-2"></span>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group mb-0">
                            <label>Shift Group : </label>
                            <span id="shift_group_asrd" class="ml-2"></span>
                        </div>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-sm-4">
                        <div class="form-group mb-0">
                            <label>Department : </label>
                            <span id="dept_asrd" class="ml-2"></span>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group mb-0">
                            <label>Section : </label>
                            <span id="section_asrd" class="ml-2"></span>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group mb-0">
                            <label>Line No. : </label>
                            <span id="line_no_asrd" class="ml-2"></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group mb-0">
                            <label>Total MP : </label>
                            <span id="total_mp_asrd" class="ml-2"></span>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group mb-0">
                            <label>Present : </label>
                            <span id="present_asrd" class="ml-2"></span>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group mb-0">
                            <label>Absent : </label>
                            <span id="absent_asrd" class="ml-2"></span>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row mb-2">
                    <div class="col-sm-4 offset-sm-8">
                        <div class="form-group mb-0">
                            <label>Percentage : </label>
                            <span id="attendance_percentage_asrd" class="ml-2"></span>
                        </div>
                    </div>
                </div>
                <div id="attendanceCountTableRes" class="table-responsive mb-2"
                    style="overflow: auto; display:inline-block;">
                    <table id="attendanceCountTable"
                        class="table table-sm table-head-fixed table-foot-fixed text-nowrap table-hover">
                        <thead style="text-align: center;">
                            <tr>
                                <th>#</th>
                                <th>Process</th>
                                <th>Present</th>
                                <th>Absent</th>
                                <th>Total MP</th>
                            </tr>
                        </thead>
                        <tbody id="attendanceCountData" style="text-align: center;">
                            <tr>
                                <td colspan="5" style="text-align:center;">
                                    <div class="spinner-border text-dark" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                        <tfoot style="text-align: center;">
                            <tr>
                                <th>Total MP :</th>
                                <th></th>
                                <th id="counting_view_present"></th>
                                <th id="counting_view_absent"></th>
                                <th id="attendanceCountTableInfo"></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <div id="accordion_attendance1_legend">
                    <div class="card shadow">
                        <div class="card-header">
                            <h4 class="card-title w-100">
                                <a class="d-block w-100 text-dark" data-toggle="collapse"
                                    href="#collapseOneAttendance1Legend">
                                    Attendance History Legend
                                </a>
                            </h4>
                        </div>
                        <div id="collapseOneAttendance1Legend" class="collapse"
                            data-parent="#accordion_attendance1_legend">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-6 col-lg-6 p-1 bg-success">
                                        <center>Present</center>
                                    </div>
                                    <div class="col-sm-6 col-lg-6 p-1 bg-danger">
                                        <center>Absent</center>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="attendanceTableRes" class="table-responsive"
                    style="max-height: 500px; overflow: auto; display:inline-block;">
                    <table id="attendanceTable"
                        class="table table-sm table-head-fixed table-foot-fixed text-nowrap table-hover">
                        <thead style="text-align: center;">
                            <tr>
                                <th>#</th>
                                <th>Picture</th>
                                <th>Day</th>
                                <th>Shift</th>
                                <th>Shift Group</th>
                                <th>Provider</th>
                                <th>Employee No.</th>
                                <th>Full Name</th>
                                <th>Department</th>
                                <th>Section</th>
                                <th>Line No.</th>
                                <th>Process</th>
                                <th>Skill Level</th>
                                <th>Time In</th>
                                <th>Time Out</th>
                                <th>Type of Absent</th>
                                <th>Reason</th>
                            </tr>
                        </thead>
                        <tbody id="attendanceData" style="text-align: center;">
                            <tr>
                                <td colspan="12" style="text-align:center;">
                                    <div class="spinner-border text-dark" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-sm-end">
                    <div class="dataTables_info" id="attendanceTableInfo1" role="status" aria-live="polite"></div>
                </div>
                <div class="d-flex justify-content-sm-center">
                    <button type="button" class="btn bg-gray-dark" id="btnNextPage1" style="display:none;"
                        onclick="get_next_page1()">Load more</button>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn bg-dark" data-dismiss="modal" data-toggle="modal">Close</button>
                <button type="button" class="btn bg-gray" onclick="export_attendances_counting()"><i class="fas fa-download"></i> Attendance
                    Count</button>
                <button type="button" class="btn bg-danger" onclick="export_absences()"><i class="fas fa-download"></i> Absences Report</button>
                <button type="button" class="btn bg-success" onclick="export_attendances()"><i class="fas fa-download"></i> Attendance
                    List</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->