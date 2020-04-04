let textAreaEdited = false;
let editForm = $('form[name=edit_form]');
$(document).ready(function(){
    $('#inputDescription').change(function(){
        textAreaEdited = true;
    });
});

function editFormSubmit(){
    if (textAreaEdited == true){
        $('input[name=admin_edited]').val('1');
        console.log('-------- 2222 --------');
    }
    console.log('--------');
    console.log($('form[name=edit_form]'));
    console.log('--------');
    $(editForm).submit();
}