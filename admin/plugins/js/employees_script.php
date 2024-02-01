<script type="text/javascript">
$( document ).ready(function() {
    fetch_dept_dropdown();
    fetch_section_dropdown();
    fetch_shuttle_route_dropdown();
    fetch_position_dropdown();
    fetch_provider_dropdown();
    load_employees(1);
    sessionStorage.setItem('notif_pending_ls', 0);
    sessionStorage.setItem('notif_accepted_ls', 0);
    sessionStorage.setItem('notif_rejected_ls', 0);
    load_notif_line_support();
    realtime_load_notif_line_support = setInterval(load_notif_line_support, 5000);
});

document.getElementById("emp_no_master_search").addEventListener("keyup", e => {
    load_employees(1);
});

document.getElementById("full_name_master_search").addEventListener("keyup", e => {
    load_employees(1);
});

// Table Responsive Scroll Event for Load More
document.getElementById("list_of_employees_res").addEventListener("scroll", function() {
    var scrollTop = document.getElementById("list_of_employees_res").scrollTop;
    var scrollHeight = document.getElementById("list_of_employees_res").scrollHeight;
    var offsetHeight = document.getElementById("list_of_employees_res").offsetHeight;

    //check if the scroll reached the bottom
    if ((offsetHeight + scrollTop + 1) >= scrollHeight) {
        get_next_page();
    }
});

const get_next_page = () => {
    var current_page = parseInt(sessionStorage.getItem('list_of_employees_table_pagination'));
    let total = sessionStorage.getItem('count_rows');
    var last_page = parseInt(sessionStorage.getItem('last_page'));
    var next_page = current_page + 1;
    if (next_page <= last_page && total > 0) {
        load_employees(next_page);
    }
}

const fetch_dept_dropdown =()=>{
    $.ajax({
        url:'../process/hr/employees/emp-masterlist_p.php',
        type:'POST',
        cache:false,
        data:{
            method:'fetch_dept_dropdown'
        },
        success:function(response){
            $('#dept_master').html(response);
            $('#dept_master_update').html(response);
        }
    });
}

const fetch_section_dropdown =()=>{
    $.ajax({
        url:'../process/hr/employees/emp-masterlist_p.php',
        type:'POST',
        cache:false,
        data:{
            method:'fetch_section_dropdown'
        },
        success:function(response){
            $('#section_master').html(response);
            $('#section_master_update').html(response);
        }
    });
}

const get_laf_approver_dropdowns = opt => {
    let dept = '';
    let section = '';
    let line_no = '';

    if (opt == 1) {
        dept = document.getElementById('dept_master').value;
        section = document.getElementById('section_master').value;
        line_no = document.getElementById('line_no_master').value;
    } else if (opt == 2) {
        dept = document.getElementById('dept_master_update').value;
        section = document.getElementById('section_master_update').value;
        line_no = document.getElementById('line_no_master_update').value;
    }

    fetch_employee_name_js_s_dropdown(dept, section, line_no, opt);
    fetch_employee_name_sv_dropdown(dept, section, line_no, opt);
    fetch_employee_name_approver_dropdown(dept, section, line_no, opt);
}

const fetch_line_dropdown = opt => {
    let section = '';

    if (opt == 1) {
        section = document.getElementById('section_master').value;
    } else if (opt == 2) {
        section = document.getElementById('section_master_update').value;
    }

    $.ajax({
        url:'../process/hr/employees/emp-masterlist_p.php',
        type:'POST',
        cache:false,
        data:{
            method:'fetch_line_dropdown',
            section:section
        },
        success:function(response){
            $('#line_no_master').html(response);
            $('#line_no_master_update').html(response);
            get_laf_approver_dropdowns(opt);
        }
    });
}

const fetch_shuttle_route_dropdown =()=>{
    $.ajax({
        url:'../process/admin/shuttle_allocation/sa_p.php',
        type:'POST',
        cache:false,
        data:{
            method:'fetch_shuttle_route_dropdown'
        },
        success:function(response){
            $('#shuttle_route_master').html(response);
            $('#shuttle_route_master_update').html(response);
        }
    });
}

const fetch_position_dropdown =()=>{
    $.ajax({
        url:'../process/hr/employees/emp-masterlist_p.php',
        type:'POST',
        cache:false,
        data:{
            method:'fetch_position_dropdown'
        },
        success:function(response){
            $('#position_master').html(response);
            $('#position_master_update').html(response);
        }
    });
}

const fetch_provider_dropdown =()=>{
    $.ajax({
        url:'../process/hr/employees/emp-masterlist_p.php',
        type:'POST',
        cache:false,
        data:{
            method:'fetch_provider_dropdown'
        },
        success:function(response){
            $('#provider_master').html(response);
            $('#provider_master_update').html(response);
            $('#provider_master_search').html(response);
        }
    });
}

const fetch_employee_name_js_s_dropdown = (dept, section, line_no, opt) =>{
    $.ajax({
        url:'../process/hr/employees/emp-masterlist_p.php',
        type:'POST',
        cache:false,
        data:{
            method:'fetch_employee_name_js_s_dropdown',
            dept:dept,
            section:section,
            line_no:line_no
        },
        success:function(response){
            if (opt == 1) {
                $('#emp_js_s_master').html(response);
            } else if (opt == 2) {
                $('#emp_js_s_master_update').html(response);
            }
        }
    });
}

const fetch_employee_name_sv_dropdown = (dept, section, line_no, opt) =>{
    $.ajax({
        url:'../process/hr/employees/emp-masterlist_p.php',
        type:'POST',
        cache:false,
        data:{
            method:'fetch_employee_name_sv_dropdown',
            dept:dept,
            section:section,
            line_no:line_no
        },
        success:function(response){
            if (opt == 1) {
                $('#emp_sv_master').html(response);
            } else if (opt == 2) {
                $('#emp_sv_master_update').html(response);
            }
        }
    });
}

const fetch_employee_name_approver_dropdown = (dept, section, line_no, opt) =>{
    $.ajax({
        url:'../process/hr/employees/emp-masterlist_p.php',
        type:'POST',
        cache:false,
        data:{
            method:'fetch_employee_name_approver_dropdown',
            dept:dept,
            section:section,
            line_no:line_no
        },
        success:function(response){
            if (opt == 1) {
                $('#emp_approver_master').html(response);
            } else if (opt == 2) {
                $('#emp_approver_master_update').html(response);
            }
        }
    });
}

const count_employee_list = () => {
    var emp_no = sessionStorage.getItem('emp_no_master_search');
    var full_name = sessionStorage.getItem('full_name_master_search');
    var provider = sessionStorage.getItem('provider_master_search');
    $.ajax({
        url:'../process/hr/employees/emp-masterlist_p.php',
        type:'POST',
        cache:false,
        data:{
            method:'count_employee_list',
            emp_no:emp_no,
            full_name:full_name,
            provider:provider
        }, 
        success:function(response){
            sessionStorage.setItem('count_rows', response);
            var count = `Total: ${response}`;
            $('#list_of_employees_info').html(count);

            if (response > 0) {
                load_employees_last_page();
            } else {
                document.getElementById("btnNextPage").style.display = "none";
                document.getElementById("btnNextPage").setAttribute('disabled', true);
            }
        }
    });
}

const load_employees_last_page = () =>{
    var emp_no = sessionStorage.getItem('emp_no_master_search');
    var full_name = sessionStorage.getItem('full_name_master_search');
    var provider = sessionStorage.getItem('provider_master_search');
    var current_page = parseInt(sessionStorage.getItem('list_of_employees_table_pagination'));
    $.ajax({
        url:'../process/hr/employees/emp-masterlist_p.php',
        type:'POST',
        cache:false,
        data:{
            method:'employee_list_last_page',
            emp_no:emp_no,
            full_name:full_name,
            provider:provider
        },
        success:function(response){
            sessionStorage.setItem('last_page', response);
            let total = sessionStorage.getItem('count_rows');
            var next_page = current_page + 1;
            if (next_page > response || total < 1) {
                document.getElementById("btnNextPage").style.display = "none";
                document.getElementById("btnNextPage").setAttribute('disabled', true);
            } else {
                document.getElementById("btnNextPage").style.display = "block";
                document.getElementById("btnNextPage").removeAttribute('disabled');
            }
        }
    });
}

const load_employees = current_page => {
    var emp_no = document.getElementById('emp_no_master_search').value;
    var full_name = document.getElementById('full_name_master_search').value;
    var provider = document.getElementById('provider_master_search').value;

    var emp_no1 = sessionStorage.getItem('emp_no_master_search');
    var full_name1 = sessionStorage.getItem('full_name_master_search');
    var provider1 = sessionStorage.getItem('provider_master_search');

    if (current_page > 1) {
        switch(true) {
            case emp_no !== emp_no1:
            case full_name !== full_name1:
            case provider !== provider1:
                emp_no = emp_no1;
                full_name = full_name1;
                provider = provider1;
                break;
            default:
        }
    } else {
        sessionStorage.setItem('emp_no_master_search', emp_no);
        sessionStorage.setItem('full_name_master_search', full_name);
        sessionStorage.setItem('provider_master_search', provider);
    }

    $.ajax({
        url:'../process/hr/employees/emp-masterlist_p.php',
        type:'POST',
        cache:false,
        data:{
            method:'employee_list',
            emp_no:emp_no,
            full_name:full_name,
            provider:provider,
            current_page:current_page
        },
        beforeSend: () => {
            var loading = `<tr id="loading"><td colspan="8" style="text-align:center;"><div class="spinner-border text-dark" role="status"><span class="sr-only">Loading...</span></div></td></tr>`;
            if (current_page == 1) {
                document.getElementById("list_of_employees").innerHTML = loading;
            } else {
                $('#list_of_employees_table tbody').append(loading);
            }
        }, 
        success:function(response){
            $('#loading').remove();
            if (current_page == 1) {
                $('#list_of_employees_table tbody').html(response);
            } else {
                $('#list_of_employees_table tbody').append(response);
            }
            sessionStorage.setItem('list_of_employees_table_pagination', current_page);
            count_employee_list();
        }
    });
}

const get_employees_details =(param)=>{
    var string = param.split('~!~');
    var id = string[0];
    var emp_no = string[1];
    var full_name = string[2];
    var dept = string[3];
    var section = string[4];
    var line_no = string[5];
    var position = string[6];
    var provider = string[7];
    var date_hired = string[8];
    var address = string[9];
    var contact_no = string[10];
    var emp_status = string[11];
    var shuttle_route = string[12];
    var emp_js_s_no = string[13];
    var emp_sv_no = string[14];
    var emp_approver_no = string[15];
    var resigned = string[16];
    var resigned_date = string[17];
    var gender = string[18];

    document.getElementById('id_employee_master_update').value = id;
    document.getElementById('emp_no_master_update').value = emp_no;
    document.getElementById('full_name_master_update').value = full_name;
    document.getElementById('dept_master_update').value = dept;

    document.getElementById('section_master_update').value = section;
    document.getElementById('position_master_update').value = position;
    document.getElementById('provider_master_update').value = provider;
    document.getElementById('date_hired_master_update').value = date_hired;
    document.getElementById('address_master_update').value = address;
    document.getElementById('contact_no_master_update').value = contact_no;
    document.getElementById('emp_status_master_update').value = emp_status;
    document.getElementById('shuttle_route_master_update').value = shuttle_route;
    
    if (resigned == 0) {
        document.getElementById("resigned_master_update").checked = false;
    } else if (resigned == 1) {
        document.getElementById("resigned_master_update").checked = true;
    }

    document.getElementById('resigned_date_master_update').value = resigned_date;
    document.getElementById('gender_master_update').value = gender;

    fetch_line_dropdown(2);

    setTimeout(() => {
        document.getElementById('line_no_master_update').value = line_no;
        document.getElementById('emp_js_s_master_update').value = emp_js_s_no;
        document.getElementById('emp_sv_master_update').value = emp_sv_no;
        document.getElementById('emp_approver_master_update').value = emp_approver_no;
    }, 500);
}

const update_employee =()=>{
    var id = document.getElementById('id_employee_master_update').value;
    var emp_no = document.getElementById('emp_no_master_update').value;
    var full_name = document.getElementById('full_name_master_update').value;
    var dept = document.getElementById('dept_master_update').value;
    var section = document.getElementById('section_master_update').value;
    var line_no = document.getElementById('line_no_master_update').value;
    var position = document.getElementById('position_master_update').value;
    var date_hired = document.getElementById('date_hired_master_update').value;
    var provider = document.getElementById('provider_master_update').value;
    var address = document.getElementById('address_master_update').value;
    var contact_no = document.getElementById('contact_no_master_update').value;
    var emp_status = document.getElementById('emp_status_master_update').value;
    var shuttle_route = document.getElementById('shuttle_route_master_update').value;
    var gender = document.getElementById('gender_master_update').value;

    var emp_js_s_master_update = document.getElementById("emp_js_s_master_update");
    var emp_js_s_no = emp_js_s_master_update.value;
    if (emp_js_s_no != '') {
        var emp_js_s = emp_js_s_master_update.options[emp_js_s_master_update.selectedIndex].text;
    } else {
        var emp_js_s = '';
    }

    var emp_sv_master_update = document.getElementById("emp_sv_master_update");
    var emp_sv_no = emp_sv_master_update.value;
    if (emp_sv_no != '') {
        var emp_sv = emp_sv_master_update.options[emp_sv_master_update.selectedIndex].text;
    } else {
        var emp_sv = '';
    }

    var emp_approver_master_update = document.getElementById("emp_approver_master_update");
    var emp_approver_no = emp_approver_master_update.value;
    if (emp_approver_no != '') {
        var emp_approver = emp_approver_master_update.options[emp_approver_master_update.selectedIndex].text;
    } else {
        var emp_approver = '';
    }

    if (emp_js_s_no == '') {
        emp_js_s = '';
    }
    if (emp_sv_no == '') {
        emp_sv = '';
    }
    if (emp_approver_no == '') {
        emp_approver = '';
    }

    var resigned = 0;
    if (document.getElementById('resigned_master_update').checked == true) {
        resigned = 1;
    }

    var resigned_date = document.getElementById('resigned_date_master_update').value;

    if ((resigned == 1 && resigned_date == '') || (resigned == 0 && resigned_date != '')) {
        Swal.fire({
            icon: 'info',
            title: 'Please Complete Fill out of Resign Information !!!',
            text: 'Information',
            showConfirmButton: false,
            timer : 1000
        });
    } else {
        $.ajax({
            url:'../process/hr/employees/emp-masterlist_p.php',
            type:'POST',
            cache:false,
            data:{
                method:'update_employee',
                id:id,
                emp_no:emp_no,
                full_name:full_name,
                dept:dept,
                section:section,
                line_no:line_no,
                position:position,
                date_hired:date_hired,
                provider:provider,
                address:address,
                contact_no:contact_no,
                emp_status:emp_status,
                shuttle_route:shuttle_route,
                gender:gender,
                emp_js_s_no:emp_js_s_no,
                emp_sv_no:emp_sv_no,
                emp_approver_no:emp_approver_no,
                emp_js_s:emp_js_s,
                emp_sv:emp_sv,
                emp_approver:emp_approver,
                resigned:resigned,
                resigned_date:resigned_date
            },success:function(response){
                if (response == 'success') {
                    Swal.fire({
                      icon: 'success',
                      title: 'Succesfully Recorded!!!',
                      text: 'Success',
                      showConfirmButton: false,
                      timer : 1000
                    });
                    $('#id_employee_master_update').val('');
                    $('#emp_no_master_update').val('');
                    $('#full_name_master_update').val('');
                    $('#dept_master_update').val('');
                    $('#section_master_update').val('');
                    $('#line_no_master_update').val('');
                    $('#position_master_update').val('');
                    $('#date_hired_master_update').val('');
                    $('#provider_master_update').val('');
                    $('#address_master_update').val('');
                    $('#contact_no_master_update').val('');
                    $('#emp_status_master_update').val('');
                    $('#shuttle_route_master_update').val('');
                    $('#gender_master_update').val('').trigger('change');
                    $('#emp_js_s_master_update').val('').trigger('change');
                    $('#emp_sv_master_update').val('').trigger('change');
                    $('#emp_approver_master_update').val('').trigger('change');
                    load_employees(1);
                    $('#update_employee').modal('hide');
                }else if(response == 'duplicate'){
                     Swal.fire({
                      icon: 'info',
                      title: 'Duplicate Data !!!',
                      text: 'Information',
                      showConfirmButton: false,
                      timer : 1000
                    });
                }else{
                    Swal.fire({
                      icon: 'error',
                      title: 'Error !!!',
                      text: 'Error',
                      showConfirmButton: false,
                      timer : 1000
                    });
                }
            }
        });
    }
}

const print_employees = () => {
    var emp_no = sessionStorage.getItem('emp_no_master_search');
    var full_name = sessionStorage.getItem('full_name_master_search');
    var provider = sessionStorage.getItem('provider_master_search');
    window.open('../process/print/print_employees.php?emp_no='+emp_no+"&full_name="+full_name+'&provider='+provider,'_blank');
}
</script>