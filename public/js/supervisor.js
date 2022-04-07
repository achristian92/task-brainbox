$(document).ready(function() {
    var monitored_all = $('#inputCanCheckByAll').val();

    if (monitored_all === "0") {
        $('#selectMultipleAdminORSuper').hide();
        $("#checkByAll").prop( "checked", true );

    } else {
        $("#checkByAll").prop('checked', false);
        $('#selectMultipleAdminORSuper').show();
        $('#checkByAll').val(0)
    }

    $('.can_check_all').change(function(){
        if (!$(this).prop('checked')){
            console.log("entro1")
            $('#selectMultipleAdminORSuper').show();
            $('#checkByAll').val(0)
        } else {
            $('#selectMultipleAdminORSuper').hide();
            $('#checkByAll').val(1)
        }
    });

    $('.js-example-basic-multiple').select2();
});
