<script type="text/javascript">
// AJAX IN PROGRESS GLOBAL VARS
var load_osh_candidates_ajax_in_process = false;

var delay = (function(){
	var timer = 0;
	return function(callback, ms){
		clearTimeout (timer);
		timer = setTimeout(callback, ms);
	};
})();

$("#emp_no_osh").on("input", function() {
	delay(function(){
		if ($("#emp_no_osh").val().length < 21) {
			$("#emp_no_osh").val("");
		}
	}, 100);
});

document.getElementById("emp_no_osh").addEventListener("keyup", e => {
	if (e.which === 13) {
		e.preventDefault();
		check_emp_no_osh();
	}
});

const check_emp_no_osh = () => {
	let emp_no = document.getElementById("emp_no_osh").value;
	if (emp_no == '') {
		Swal.fire({
			icon: 'info',
			title: 'Please Scan ID Number !!!',
			text: 'Information',
			showConfirmButton: false,
			timer: 1000
		});
	} else {
		$.ajax({
			url: '../process/admin/osh/osh_p.php',
			type: 'POST',
			cache: false,
			data: {
				method: 'check_emp_no_osh',
				emp_no: emp_no
			},
			success: function (response) {
				if (response == 'No Time In') {
					document.getElementById("emp_no_osh").value = '';
					document.getElementById("full_name_osh").value = '';
					Swal.fire({
						icon: 'info',
						title: 'No Time In',
						text: 'Information',
						showConfirmButton: false,
						timer: 1000
					});
				} else if (response == 'Already Time Out') {
					document.getElementById("emp_no_osh").value = '';
					document.getElementById("full_name_osh").value = '';
					Swal.fire({
						icon: 'error',
						title: 'Already Time Out',
						text: 'Error !!!',
						showConfirmButton: false,
						timer: 2000
					});
				} else if (response == 'Already Voted') {
					document.getElementById("emp_no_osh").value = '';
					document.getElementById("full_name_osh").value = '';
					Swal.fire({
						icon: 'info',
						title: 'Already Voted',
						text: 'Information',
						showConfirmButton: false,
						timer: 2000
					});
				} else {
					try {
						let response_array = JSON.parse(response);
						if (response_array.message == 'success') {
							document.getElementById("full_name_osh").value = response_array.full_name;
							sessionStorage.setItem('emp_no_osh', emp_no);
							load_osh_candidates();
						} else {
							Swal.fire({
								icon: 'error',
								title: 'Error !!!',
								text: `${response_array.message}`,
								showConfirmButton: false,
								timer: 2000
							});
						}
					} catch (e) {
						console.log(response);
						Swal.fire({
							icon: 'error',
							title: 'Error !!!',
							text: `${response}`,
							showConfirmButton: false,
							timer: 2000
						});
					}
				}
			}
		});
	}
}

const load_osh_candidates = () => {
	// If an AJAX call is already in progress, return immediately
	if (load_osh_candidates_ajax_in_process) {
		return;
	}

	// Set the flag to true as we're starting an AJAX call
	load_osh_candidates_ajax_in_process = true;

	$.ajax({
		url: '../process/admin/osh/osh_p.php',
		type: 'POST',
		cache: false,
		dataType: 'json',
		data: {
			method: 'load_osh_candidates',
			emp_no: sessionStorage.getItem('emp_no_osh')
		},
		success: function (response) {
			let url = 'http://172.25.116.188:3000/';
			let url_blank = 'http://172.25.116.188:3000/emp_mgt/dist/img/user.png';

			// Set the flag back to false as the AJAX call has completed
            load_osh_candidates_ajax_in_process = false;

            // Clear the existing content
            $('#osh_candidates_list').empty();

            // Check if the response is not empty
            if (response && response.length > 0) {
				let title = `<div class="col-12"><h4>Vote for <b>(1) OSH Representative</b> Click the profile box you want to vote.</h4></div>`;
				$('#osh_candidates_list').append(title);
                // Iterate over the response and create cards
                response.forEach(candidate => {
					let img_url = url_blank;
					if (candidate.file_url !== null) {
						img_url = url + candidate.file_url;
					}
                    const card = `
                        <div class="col-md-4 mb-4" style="height:400px;">
                            <div class="card shadow-lg mb-4" onclick="confirm_osh_vote(event)" data-id="${candidate.cand_emp_no}" style="height:100%;">
                                <div class="card-body">
									<div class="row mb-2">
										<div class="col-5">
											<img src="${img_url}" class="card-img-top osh_employee_picture_img_tag" alt="${candidate.cand_name}">
										</div>
										<div class="col-7 d-flex flex-column">
											<p class="card-title"><b>${candidate.cand_name}</b></p>
											<p class="card-text">${candidate.cand_emp_no}</p>
										</div>
									</div>
									<div class="row mb-2">
										<p class="card-text">Platform: <b>${candidate.platform !== null ? candidate.platform : ""}</b></p>
									</div>
									<div class="row">
										<p class="card-text">Goal: <b>${candidate.goal !== null ? candidate.goal : ""}</b></p>
									</div>
                                </div>
                            </div>
                        </div>
                    `;
                    // Append the card to the list
                    $('#osh_candidates_list').append(card);
                });
                // Show the list after rendering
                $('#osh_candidates_list').removeClass('d-none');

				document.getElementById("emp_no_osh").disabled = true;
            } else {
                $('#osh_candidates_list').append('<p>No candidates found.</p>');
                $('#osh_candidates_list').removeClass('d-none');
            }
		}
	}).fail((jqXHR, textStatus, errorThrown) => {
		console.log(jqXHR);
		console.log(`System Error : Call IT Personnel Immediately!!! They will fix it right away. Error: url: ${jqXHR.url}, method: ${jqXHR.type} ( HTTP ${jqXHR.status} - ${jqXHR.statusText} ) Press F12 to see Console Log for more info.`);
		// Set the flag back to false as the AJAX call has completed
		load_osh_candidates_ajax_in_process = false;
	});
}

const confirm_osh_vote = event => {
	event.preventDefault();
	const candidateId = event.currentTarget.dataset.id;

	let text = `
		<img src="${event.currentTarget.querySelector('.card-img-top').src}" alt="Logo" width="150" height="150"><br>
		Cast your 1 vote for this candidate?<br>
		${candidateId}<br>
		${event.currentTarget.querySelector('.card-title').innerText}
	`;

	Swal.fire({
  		title: "Confirm Vote",
  		html: text,
  		showCancelButton: true,
  		confirmButtonColor: "#3085d6",
  		cancelButtonColor: "#d33",
  		confirmButtonText: "Vote"
	}).then((result) => {
  	if (result.isConfirmed) {
		//ajax here

		$.ajax({
            type: 'POST',
            url: '../process/admin/osh/osh_p.php',
            data: {
				method: 'set_vote',
				vote: candidateId,
				voter: sessionStorage.getItem('emp_no_osh')
            },
            dataType: 'json',
            success: function(response) {

            },
            error: function(xhr, status, error) {
                // Handle error response
                console.error('Form submission failed:', error);
            }
        });

    	Swal.fire({
      	title: "Vote Process Complete!",
      	text: "Your vote has been successfully registered.",
      	icon: "success"
		}).then((result) => {
			// do a hard reset here, might as well just say fuck it and reload
			window.location.reload();
		});
  	}
	});
}
</script>