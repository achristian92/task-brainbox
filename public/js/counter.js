$(document).ready(function() {
    var can_check_all_customers = $('#inputCanCheckAllCustomers').val();

    if (can_check_all_customers === "1") {
        $('#selectMultipleCustomers').hide();
        $("#checkAllCustomers").prop( "checked", true );

    } else {
        $("#checkAllCustomers").prop('checked', false);
        $('#selectMultipleCustomers').show();
        $('#checkAllCustomers').val(0)
    }

    $('#checkAllCustomers').change(function(){
        if(!$(this).prop('checked')){
            $('#selectMultipleCustomers').show();
            $('#checkAllCustomers').val(0)
        }else{
            $('#selectMultipleCustomers').hide();
            $('#checkAllCustomers').val(1)
        }
    });

    $('.js-customer-select-multiple').select2();
});
