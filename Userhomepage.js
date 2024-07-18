function like() {
    console.log('like');
    $('#likebtn').css('color', 'red').css('font-size', '24px');
  }
  $('#YourPostsHP').on('click', function(e) {
    e.preventDefault();
    $.ajax({
      type: 'POST',
      url: 'DisplayCurrentPosts.php',
      data: $('#ypform').serialize(),
      success: function(response) {
        document.getElementById("posts").innerHTML = (response);
      },
      error: function(response) {
        $('#posts').innnerHTML = ("Error: " + response);
      }
    });
  });

  $('#searchpost').on('click', function(e) {
    e.preventDefault();
    $.ajax({
      type: 'POST',
      url: 'FetchSearchPost.php',
      data: $('#searchform').serialize(),
      success: function(response) {
        document.getElementById("searchresponse").innerHTML = (response);
      },
      error: function(response) {
        $('#searchresponse').innnerHTML = ("Error: " + response);
      }
    });
  });

  function asktoaddfriend(UIDtocheck,currentloginUID) {
    $.ajax({
      type: 'POST',
      url: 'DisplayUserProfileInUHP.php',
      data: {
        'UIDtocheck': UIDtocheck,
        'currentloginUID':currentloginUID,
      },
      success: function(response) {
        document.getElementById("displaypostprofile").innerHTML = (response);
      },
      error: function(response) {
        $('#displaypostprofile').innnerHTML = ("Error: " + response);
      }
    });
    var modalBg = document.getElementById('modalprofile');
    modalBg.style.display = 'flex';
    document.getElementById('currentPID').value = x;
  }

  function closeprofile() {
    var modalBg = document.getElementById('modalprofile');
    modalBg.style.display = 'none';
  }

  $('#postdata').submit(function(event) {
    event.preventDefault();
    var formData = new FormData($(this)[0]);
    $.ajax({
      type: 'post',
      url: 'CreateNewPost.php',
      data: formData,
      processData: false,
      contentType: false,
      success: function(response) {
        // alert('Success: ' + response);
        $('#displaymsg').html(response);
      },
      error: function(response) {
        // alert('Error: ' + response);
        $('#displaymsg').html(response);

      }
    });
  });

  function toggleModal() {
    var modalBg = document.getElementById('modalBg');
    modalBg.style.display = 'flex';
  }

  function closeModal() {
    var modalBg = document.getElementById('modalBg');
    modalBg.style.display = 'none';
  }

  function togglecomments(x) {

    $.ajax({
      type: 'POST',
      url: 'Userhomepage-comments.php',
      data: {
        'PID': x
      },
      success: function(response) {
        console.log(response);
        document.getElementById("comment").innerHTML = (response);
      },
      error: function(response) {
        console.log("Error: " + response);
      }
    });

    var modalBg = document.getElementById('modalcomment');
    modalBg.style.display = 'flex';
    document.getElementById('currentPID').value = x;
  }

  function closecomments() {
    var modalBg = document.getElementById('modalcomment');
    modalBg.style.display = 'none';
  }
  $('#addcomment').on('click', function(e) {

    e.preventDefault();

    $.ajax({
      type: 'POST',
      url: 'AddComment.php',
      data: $('#commenttdata').serialize(),
      success: function(response) {
        console.log(response);
        $('#comment').append(response);
      },
      error: function(response) {
        console.log("error :" + response);
      }
    });

  });


