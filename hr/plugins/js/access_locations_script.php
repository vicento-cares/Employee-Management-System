<script type="text/javascript">
    // AJAX IN PROGRESS GLOBAL VARS
    var load_access_locations_ajax_in_process = false;

    // DOMContentLoaded function
    document.addEventListener("DOMContentLoaded", () => {
        load_access_locations(1);
    });

    var typingTimerLineNoSearch; // Timer identifier LineNo Search
    var typingTimerSectionSearch; // Timer identifier Section Search
    var typingTimerIPSearch; // Timer identifier IP Search
    var doneTypingInterval = 250; // Time in ms

    // On keyup, start the countdown
    document.getElementById("line_no_search").addEventListener('keyup', e => {
        clearTimeout(typingTimerLineNoSearch);
        typingTimerLineNoSearch = setTimeout(doneTypingLoadAccessLocations, doneTypingInterval);
    });

    // On keydown, clear the countdown
    document.getElementById("line_no_search").addEventListener('keydown', e => {
        clearTimeout(typingTimerLineNoSearch);
    });

    // On keyup, start the countdown
    document.getElementById("section_search").addEventListener('keyup', e => {
        clearTimeout(typingTimerSectionSearch);
        typingTimerSectionSearch = setTimeout(doneTypingLoadAccessLocations, doneTypingInterval);
    });

    // On keydown, clear the countdown
    document.getElementById("section_search").addEventListener('keydown', e => {
        clearTimeout(typingTimerSectionSearch);
    });

    // On keyup, start the countdown
    document.getElementById("ip_search").addEventListener('keyup', e => {
        clearTimeout(typingTimerIPSearch);
        typingTimerIPSearch = setTimeout(doneTypingLoadAccessLocations, doneTypingInterval);
    });

    // On keydown, clear the countdown
    document.getElementById("ip_search").addEventListener('keydown', e => {
        clearTimeout(typingTimerIPSearch);
    });

    // User is "finished typing," do something
    const doneTypingLoadAccessLocations = () => {
        load_access_locations(1);
    }

    // Table Responsive Scroll Event for Load More
    document.getElementById("list_of_access_locations_res").addEventListener("scroll", () => {
        var scrollTop = document.getElementById("list_of_access_locations_res").scrollTop;
        var scrollHeight = document.getElementById("list_of_access_locations_res").scrollHeight;
        var offsetHeight = document.getElementById("list_of_access_locations_res").offsetHeight;

        if (load_access_locations_ajax_in_process == false) {
            //check if the scroll reached the bottom
            if ((offsetHeight + scrollTop + 1) >= scrollHeight) {
                get_next_page();
            }
        }
    });

    const get_next_page = () => {
        var current_page = parseInt(sessionStorage.getItem('list_of_access_locations_table_pagination'));
        let total = sessionStorage.getItem('count_rows');
        var last_page = parseInt(sessionStorage.getItem('last_page'));
        var next_page = current_page + 1;
        if (next_page <= last_page && total > 0) {
            load_access_locations(next_page);
        }
    }

    const count_access_location_list = () => {
        var line_no = sessionStorage.getItem('line_no_search');
        var section = sessionStorage.getItem('section_search');
        var ip = sessionStorage.getItem('ip_search');
        $.ajax({
            url: '../process/hr/access_locations/al_p.php',
            type: 'POST',
            cache: false,
            data: {
                method: 'count_access_location_list',
                line_no: line_no,
                section: section,
                ip: ip
            },
            success: function (response) {
                sessionStorage.setItem('count_rows', response);
                var count = `Total: ${response}`;
                document.getElementById("list_of_access_locations_info").innerHTML = count;

                if (response > 0) {
                    load_access_locations_last_page();
                } else {
                    document.getElementById("btnNextPage").style.display = "none";
                    document.getElementById("btnNextPage").setAttribute('disabled', true);
                }
            }
        });
    }

    const load_access_locations_last_page = () => {
        var line_no = sessionStorage.getItem('line_no_search');
        var section = sessionStorage.getItem('section_search');
        var ip = sessionStorage.getItem('ip_search');
        var current_page = parseInt(sessionStorage.getItem('list_of_access_locations_table_pagination'));
        $.ajax({
            url: '../process/hr/access_locations/al_p.php',
            type: 'POST',
            cache: false,
            data: {
                method: 'access_location_list_last_page',
                line_no: line_no,
                section: section,
                ip: ip
            },
            success: function (response) {
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

    const load_access_locations = current_page => {
        // If an AJAX call is already in progress, return immediately
        if (load_access_locations_ajax_in_process) {
            return;
        }

        var line_no = document.getElementById('line_no_search').value;
        var section = document.getElementById('section_search').value;
        var ip = document.getElementById('ip_search').value;

        var line_no1 = sessionStorage.getItem('line_no_search');
        var section1 = sessionStorage.getItem('section_search');
        var ip1 = sessionStorage.getItem('ip_search');

        if (current_page > 1) {
            switch (true) {
                case line_no !== line_no1:
                case section !== section1:
                case ip !== ip1:
                    line_no = line_no1;
                    section = section1;
                    ip = ip1;
                    break;
                default:
            }
        } else {
            sessionStorage.setItem('line_no_search', line_no);
            sessionStorage.setItem('section_search', section);
            sessionStorage.setItem('ip_search', ip);
        }

        // Set the flag to true as we're starting an AJAX call
        load_access_locations_ajax_in_process = true;

        $.ajax({
            url: '../process/hr/access_locations/al_p.php',
            type: 'POST',
            cache: false,
            data: {
                method: 'access_location_list',
                line_no: line_no,
                section: section,
                ip: ip,
                current_page: current_page
            },
            beforeSend: (jqXHR, settings) => {
                document.getElementById("btnNextPage").setAttribute('disabled', true);
                var loading = `<tr id="loading"><td colspan="6" style="text-align:center;"><div class="spinner-border text-dark" role="status"><span class="sr-only">Loading...</span></div></td></tr>`;
                if (current_page == 1) {
                    document.getElementById("list_of_access_locations").innerHTML = loading;
                } else {
                    $('#list_of_access_locations_table tbody').append(loading);
                }
                jqXHR.url = settings.url;
                jqXHR.type = settings.type;
            },
            success: function (response) {
                $('#loading').remove();
                document.getElementById("btnNextPage").removeAttribute('disabled');
                if (current_page == 1) {
                    $('#list_of_access_locations_table tbody').html(response);
                } else {
                    $('#list_of_access_locations_table tbody').append(response);
                }
                sessionStorage.setItem('list_of_access_locations_table_pagination', current_page);
                count_access_location_list();
                // Set the flag back to false as the AJAX call has completed
                load_access_locations_ajax_in_process = false;
            }
        }).fail((jqXHR, textStatus, errorThrown) => {
            console.log(jqXHR);
            console.log(`System Error : Call IT Personnel Immediately!!! They will fix it right away. Error: url: ${jqXHR.url}, method: ${jqXHR.type} ( HTTP ${jqXHR.status} - ${jqXHR.statusText} ) Press F12 to see Console Log for more info.`);
            $('#loading').remove();
            document.getElementById("btnNextPage").removeAttribute('disabled');
            // Set the flag back to false as the AJAX call has completed
            load_access_locations_ajax_in_process = false;
        });
    }

    document.getElementById('new_access_location_form').addEventListener('submit', e => {
        e.preventDefault();
        add_access_locations();
    });

    const add_access_locations = () => {
        var dept = document.getElementById('dept_al').value;
        var section = document.getElementById('section_al').value;
        var line_no = document.getElementById('line_no_al').value;
        var ip = document.getElementById('ip_al').value;

        $.ajax({
            url: '../process/hr/access_locations/al_p.php',
            type: 'POST',
            cache: false,
            data: {
                method: 'add_access_location',
                dept: dept,
                section: section,
                line_no: line_no,
                ip: ip
            }, success: function (response) {
                if (response == 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Succesfully Recorded!!!',
                        text: 'Success',
                        showConfirmButton: false,
                        timer: 1000
                    });
                    clear_access_location_details();
                    load_access_locations(1);
                    $('#new_access_location').modal('hide');
                } else if (response == 'Already Exist') {
                    Swal.fire({
                        icon: 'info',
                        title: 'Duplicate Data !!!',
                        text: 'Information',
                        showConfirmButton: false,
                        timer: 2000
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error !!!',
                        text: 'Error',
                        showConfirmButton: false,
                        timer: 2000
                    });
                }
            }
        });
    }

    const get_access_location_details = (param) => {
        var string = param.split('~!~');
        var id = string[0];
        var dept = string[1];
        var section = string[2];
        var line_no = string[3];
        var ip = string[4];

        document.getElementById('id_access_location_update').value = id;
        document.getElementById('dept_al_update').value = dept;
        document.getElementById('section_al_update').value = section;
        document.getElementById('line_no_al_update').value = line_no;
        document.getElementById('ip_al_update').value = ip;
    }

    const clear_access_location_details = () => {
        document.getElementById("dept_al").value = '';
        document.getElementById("section_al").value = '';
        document.getElementById("line_no_al").value = '';
        document.getElementById("ip_al").value = '';

        document.getElementById('id_access_location_update').value = '';
        document.getElementById('dept_al_update').value = '';
        document.getElementById('section_al_update').value = '';
        document.getElementById('line_no_al_update').value = '';
        document.getElementById('ip_al_update').value = '';
    }

    // Get the form element
    var update_access_location_form = document.getElementById('update_access_location_form');

    // Add a submit event listener to the form
    update_access_location_form.addEventListener('submit', e => {
        e.preventDefault();

        // Get the button that triggered the submit event
        var button = document.activeElement;

        // Check the id or name of the button
        if (button.id === 'btnUpdateAccessLocation') {
            // Call the function for the first submit button
            update_access_location();
        } else if (button.id === 'btnDeleteAccessLocation') {
            // Call the function for the first submit button
            delete_access_location();
        }
    });

    const update_access_location = () => {
        var id = document.getElementById('id_access_location_update').value;
        var dept = document.getElementById('dept_al_update').value;
        var section = document.getElementById('section_al_update').value;
        var line_no = document.getElementById('line_no_al_update').value;
        var ip = document.getElementById('ip_al_update').value;

        $.ajax({
            url: '../process/hr/access_locations/al_p.php',
            type: 'POST',
            cache: false,
            data: {
                method: 'update_access_location',
                id: id,
                dept: dept,
                section: section,
                line_no: line_no,
                ip: ip
            }, success: function (response) {
                if (response == 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Succesfully Recorded!!!',
                        text: 'Success',
                        showConfirmButton: false,
                        timer: 1000
                    });
                    clear_access_location_details();
                    load_access_locations(1);
                    $('#update_access_location').modal('hide');
                } else if (response == 'duplicate') {
                    Swal.fire({
                        icon: 'info',
                        title: 'Duplicate Data !!!',
                        text: 'Information',
                        showConfirmButton: false,
                        timer: 2000
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error !!!',
                        text: 'Error',
                        showConfirmButton: false,
                        timer: 2000
                    });
                }
            }
        });
    }

    const delete_access_location = () => {
        var id = document.getElementById('id_access_location_update').value;

        $.ajax({
            url: '../process/hr/access_locations/al_p.php',
            type: 'POST',
            cache: false,
            data: {
                method: 'delete_access_location',
                id: id
            }, success: function (response) {
                if (response == 'success') {
                    Swal.fire({
                        icon: 'info',
                        title: 'Succesfully Deleted !!!',
                        text: 'Information',
                        showConfirmButton: false,
                        timer: 1000
                    });
                    clear_access_location_details();
                    load_access_locations(1);
                    $('#update_access_location').modal('hide');
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error !!!',
                        text: 'Error',
                        showConfirmButton: false,
                        timer: 2000
                    });
                }
            }
        });
    }
</script>