$(document).ready(function() {
     $("#dataTable").DataTable()

    $("#categorytype").on("change", function() {
        const selected =  $(this).val();
        if(selected === "sub-category") {
            $("#parentcat").removeClass("hidden");
        }
        else {
            $("#parentcat").addClass("hidden")
        }
    })

    $("#bank_code").on("change", function() {
        const bank =  $("#bank_code option:selected").text();
        $("#bank").val(bank)
    })

    $("#edit").on("click", function() {
        $("#editForm").removeClass("hidden");
    })

    $("#interest_on_default").on("change", function() {
        const interest_on_default = $("#interest_on_default option:selected").val();

        if(interest_on_default === "fixed") {
            $("#interest_amount").removeClass("hidden");
            $("#interest_percentage").addClass("hidden");
        }
        else
        if(interest_on_default === "compound") {
            $("#interest_amount").addClass("hidden");
            $("#interest_percentage").removeClass("hidden");
        }
    })

    $("#addmore").on("click", function() {
        const elem =  $("#settings").html()
        $("#settingform").append("<div class='row'>"+elem+"</div>")
    })

    $("#serviceChargeOption").on("change", function() {
        const selected =  $(this).val();
        if(selected === "Yes") {
            $("#serviceCharge").removeClass("hidden");
        }
        else {
            $("#serviceCharge").addClass("hidden")
        }
    })

    $(".mgtbtn").on('click', function () {

        let type = $(this).data('type')
        let declineLink = $('#declineLink')
        let approveLink = $('#approvalLink')
        let target = $(this).data('url')

        if(type === 'approve') {
            $('#titleMsg').html("Do you want to approve this SAS?")
            declineLink.hide();
            approveLink.show();
            $('#approvalMessage').hide();
            approveLink.attr('href', target)
        }
        else if(type === 'decline') {
            $('#titleMsg').html("Do you want to decline this SAS?")
            declineLink.show();
            approveLink.hide();
            $('#approvalMessage').show();

            $('#approvalMessage').on('blur', function() {
                let val = $(this).val()
                declineLink.attr('href', target+'&reason='+val)
            })

        }

    })

    $(".lmgtbtn").on('click', function () {

        let type = $(this).data('type')
        let declineLink = $('#declineLink')
        let approveLink = $('#approvalLink')
        let target = $(this).data('url')

        if(type === 'approve') {
            $('#titleMsg').html("Do you want to approve this Loan?")
            declineLink.hide();
            approveLink.show();
            $('#approvalMessage').hide();
            approveLink.attr('href', target)
        }
        else if(type === 'decline') {
            $('#titleMsg').html("Do you want to decline this Loan?")
            declineLink.show();
            approveLink.hide();
            $('#approvalMessage').hide();
            declineLink.attr('href', target)
        }

    })

    $('#showDebtForm').on('click', function () {
        $('#debtForm').slideToggle()
    })
    
     $('.action_btn').on('click', function() {
    let action_id = $(this).data('action') 
    const site_URL = window.location
    $('#modalLink').attr("href", site_URL+'/'+action_id+'/delete')
})

    // $('#findDebt').on('click', function () {
    //     $('#user_id').val()
    // })
});


