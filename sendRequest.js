$(document).ready(function() {
    $('#find').click();
    var modalBg = document.getElementById('modalBg');
    modalBg.style.display = 'none';
});

function viewprofile(otherUserUID,othersemail) {
    var modalBg = document.getElementById('modalBg');
    modalBg.style.display = 'flex';

    $.ajax({
        type: 'POST',
        url: 'ViewProfile.php',
        data:{
            'UID':otherUserUID,
            'senduseremail':othersemail
        },
        success: function(response) {
            document.getElementById('UserProfile').innerHTML = response;
        },
        error: function(response) {
            $('#UserProfile').innerHTML = response;
        }
    });

}

function closeModal() {
    var modalBg = document.getElementById('modalBg');
    modalBg.style.display = 'none';
}
// function AddFriend(){

//     $.ajax({
//         type: 'POST',
//         url: 'SendRequest.php',
//         data:$('#Addfriend').serialize(),
//         success: function(response) {
//             alert(response);
//             // document.getElementById('UserProfile').innerHTML = response;
//         },
//         error: function(response) {
//             // $('#UserProfile').innerHTML = response;

//         }
//     });       
// }

$('#find').click(function(event) {

    event.preventDefault();

    $.ajax({
        type: 'POST',
        url: 'find_person.php',
        data: $('#searchuserform').serialize(),
        success: function(response) {
            document.getElementById('maindiv').innerHTML = response;
        },
        error: function(response) {
            $('#maindiv').innerHTML = response;
        }
    });



    $(document).on('click', '.SendFR', function(e) {
        e.preventDefault();
        var form = $(this).closest('form');
        $.ajax({
            type: 'POST',
            url: 'SendRequest.php',
            data: form.serialize(),
            success: function(response) {
                $('#reqsend').html(response);
            },
            error: function() {
                $('#reqsend').html('An error occurred.');
            }
        });
    });
});

function sendfr() {

    $.ajax({
        type: 'POST',
        url: 'SendFriendRequest.php',
        data: $('#sendfrform').serialize(),
        success: function(response) {
            console.log(response)
        },
        error: function(response) {
            console.log(response);
        }
    });

}

