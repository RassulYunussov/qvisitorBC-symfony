$(document).ready(function(){
    $('#visitor-button').on('click', function(){
        $('#bd_visitors').css('display', 'none');
        $('#new_visitor').css('display', 'block');
        $('#myModalLabel').text('Добавление нового посетителя');
    });

    $('#back_btn').on('click', function(){
        $('#bd_visitors').css('display', 'block');
        $('#new_visitor').css('display', 'none');

        $('#myModalLabel').text('Список Ваших посетителей');
    });
    $('#myModal').on('hidden.bs.modal', function (e) {
        $('#new_visitor').css('display', 'none');
        $('#bd_visitors').css('display', 'block');
    });
    var array = [];
    $('#visitors tr').click(function(event){
        var name = $(this).attr('name');
        var i=0;
        $('#visitors-input').find('input:text').each(function(){
              if(  $(this).attr('value') == name){
                i++;
              }
        })
        if (i == 0){
        $('#visitors-input').append('<div class="visitor  form-margin2 form-group input-group"><input id="'+$(this).attr('value')+'" type="text" disabled="true" class="form-control" value="'+$(this).attr('name')+'"/><span class="input-group-btn"  id="'+$(this).attr('value')+'"><button onclick="a(this);" class="btn btn-default" id="oldDelete" type="button"><i class="fa fa-times"></i></button></span></div>');

            $("#form_visitors option[value='" + $(this).attr('value') + "']").prop("selected", true);
            console.log($(this).attr('value'));
            array.push($(this).attr('value'));}
    console.log(array);
    });  


    $('#form_visitors').removeAttr('required');
        var index = 1;
    $('#create-visitor-btn').on('click', function(){

        var ln_prototype = $('#form_lastnames').data('prototype');
        var lnForm = ln_prototype.replace(/__name__/g, index);

        var fn_prototype = $('#form_firstnames').data('prototype');
        var fnForm = fn_prototype.replace(/__name__/g, index);

        var p_prototype = $('#form_patronimics').data('prototype');
        var pForm = p_prototype.replace(/__name__/g, index);

        var bd_prototype = $('#form_birthdates').data('prototype');
        var bdForm = bd_prototype.replace(/__name__/g, index);

        var g_prototype = $('#form_genders').data('prototype');
        var gForm = g_prototype.replace(/__name__/g, index);

        var pn_prototype = $('#form_passport_numbers').data('prototype');
        var pnForm = pn_prototype.replace(/__name__/g, index);

        var pi_prototype = $('#form_passport_issuedates').data('prototype');
        var piForm = pi_prototype.replace(/__name__/g, index);

        var pe_prototype = $('#form_passport_expiredates').data('prototype');
        var peForm = pe_prototype.replace(/__name__/g, index);

        $('#new_visitors_tb').append('<tr id = "tr_'+index+'"><td>'+lnForm+'</td><td>'+fnForm+'</td><td>'+pForm+'</td><td>'+bdForm+'</td><td>'+gForm+'</td><td><input id="gender-'+index+'"></td><td>'+pnForm+'</td><td>'+piForm+'</td><td>'+peForm+'</td></tr>');
$('#visitors-input').append('<div class="visitor  form-margin2 form-group input-group"><input id="'+index+'" type="text" disabled="true" class="form-control" value="'+$('#lastname').val()+' '+$('#firstname').val()+' '+$('#patronimic').val()+'"/><span class="input-group-btn"  id="'+index+'"><button onclick="b(this);" class="btn btn-default" id="oldDelete" type="button"><i class="fa fa-times"></i></button></span></div>');

        $('#form_lastnames_'+index+'').css('display', 'none');
        $('#form_lastnames_'+index+'').attr('value',$('#lastname').val());
        $('#form_firstnames_'+index+'').css('display', 'none');
        $('#form_firstnames_'+index+'').attr('value',$('#firstname').val());
        $('#form_patronimics_'+index+'').css('display', 'none');
        $('#form_patronimics_'+index+'').attr('value',$('#patronimic').val());
        $('#form_birthdates_'+index+'').css('display', 'none');
        $('#form_birthdates_'+index+'').attr('value',$('#birthdate').val());
        $('#gender-'+index+'').val(''+$('#gender option:selected').text()+'');        
        $('#gender-'+index+'').css('display', 'none');
        $('#form_genders_'+index+'').css('display', 'none');
        $('#form_genders_'+index+'').attr('value',$('#gender').val());

        $('#form_passport_numbers_'+index+'').css('display', 'none');
        $('#form_passport_numbers_'+index+'').attr('value',$('#pNumber').val());

        $('#form_passport_issuedates_'+index+'').css('display', 'none');
        $('#form_passport_issuedates_'+index+'').attr('value',$('#pIssuedate').val());

        $('#form_passport_expiredates_'+index+'').css('display', 'none');
        $('#form_passport_expiredates_'+index+'').attr('value',$('#pExpiredate').val());


        index++;

        $('#new_visitors').find('label').each(function(){
                $(this).css('display', 'none');
        });

        $('#lastname').val('');
        $('#firstname').val('');
        $('#patronimic').val('');
        $('#birthdate').val('');
        $('#gender').val('2');

        $('#pNumber').val('');
        $('#pIssuedate').val('');
        $('#pExpiredate').val('');
    });

});

function a(object){
        $("#form_visitors option[value='" + $(object).parent().attr('id') + "']").prop("selected", false);
    console.log($('#oldDelete').parent().attr('id'));
$(object).parent().parent().remove();
};
function b(object) {
    $(object).parent().parent().remove();
    var index = $(object).parent().attr('id');
    $("#tr_"+index).remove();
    console.log(index);
};