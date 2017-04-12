	function completed() {
		$("#message").fadeIn(100);
      	$("#server_res").html(" Success!");
		$("progress-section").hide(100);
		$("#currant_domain").html('');
      window.clearInterval(timer);
    }
	
	function checkDomains() {
		var str = $( '.str' ).val();
		$( '#submision' ).hide();
		$( '#responce_wrapper' ).fadeIn( 300 );
		$( '#stringe_wrapper' ).fadeIn( 300 );
		
		$.ajax({
			url:"_server/site.php",
			data: 'str=' + str,
			beforeSend: function() {
				$( '#loader' ).html( '<div class="loader">Loading...</div>' );
			},
			success: function( data ) {
				$( '#loader' ).html( '' );
			}
		});
		timer = window.setInterval(refreshProgress, 1000);
	}
	
	$(document).ready(function () {

    $('.btn-filter').on('click', function () {
      var $target = $(this).data('target');
      if ($target != 'all') {
        $('.table tr').css('display', 'none');
        $('.table tr[data-status="' + $target + '"]').fadeIn('slow');
      } else {
        $('.table tr').css('display', 'none').fadeIn('slow');
      }
    });

 });
 
 /*
 $(function(){
    $('#frameModal').modal({
        keyboard: true,
        backdrop: "static",
        show:false,
        
    }).on('show', function(){
          var site = $(this).data('site');
    });
    
    $(".table").find('tr[data-target]').on('click', function(){
        	var site = $(this).data('site');
         	$('#frameModal').data('site',$(this).data('id'));
		 	$('.framed_site').attr( 'src', site );
    });
    
});
*/