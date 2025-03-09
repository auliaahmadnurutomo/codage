let asc = 'desc';
let desc = 'asc';
let border_order_table = 'bg-light';

function interval_reload(){
    // var timer;
    
    // $(window).on('mousemove', function() {
    //     clearInterval(timer);
    //     if(startReload){
    //         timer = setInterval(update, 20000);
    //     }
        
    // }).trigger('mousemove');
    
    // function update() {
    //   if($('.modal').hasClass('show')){
    //     console.log('modal show');
    //   } 
    //   else{
    //     if(startReload){
    //         var currentUrl = window.location.href;
    //         console.log('update');
    //         hot_reload(currentUrl);
    //     }
    //     else{
    //         console.log('expired');
    //         clearInterval(timer);
    //     }
    //   }
    // }
}

function hot_reload(gotoWhere){
    $.ajax({
        type: "GET",
        url:gotoWhere,
        beforeSend(){
            showFullLoader()
        },
        success: function(data){
            _append_tbody(data.list_data);
            $("#paging-navigation").html(data.data_pagination);
            $("#current_url").html(data.current_url);
            $("#total_data").html(data.total_data);
            // $("html, body").animate({ scrollTop: $("body").offset().top }, "slow");
            // scrollTo('table')
        },
        error: function(data){
            if(data.status == 419 || data.status == 401){
                van_modal();
                startReload = false;
            }
        }
    });
}

$('.btn-menu, [data-toggle="sidebar"]').click(function(){
    $('#sidebar').toggleClass('sidebar-toggle',function(){
        $('body').css('overflow','auto');
        if ($('.sidebar-backdrop').css("visibility") == "visible"){
            $('body').css('overflow','hidden');
        }
    });

});

$('.table-sortable #orderType').on('click',function(){
    //testing
    showFullLoader();
    var dataOrder = $(this).data('order');
    // alert(dataOrder);
    var dataColumn  = $(this).data('column');
    var changes = eval(dataOrder);
    // alert(changes);
    $(this).removeAttr('class').attr('class',border_order_table+' '+dataOrder).data('order',changes);

    $('.table-sortable #orderType').not($(this)).addClass('text-muted').removeClass(border_order_table);
    orderData(dataColumn,dataOrder);
});
function store_data() //would be deprecated
{
    var formInput = $('#formData');
    var btn = $('#btn-submit');
    var curr_text = btn.html();
    $('input, select').removeClass('error-input');
    $('[data-label="alert"]').html('');
    formInput.ajaxForm({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url : dynamic_url,
        type: 'POST',
        data: formInput.serialize(),
        beforeSend: function(arr, $form, options){
            showFullLoader()
            progressButtonStart(btn,curr_text)
        },
        success: function(data){
            if($.isEmptyObject(data.error || data.global_error)){
              if(modal_form){
                if(modal_form == 'redirect'){
                    append_to_basic_error(data.redirect_errors);
                  }
                  else{
                    $("#layout-form").html(data);
                  }
              }
              else{
                $("#response-click .modal-content").html(data);
                $("#response-click").modal({'keyboard':false,'backdrop':'static',});
              }
            }
            else if(data.global_error){
                $(".print-error-msg").find("ul").html('');
                $(".print-error-msg").css('display','block');
                $.each( data.global_error, function( key, value ) {
                    $(".print-error-msg").find("ul").append('<li>'+value+'</li>');
                });
            }
            else{
                append_to_basic_error(data.error);
            }
            hideFullLoader();
            progressButtonEnd(btn,curr_text)
        },
        error: function(){
            van_modal(['Communication Error','Please reload the page']);
        }
    });
}


function ajaxSubmit(formID)
{
    var formInput = $(formID);
    var btn = $('#btn-submit');
    var curr_text = btn.html();
    $('input, select').removeClass('error-input');
    $('[data-label="alert"]').html('');
    var formData = new FormData(formInput[0]);
    $.ajax({
        url: dynamic_url,
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: formData,
        processData: false,
        contentType: false,
        beforeSend: function(arr, $form, options){
            showFullLoader()
            progressButtonStart(btn,curr_text)
        },
        success: function(response){
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').remove();

            if($.isEmptyObject(response.errors || response.global_error)){
              if(modal_form){
                $("#layout-form").html(response);

              }
              else{
                $("#response-click .modal-content").html(response);
                $("#response-click").modal({'keyboard':false,'backdrop':'static',});
              }
            }
            else if(response.global_error){
                // alert('global');
                $(".print-error-msg").find("ul").html('');
                $(".print-error-msg").css('display','block');
                $.each( response.global_error, function( key, value ) {
                    $(".print-error-msg").find("ul").append('<li>'+value+'</li>');
                });
            }
            else{
                errorValidation(response)
            }
            hideFullLoader();
            progressButtonEnd(btn,curr_text)
        },
        error: function(){
            van_modal(['Communication Error','Please reload the page']);
        }
    });
}

function ajaxFormSubmit(url = null,form = null) {
    var formInput = (form === null) ? $('#formData'):$('#'+form);
    var btn = $('#btn-submit');
    var curr_text = btn.html();
    $('input, select').removeClass('error-input');
    $('[data-label="alert"]').html('');

    var submitUrl = (url === null) ? dynamic_url : url;

    var formData = new FormData(formInput[0]);

    $.ajax({
        url: submitUrl,
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: formData,
        processData: false,
        contentType: false,
        beforeSend: function () {
            showFullLoader();
            progressButtonStart(btn, curr_text);
            btn.prop('disabled', true); // Matikan tombol selama permintaan AJAX berlangsung
        }
    })
    .done(function (data) {
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').remove();
        if (data.errors) {
            errorValidation(data);
        } 
        else if ($.isEmptyObject(data.error || data.global_error)) {
            if(!$.isEmptyObject(data.jsFunction)){
                window[data.jsFunction](data); 
                // console.log(data)
            }
            if (modal_form) {
                $("#layout-form").html(data);
            } else {
                $("#response-click .modal-content").html(data);
                $("#response-click").modal({'keyboard': false, 'backdrop': 'static'});
            }
        } else if (data.global_error) {
            $(".print-error-msg").find("ul").html('');
            $(".print-error-msg").css('display', 'block');
            $.each(data.global_error, function (key, value) {
                $(".print-error-msg").find("ul").append('<li>' + value + '</li>');
            });
        }

        else {
            append_to_basic_error(data.error);
        }
    })
    .fail(function () {
        van_modal(['Communication Error', 'Please reload the page']);
    })
    .always(function () {
        hideFullLoader();
        progressButtonEnd(btn, curr_text);
        btn.prop('disabled', false); // Aktifkan tombol setelah permintaan selesai atau jika terjadi kesalahan
    });
}


function ajaxModalSubmit()
{
    var formInput = $('#formData');
    var btn = $('#btn-submit');
    var curr_text = btn.html();
    $('input, select').removeClass('error-input');
    $('[data-label="alert"]').html('');
    var formData = new FormData(formInput[0]);
    $.ajax({
        url: dynamic_url,
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: formData,
        processData: false,
        contentType: false,
        beforeSend: function(arr, $form, options){
            showFullLoader()
            progressButtonStart(btn,curr_text)
        },
        success: function(response){
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').remove();

            if($.isEmptyObject(response.errors || response.global_error)){
              if(modal_form){
                $("#layout-form").html(response);
              }
              else{
                $("#response-click .modal-content").html(response);
                $("#response-click").modal({'keyboard':false,'backdrop':'static',});
              }
            }
            else if(response.global_error){
                // alert('global');
                $(".print-error-msg").find("ul").html('');
                $(".print-error-msg").css('display','block');
                $.each( response.global_error, function( key, value ) {
                    $(".print-error-msg").find("ul").append('<li>'+value+'</li>');
                });
            }
            else{
                errorValidation(response)
            }
            hideFullLoader();
            progressButtonEnd(btn,curr_text)
        },
        error: function(){
            van_modal(['Communication Error','Please reload the page']);
        }
    });
}

function errorValidation(response){
    $.each(response.errors, function(key, value) {
        var input = $('[name="' + key + '"]');
        input.addClass('is-invalid');
        if (input.is('select')) {
            input.parent().append('<div class="invalid-feedback">' + value + '</div>');
        } else if (input.is('textarea')) {
            input.after('<div class="invalid-feedback">' + value + '</div>');
        } else {
            input.after('<div class="invalid-feedback">' + value + '</div>');
        }
    });

    if (typeof showToastNotif !== 'undefined') {
        $("#liveToast .toast-body").find("ul").html('');
        $("#liveToast .toast-header").addClass('bg-danger text-white');
        $('#liveToast .toast-header .header').html('Error Validation');
        $("#liveToast .toast-body").addClass('alert-danger');
        $('.toast').toast('show');
        $.each(response.errors, function(key, value) {
            $("#liveToast .toast-body").find("ul").append('<li>'+value+'</li>');
        });
    }
}



function append_to_basic_error(msg,formID = '#formData')
{
    var message = msg;
    for(var i = 0 ; i<message.length;i++){
        var arr = message[i].split('-@-');
        document.getElementsByName(arr[0])[0].className += ' error-input';
        $(formID+' .form-group').find('#'+arr[0]).html(arr[1]);
    }
}

// function append_to_basic_error(msg, formID = '#formData') {
//     var message = msg;
    
//     // Handle form error indicators
//     for(var i = 0; i < message.length; i++) {
//         var arr = message[i].split('-@-');
//         document.getElementsByName(arr[0])[0].className += ' error-input';
//         $(formID + ' .form-group').find('#' + arr[0]).html(arr[1]);
//     }

//     // Display errors in toast if toast functionality exists
//     if (typeof showToastNotif !== 'undefined') {
//         $("#liveToast .toast-body").find("ul").html('');
//         $("#liveToast .toast-header").addClass('bg-danger text-white');
//         $('#liveToast .toast-header .header').html('Error Validation');
//         $("#liveToast .toast-body").addClass('alert-danger');
        
//         for(var i = 0; i < message.length; i++) {
//             var arr = message[i].split('-@-');
//             $("#liveToast .toast-body").find("ul").append('<li>' + arr[1] + '</li>');
//         }
        
//         $('.toast').toast('show');
//     }
// }

function orderData(orderBy,orderType)
{
    var url        = window.location.href;
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': X_CSRF_TOKEN,
        },
        type: "GET",
        url:url,
        data: {'orderBy':orderBy,'orderType':orderType},
        beforeSend(){
            showFullLoader()
        },
        success: function(data){
            _append_tbody(data.list_data);
            $("#paging-navigation").html(data.data_pagination);
            $("#total_data").html(data.total_data);
            history.pushState(null, '',data.current_url);
        },
        error: function(data){
            if(data.status == 419 || data.status == 401){
                van_modal();
            }
        }
    });
}

$("#modalForm").on("show.bs.modal", function(e) {
    showFullLoader();
    $(this).find(".modal-content").html('');
    var link = $(e.relatedTarget);
    $(this).find(".modal-content").load(link.attr("href"),function (response,status,xhr){
        if(xhr.status == 419 || xhr.status == 401){
            van_modal();
        }
    });
});


function dateInit(fieldDate,start = null, end = null){
    var max = '+10Y';
    var min = '-10Y';
    if(start !== null){
        min = start;
    }
    if(end !== null){
        max = end;
    }
    $(fieldDate).datepicker({
        maxDate : max,
        minDate : min,
        dateFormat: "dd-mm-yy",
        changeMonth: true,
        changeYear: true,
    });
}


function initOldDateRange(fromOld,toOld,max){
    $(fromOld).datepicker({
        maxDate: 0,
        dateFormat: "dd-mm-yy",
        changeMonth: true,
        changeYear: true,
        onSelect: function onSelect(dateStr) {
            var maxDate = $(this).datepicker('getDate'); // Get selected date

            $(toOld).datepicker('option', 'minDate', maxDate || '0'); // Set other max, default to today
            if(max){
                var minDate = new Date(maxDate.valueOf());
                minDate.setDate(minDate.getDate() + max);
                $(toOld).datepicker('option', 'maxDate', minDate);
            }
        },
        onClose: function onClose() {
            $(toOld).focus();
        }
    });
    $(toOld).datepicker({
        maxDate: 0,
        dateFormat: "dd-mm-yy",
        changeMonth: true,
        changeYear: true,
        onSelect: function onSelect(dateStr) {
            
            // var start = $(fromOld).datepicker("getDate");
            var end = $(toOld).datepicker("getDate");
            var rawStart = $(fromOld).val();
            var rawEnd = $(toOld).val();

            if (rawStart == rawEnd) {
                end = end.setDate(end.getDate() + 1);
            }
            
        }
    });
}

function initNewDateRange(fromNew,toNew,set31=false){
    $(fromNew).datepicker({
        minDate: 0,
        dateFormat: "dd-mm-yy",
        changeMonth: true,
        changeYear: true,
        onSelect: function (dateStr) {
            var minDate = $(this).datepicker('getDate'); // Get selected date
            $(toNew).datepicker('option', 'minDate', minDate || '0'); // Set other max, default to today
            // var max = min+' +7D';
            // $("#sampai").datepicker('option','maxDate',min);
            // console.log(new Date(min+'7D'))
            if(set31){
                var maxDate = new Date(minDate.valueOf());
                maxDate.setDate(maxDate.getDate() + 31);
                $(toNew).datepicker('option', 'maxDate', maxDate);
            }

        },
        onClose: function(){
            $(toNew).focus();
        }
    });

    $(toNew).datepicker({
        minDate: 0,
        dateFormat: "dd-mm-yy",
        changeMonth: true,
        changeYear: true,
    });

}

//hanya untuk positif
function formatThousandSeparator(value) {
    // Remove non-numeric characters except dots
    let numericValue = value.toString().replace(/[^\d]/g, '');
    
    // Format with thousand separator if value exists
    if (numericValue) {
        return parseInt(numericValue).toLocaleString('id-ID');
    }
    // return '';
    return 0;
}

// Fungsi untuk format angka dengan titik sebagai pemisah ribuan
function formatNumberWithComma(number) {
    if (number == null || number === '') {
        return '';  // Kembalikan string kosong jika input null atau empty
    }

    var isNegative = number[0] === '-';
    if (isNegative) {
        number = number.substring(1);  // Hapus tanda minus untuk sementara
    }

    // Hapus semua karakter yang bukan angka atau koma
    number = number.toString().replace(/[^\d]/g, '');  // Hapus semua karakter non-angka

    // Pisahkan angka desimal (jika ada)
    var parts = number.split(',');  // Misalnya "1000,50" => ["1000", "50"]

    // Format bagian ribuan
    parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ".");  // Format ribuan dengan titik

    if (isNegative) {
        formattedNumber = '-' + formattedNumber;
        // formattedNumber = '0';
    }
    // Gabungkan kembali bagian ribuan dan desimal (jika ada)
    return parts.join(',');
}

function removeNumberSeparator(number) {
    if (number == null || number === '') {
        return 0; // Kembalikan string kosong jika number null, undefined, atau kosong
    }
    // return number.replace(/\./g, '');
    return number.toString().replace(/\./g, '');  // Menghapus titik
}

function onChangeFormatNumberWithComma(element){
    var inputValue = $(element).val();
    // Pastikan input adalah angka yang valid atau kosong
    if (isNaN(inputValue.replace(/\./g, '').replace(',', '.'))) {
        $(element).val(0);  // Kosongkan input jika bukan angka valid
    } else {
        var formattedValue = formatNumberWithComma(inputValue);  // Format dengan fungsi
        $(element).val(formattedValue);  // Set kembali nilai yang terformat ke input
    }
}


function showFullLoader(){
    $('.preload').addClass('full-loader');
    $('body').addClass('ov-h')
    
}

function hideFullLoader(){
    $('.preload').removeClass('full-loader');
    $('body').removeClass('ov-h')
    
}

function scrollTo(targetScroll){
    $('html, body').animate({
        scrollTop: $(targetScroll).offset().top
    }, 200);
}

function progressButtonStart(targetButtonStart,textBtn){
    var btn = $(targetButtonStart);
    // var curr_text = btn.html();
    btn.html(btn.data('loading'));
    btn.attr('disabled',true);
    //     btn.html(curr_text)
    //     btn.removeAttr('disabled');
}

function progressButtonEnd(targetButtonEnd,textBtn){
    var btn = $(targetButtonEnd);
    // var curr_text = btn.html();
    // btn.html(btn.data('loading'));
    // btn.attr('disabled',true);
    btn.html(textBtn)
    btn.removeAttr('disabled');
}

$(document).on("click","#btn-action", function(e){
    e.preventDefault();
    var id = $(this).data('id');
    var state = $(this).data('state');
    var title = $(this).attr('title');
    var func = $(this).data('func');
    var url = controller_path+'/'+func;

    $.confirm({
        theme: 'bootstrap',
        type: 'default',
        icon: 'fa fa-warning',
        title: title,
        content: 'Are you sure to continue ?',
        draggable: false,
        buttons: {
            confirm: {
                text: 'Yes',
                btnClass: 'btn-blue',
                action: function action() {
                    showFullLoader();
                    var data = {};
                    data['data0'] = $("#current_url").html();
                    // data['data0'] = 1;
                    data['data3'] = state;
                    data['data2'] = id;
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url : url,
                        type: 'GET',
                        data: data,
                        success: function(data){
                            if($('.modal').hasClass('show')){
                                // console.log('modal show');
                                $('#modalForm').modal('hide');
                                // $('#response-click').modal('hide');
                            }
                            $('#response-click .modal-content').html(data);
                            $('#response-click').modal({
                                show : true,
                                keyboard : false
                            });
                        },
                        error: function(data){
                            if(data.status == 419 || data.status == 401){
                                // alert('Your Session has expired !!!');
                                van_modal();
                                // location.reload();
                            }
                            else{
                                van_modal(['Error','Error Action '+data.status]);
                            }
                        }
                    });
                    //setelah ajax selesai,
                    hideFullLoader()
                }
            },
            cancel: {
                text: 'No'
            }
        }
    });
});

$(document).on("click","#btn-action-confirm", function(){
    // e.preventDefault();
    var id = $(this).data('id');
    var state = $(this).data('state');
    var title = $(this).attr('title');
    var func = $(this).data('func');
    var mandatory = $(this).data('mandatory');
    var url = controller_path+'/'+func;
    var placeHolderContent = $(this).data('placeholder');
    var required = mandatory ? 'required' : '';

    $.confirm({
        theme: 'bootstrap',
        type: 'default',
        icon: 'fa fa-warning',
        title: title,
        content: '' +
            '<form>' +
            '<div class="form-group">' +
            '<hr>'+
            '<label class="text-center">Notes</label>' +
            '<input type="text" placeholder="'+placeHolderContent+'" class="msgContent form-control" '+required+' />' +
            '</div>' +
            '</form>',
        draggable: false,
        buttons: {
            confirm: {
                text: 'Yes',
                btnClass: 'btn-blue',
                action: function action() {
                    var msgContent = this.$content.find('.msgContent').val();
                    if(required){
                        if(!msgContent){
                            $.alert('Message content required');
                            return false;
                        }
                    }
                    
                    showFullLoader();
                    var data = {};
                    data['data0'] = $("#current_url").html();
                    // data['data0'] = 1;
                    data['data3'] = state;
                    data['data2'] = id;
                    data['msgContent'] = msgContent;
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url : url,
                        type: 'GET',
                        data: data,
                        success: function(data){
                            if($('.modal').hasClass('show')){
                                // console.log('modal show');
                                $('#modalForm').modal('hide');
                                // $('#response-click').modal('hide');
                            }
                            $('#response-click .modal-content').html(data);
                            $('#response-click').modal({
                                show : true,
                                keyboard : false
                            });
                        },
                        error: function(data){
                            if(data.status == 419 || data.status == 401){
                                // alert('Your Session has expired !!!');
                                van_modal();
                                // location.reload();
                            }
                            else{
                                van_modal(['Error','Error Action '+data.statu]);
                            }
                        }
                    });
                    //setelah ajax selesai,
                    hideFullLoader()
                }
            },
            cancel: {
                text: 'No'
            }
        },
        onContentReady: function () {
            // bind to events
            var jc = this;
            this.$content.find('form').on('submit', function (e) {
                // if the user submits the form by pressing enter in the field.
                e.preventDefault();
                // jc.$$formSubmit.trigger('click'); // reference the button and click it
            });
        }
    });
});


function _append_tbody(data_json)
{
    $("tbody").fadeOut(150, function(){
        $("tbody").html('');
        var tr;
        if(data_json.length==0){
            $('tbody').append('<tr><td colspan=3 class="text-left text-md-center p-3 text-primary h6">Tidak ada data di halaman ini</td></tr>');
        }
        else{
            for(var i = 0 ; i<data_json.length;i++){
                tr = $('<tr/>');
                data_fields.map(function getField(field) {
                    tr.append("<td>" +data_json[i][field]+ "</td>");
                })
                $('tbody').append(tr);
            }
        }
    });

    $("tbody").fadeIn(80, function(){
        hideFullLoader()
    });
}


function van_modal(data_van=[]){
    ttl =  'Session Expired';
    ctn = 'Please re-Login';
    btn_txt = 'Login';
    if(data_van.length){
        ttl =  data_van[0];
        ctn = data_van[1];
        btn_txt = 'Reload';
    }
    hideFullLoader()
    $.confirm({
        theme: 'supervan',
        type: 'blue',
        icon: 'fa fa-warning',
        title: ttl,
        content: ctn,
        draggable: false,
        buttons: {
            yes: {
                text: btn_txt,
                action: function () {
                    location.reload();
                }
            },
        }
    });
}

$(document).on("click","#paging-navigation ul li a, #btn-reload-ajax, #shortcut", function(e){
    e.preventDefault();
    var _href = $(this).attr("href");

    $.ajax({
        type: "GET",
        url:_href,
        beforeSend(){
            showFullLoader()
        },
        success: function(data){
            _append_tbody(data.list_data);
            $("#paging-navigation").html(data.data_pagination);
            $("#current_url").html(data.current_url);
            $("#total_data").html(data.total_data);
            // $("html, body").animate({ scrollTop: $("body").offset().top }, "slow");
            history.pushState(null, '', _href);
            scrollTo('#target-top')
        },
        error: function(data){
            if(data.status == 419 || data.status == 401){
                // alert('Your Session has expired !!!');
                van_modal();
                // location.reload();
            }
        }
    });
});

// $('#paging-navigation ul li a, #btn-reload-ajax').click(function(){
//     scrollTo('#shortcut');
// });



function filter()
{

    var formFilter = $('#formFilter');
    var url        = controller_path;
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': X_CSRF_TOKEN,
        },
        type: "GET",
        url:url,
        data: formFilter.serialize()+ '&type=filter',
        beforeSend(){
            showFullLoader()
        },
        success: function(data){
            _append_tbody(data.list_data);
            $("#paging-navigation").html(data.data_pagination);
            $("#total_data").html(data.total_data);
            history.pushState(null, '', data.current_url);
            // reset_nav();
            $('#stringToSearch').val('');
        },
        error: function(data){
            if(data.status == 419 || data.status == 401){
                van_modal();
            }
        }
    });
    $('#side-filter').modal('hide')
}

// function get_index(){
//   var url        = controller_path+'/filter';
//   $.ajax({
//         headers: {
//           'X-CSRF-TOKEN': X_CSRF_TOKEN,
//         },
//         type: "GET",
//         url:url,
//         data: {"index" : "index"},
//         beforeSend(){
//           showFullLoader()
//         },
//         success: function(data){
//           _append_tbody(data.list_data);
//           $("#paging-navigation").html(data.data_pagination);
//           // $("#current_url").html(data.current_url);
//           $("#total_data").html(data.total_data);
//         },
//         error: function(data){
//           if(data.status == 419 || data.status == 401){
//             alert('Your Session has expired !!!');
//             hideFullLoader()
//           }
//         }
//     });
// }

function search_list(){
    var url         = controller_path;
    getColumn       = $('#getColumn').val();
    stringToSearch  = $('#stringToSearch').val();

    // e.preventDefault();
    showFullLoader()
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': X_CSRF_TOKEN,
        },
        type: "GET",
        url:url,
        data: {"type":"search","getColumn" : getColumn, "stringToSearch" : stringToSearch},
    }).done(function(data){
        $('#modalFilter').modal('hide');
        _append_tbody(data.list_data);
        $("#paging-navigation").html(data.data_pagination);
        $("#total_data").html(data.total_data);
        hideFullLoader()
        reset_nav();

        // Set URL dengan pushState setelah AJAX berhasil
        var newURL = window.location.pathname + '?type=search&getColumn=' + getColumn + '&stringToSearch=' + stringToSearch;
        history.pushState(null, '', newURL);
        // alert(newURL);
    });
}

$('#pageIndexSearch').on('submit', function (e){
    e.preventDefault();
    search_list()
});

$('#btn-string-filter').on("click",function(e)
{
    e.preventDefault();
    search_list()
    // var url         = controller_path+'/filter';
    // getColumn       = $('#getColumn').val();
    // searchType      = $('#searchType').val();
    // stringToSearch  = $('#stringToSearch').val();

    
    // showFullLoader()
    // $.ajax({
    //     headers: {
    //         'X-CSRF-TOKEN': X_CSRF_TOKEN,
    //     },
    //     type: "POST",
    //     url:url,
    //     data: {"getColumn" : getColumn, "searchType" : searchType, "stringToSearch" : stringToSearch,},
    // }).done(function(data){
    //     $('#modalFilter').modal('hide');
    //     _append_tbody(data.list_data);
    //     $("#paging-navigation").html(data.data_pagination);
    //     $("#total_data").html(data.total_data);
    //     hideFullLoader();
    //     reset_nav();
    // });
});

$('#getColumn').on('change', function(){
    changeSearchPlaceholder()
});

function changeSearchPlaceholder(){
    var selectElement = document.getElementById("getColumn");
    var selectedOption = selectElement.options[selectElement.selectedIndex];
    // alert(selectedOption.text)
    var input = document.getElementById("stringToSearch");
    input.placeholder = "Placeholder Baru";
    input.placeholder = 'Search For '+selectedOption.text;
}

function reset_nav(){
    // $.each($('#shortcut'), function() {
    //     alert(this.html());
    // });
    $('a[id^="shortcut"]').each(function () {
    // alert($(this).html());
    $(this).removeClass('active');
    });
    $('.first-all').addClass('active');

}


function appendMessageAPIConnect(response){
    var title = '<b class="text-success">'+response.title+'</b>';
    if(response.status !== 'ok'){
        title = '<b class="text-danger">'+response.title+'</b>';
    }
    $('#responseStatus').html('').html(title);
    $('#responseTotalData').html('').html(response.total_data);
    $('#responseMessage').html('').html(JSON.stringify(response.message, null, 2));
}


//===========CONFIRMATION DIALOG===========//
    //adjust from kovaln
    //how to use
    // $('#formPartialSudahSpk, #formPartialVoidIndent').on('submit', function(e) {
    //     e.preventDefault();
    //     showConfirmationDialog($(this));
    // });
function showConfirmationDialog($form) {
    var config = {
        url: $form.data('url'),
        formID: $form.attr('id'),
        confirmText: $form.data('partial'),
        description: $form.data('description'),
        requirement: $form.data('requirement'),
        alertColor: $form.data('alert-color') || 'warning'
    };
    $.confirm({
        title: config.confirmText,
        columnClass: 'col-md-8',
        content: buildDialogContent(config),
        buttons: {
            formSubmit: {
                text: 'Submit',
                btnClass: 'btn-blue',
                action: function() {
                    var name = this.$content.find('.name').val();
                    if (!name) {
                        $.alert('Keterangan/Referensi Harus Diisi');
                        return false;
                    }
                    $(`#${config.formID} input[name="description"]`).val(name);
                    ajaxFormSubmit(config.url, config.formID);

                    // return handleFormSubmission(this, config);
                }
            },
            cancel: function() {
                //close
            },
        },
        onContentReady: function() {
            this.$content.find('form').on('submit', function(e) {
                e.preventDefault();
                this.$$formSubmit.trigger('click');
            });
        }
    });
}

function buildDialogContent(config) {
    return `
            <div class="form-group">
                <div class="alert alert-${config.alertColor}">
                    ${config.description}
                </div>
                <label>${config.requirement}</label>
                <input type="text" 
                       placeholder="Keterangan/Referensi" 
                       name="keterangan" 
                       class="name form-control" 
                       required />
            </div>`;
}
//===========END CONFIRMATION DIALOG===========//