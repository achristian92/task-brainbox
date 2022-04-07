{{--@extends('layouts.admin.app')--}}
{{--@section('content')--}}
{{--    <style>--}}
{{--        .form-group {--}}
{{--            margin-bottom: 0.7rem;--}}
{{--        }--}}
{{--    </style>--}}
{{--    @include('admin.activities.view')--}}
{{--    <br>--}}
{{--    @include('counter.plans.header', [ compact('dataHeader')])--}}
{{--    @includeWhen(request()->input('view') === "calendar",'admin.activities.calendar')--}}
{{--    @includeWhen(request()->input('view') === "list",'counter.plans.list')--}}
{{--    @include('counter.plans.create-modal', [compact('tags'), compact('priorities')])--}}
{{--    @include('admin.plans.edit-modal-pending-approval', [compact('tags'), compact('priorities')])--}}

{{--@endsection--}}
{{--@push('js')--}}
{{--    <script>--}}
{{--        $(document).ready(function() {--}}
{{--            const token = {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')};--}}
{{--            var urlActPendApproval = '{!! url('/').'/activities/pending-approval' !!}';--}}

{{--            $('.select-customers-pend-approval').select2();--}}
{{--            $('.select-tags-pend-approval').select2({--}}
{{--                maximumSelectionLength: 2--}}
{{--            });--}}

{{--            var calendar = $('#calendar-pending-approval').fullCalendar({--}}
{{--                locale: 'es',--}}
{{--                defaultView: 'month',--}}
{{--                selectable: true,--}}

{{--                viewRender: function(view) {--}}
{{--                    var title = view.title;--}}
{{--                    $("#CustomTitleCalendarPendingApproval").html(title);--}}
{{--                },--}}
{{--                // events: events,--}}
{{--                //--}}
{{--                // dayClick: function(date, jsEvent, view) {--}}
{{--                //     $('#start_date').val(date.format())--}}
{{--                //     $('#due_date').val(date.format())--}}
{{--                //     $('#modo_view').val('calendar')--}}
{{--                //     $('#modalCreateActivityPlanned').modal('show')--}}
{{--                // },--}}
{{--                eventClick: function(event) {--}}
{{--                    // $('#btn_delete_activity').attr("data-id",event.id);--}}
{{--                    loadModalActivityPendingApproval(event.id,'calendar');--}}
{{--                }--}}
{{--            });--}}

{{--            $('#selectCounter').on('change',function(e){--}}
{{--                var counter_id =  e.target.value;--}}
{{--                if ( !counter_id) { toastr.error("Selecciona un contador"); return;}--}}
{{--                calendar.fullCalendar('refetchEvents');--}}
{{--                $.get(urlActPendApproval+'?counter_id=' + counter_id, function(data){--}}
{{--                    $.each(data.actPendingApproval, function(index, value) {--}}
{{--                        calendar.fullCalendar('renderEvent',value);--}}
{{--                    });--}}
{{--                    updateHeader(data.header)--}}
{{--                });--}}

{{--            });--}}

{{--            function loadModalActivityPendingApproval($activity_id,$view = "list") {--}}
{{--                $("#customerSelect2EditPendApproval").empty();--}}
{{--                $.ajax({--}}
{{--                    url:urlActPendApproval+"/"+$activity_id+"/edit",--}}
{{--                    success: function (response) {--}}
{{--                        $("#activity_pend_approval_id").val(response.activity.id);--}}
{{--                        $.each(response.customers,function(key, customer) {--}}
{{--                            $("#customerSelect2EditPendApproval").append('<option value='+customer.id+'>'+customer.name+'</option>');--}}
{{--                        });--}}
{{--                        $("#customerSelect2EditPendApproval").val(response.activity.customer_id).change();--}}
{{--                        $("#nameActivityEditPendApproval").val(response.activity.name);--}}
{{--                        $("#estimatedHoursEditPendApproval").val(response.activity.estimated_hours);--}}
{{--                        $("#startDateEditPendApproval").val(response.activity.start_date);--}}
{{--                        $("#dueDateEditPendApproval").val(response.activity.due_date);--}}
{{--                        $("#selectPriorityEditPendApproval").val(response.activity.priority).change();--}}
{{--                        $("#select2TagsEditPendApproval").val(response.activity.tags_ids).change();--}}
{{--                        $("#descriptionEditPendApproval").val(response.activity.description);--}}
{{--                        $('#modalEditActivityPendingApproval').modal('show');--}}
{{--                    },--}}
{{--                    error:function(error){--}}
{{--                        console.log(error)--}}
{{--                    }--}}
{{--                });--}}
{{--            }--}}

{{--            $('#btnShowMoreEditPendApproval').click(function () {--}}
{{--                var btn_text = $('#btnShowMoreEditPendApproval').text();--}}
{{--                if (btn_text === "ver más") {--}}
{{--                    $('#btnShowMoreEditPendApproval').text("ver menos")--}}
{{--                } else {--}}
{{--                    $('#btnShowMoreEditPendApproval').text("ver más")--}}
{{--                }--}}
{{--                $('#collapseDetailActivityEditPendApproval').toggleClass('show');--}}
{{--            });--}}

{{--            $('#form-edit-plan-pending-approval').on('submit', function (e) {--}}
{{--                e.preventDefault();--}}
{{--                var idActivity = $('#activity_pend_approval_id').val();--}}
{{--                var datos = $('#form-edit-plan-pending-approval').serialize();--}}

{{--                $.ajax({--}}
{{--                    headers: token,--}}
{{--                    type: "PUT",--}}
{{--                    url: urlActPendApproval+'/'+idActivity,--}}
{{--                    data: datos,--}}
{{--                    success: function (response) {--}}
{{--                        calendar.fullCalendar('refetchEvents');--}}
{{--                        $.each(response.actPendingApproval, function(index, value) {--}}
{{--                            calendar.fullCalendar('renderEvent',value);--}}
{{--                        });--}}
{{--                        $('#cantHoursEst').text(response.header.estimatedHours);--}}
{{--                        $('#modalEditActivityPendingApproval').modal('hide');--}}
{{--                        toastr.success(response.message)--}}
{{--                    },--}}
{{--                    error: function (data) {--}}
{{--                        alert_errors(data);--}}
{{--                    }--}}
{{--                });--}}
{{--            })--}}

{{--            $('#btnActPendApproval').click(function (e) {--}}
{{--                e.preventDefault();--}}
{{--                var idActivity = $('#activity_pend_approval_id').val();--}}
{{--                var datos = $('#form-edit-plan-pending-approval').serialize();--}}
{{--                $.ajax({--}}
{{--                    headers: token,--}}
{{--                    type: "PUT",--}}
{{--                    url: urlActPendApproval+'/'+idActivity+'/approve',--}}
{{--                    data: datos,--}}
{{--                    success: function (data) {--}}
{{--                        calendar.fullCalendar('refetchEvents');--}}
{{--                        $.each(data.actPendingApproval, function(index, value) {--}}
{{--                            calendar.fullCalendar('renderEvent',value);--}}
{{--                        });--}}
{{--                        $('#modalEditActivityPendingApproval').modal('hide');--}}
{{--                        toastr.success(data.message)--}}
{{--                        updateHeader(data.header)--}}
{{--                    },--}}
{{--                    error: function (data) {--}}
{{--                        alert_errors(data);--}}
{{--                    }--}}
{{--                });--}}
{{--            })--}}

{{--            $('#btnDeleteActPendingApproval').on("click", function (e) {--}}
{{--                e.preventDefault();--}}
{{--                var activity_id = $('#activity_pend_approval_id').val();--}}
{{--                var counter_id = $('#selectCounter').val();--}}
{{--                var choice = confirm("Estas seguro de eliminar este registro");--}}
{{--                if (!choice) { return;}--}}
{{--                $.ajax({--}}
{{--                    headers: token,--}}
{{--                    url: urlActPendApproval+'/'+activity_id,--}}
{{--                    type: 'DELETE',--}}
{{--                    data: {'counter_id':counter_id},--}}
{{--                    success: function (data) {--}}
{{--                        calendar.fullCalendar('refetchEvents');--}}
{{--                        $.each(data.actPendingApproval, function(index, value) {--}}
{{--                            calendar.fullCalendar('renderEvent',value);--}}
{{--                        });--}}
{{--                        $('#modalEditActivityPendingApproval').modal('hide');--}}
{{--                        toastr.success(data.message)--}}
{{--                        updateHeader(data.header)--}}
{{--                    }--}}
{{--                });--}}
{{--            });--}}

{{--            function updateHeader($data) {--}}
{{--                if ($data.cantActivityPlanned > 0) {--}}
{{--                    $('#cantActPlanned').text($data.cantActivityPlanned);--}}
{{--                    $('#cantActApproved').text($data.cantActivityApproved);--}}
{{--                    $('#cantActCompleted').text($data.cantActivityCompleted);--}}
{{--                    $('#cantHoursEst').text($data.estimatedHours);--}}
{{--                    $('#totalActi').text($data.totalActivityPlanned);--}}
{{--                    $('#headerPendingApproval').show();--}}
{{--                } else {--}}
{{--                    toastr.warning("Este contador no tiene actividades pendientes de aprobar")--}}
{{--                    $('#headerPendingApproval').hide();--}}
{{--                }--}}
{{--            }--}}
{{--            function alert_errors($data) {--}}
{{--                if ($data.status === 422) {--}}
{{--                    var errors = $.parseJSON($data.responseText);--}}
{{--                    $.each(errors, function (key, value) {--}}
{{--                        if($.isPlainObject(value)) { $.each(value, function (key, value) { toastr.warning(value);});}--}}
{{--                    });--}}
{{--                }--}}
{{--            }--}}
{{--        });--}}
{{--    </script>--}}
{{--@endpush--}}
