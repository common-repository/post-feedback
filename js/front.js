jQuery(document).ready(function($){
  $("#pf_submit_button").click(submitFeedback);


  function submitFeedback() {
    postId = $(this).attr("data-post_id");
    feedbackId = $("#pf_select").val();
    nonce = $(this).attr("data-nonce");

    $.ajax({
      type : "post",
      dataType : "json",
      url : frontAjax.ajaxurl,
      data : {action: "pf_submit_feedback", post_id : postId, nonce: nonce, feedback_id: feedbackId},
      complete: function(response) {
        $('.pf_content').hide();
        $('.pf_message').text('Thank you for your feedback');
        $('.pf_message').show();
        setTimeout(function() {$('.pf_message').hide()}, 3000)
      }
    })
    return false;
  }
})
