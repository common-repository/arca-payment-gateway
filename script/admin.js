jQuery(document).ready(function(){

   jQuery('.arca-pg-thickbox').on("click", function() {
   tb_click.call(this);
      var alink = jQuery(this).parents('.available-theme').find('.activatelink');
      var href = jQuery(this).attr('href');
      var url, text;
      var link = "Title";
      return false;
   });

	// How To Use
	jQuery(document).ready(function(){
		jQuery("#HowToUse h2").click(function(){
			jQuery(this).next("ol").slideToggle("fast");
		});
	});
	
   // if not ameria bank hide ameria fields 
   jQuery("#apg-bank-switcher").change(function(){
      if( jQuery("#apg-bank-switcher").val() == 10 ){
         jQuery(".apg-ameria-fields").show();
      } else {
         jQuery(".apg-ameria-fields").hide();
      }
   });

   jQuery(".apg .jsonView").on("click", function() {
      jQuery("#apg-jsonView textarea").html( JSON.stringify( JSON.parse(jQuery(this).find('.jsonData').html()) , undefined, 4) );
      jQuery("#apg-jsonView").show();
   });

   jQuery(".popupClose, .popupCloseButton").on("click", function() {
      jQuery(".apg-popup").hide();
   });

   jQuery(document).keyup(function(e) {
      if (e.key === "Escape") {
         jQuery(".apg-popup").hide();
      }
   });

   jQuery(".apg-how-to-use-button").on("click", function(){
      jQuery(".apg-layout").removeClass("apg-hidden");
   });

   jQuery(".apg-layout:not(.apg-how-to-use-popup), .apg-close").on("click", function(){
      jQuery(".apg-layout").addClass("apg-hidden");
   });

   jQuery(".show-hide").mousedown(function() {
      jQuery(this).prev(".api-password").attr("type", "text");
   });

   jQuery(".show-hide").mouseup(function() {
      jQuery(this).prev(".api-password").attr("type", "password");
   });

   // on deactivate
   jQuery(document).on("click", "#deactivate-arca-payment-gateway", function () {
      jQuery("#skip-and-deactivate").attr( "href", jQuery(this).attr("href") );
      jQuery("#apg-deactivate-url").val( jQuery(this).attr("href") );
      jQuery("#apg-deactivate-popup").show();
      return false;
   });

   // show / hide other deactivate reason fields
   jQuery(".apg-deactivate-form-body input").on("change", function(){

      // hide and disable all other reason field / textarea
      jQuery(".apg-reason-other-field").hide();
      jQuery(".apg-deactivate-form-body textarea").prop('disabled', true);

      // show and enable current reason field / textarea
      jQuery("#apg-reason-other-field-"+jQuery(this).val()).show();
      jQuery("#apg-reason-other-field-"+jQuery(this).val()+" textarea").prop('disabled', false);

   });

   jQuery("#apg-deactivate-popup form").submit(function(event) {
      
      if ( ! jQuery(".apg-deactivate-form-body input[type=radio]").is(':checked') ){
         jQuery("#apg-deactivation-error-msg").show();
         return false; 
      }

   });


});

function CopyToClipboard(id){
   var r = document.createRange();
   r.selectNode(document.getElementById(id));
   window.getSelection().removeAllRanges();
   window.getSelection().addRange(r);
   document.execCommand('copy');
   window.getSelection().removeAllRanges();
   alert(arcapg_admin.copied_to_clipboard);
}

function confirmDelete(){
   return confirm(arcapg_admin.confirm_delete);
}

function CopyToClipboard(id){
   var r = document.createRange();
   r.selectNode(document.getElementById(id));
   window.getSelection().removeAllRanges();
   window.getSelection().addRange(r);
   document.execCommand('copy');
   window.getSelection().removeAllRanges();
   alert(arcapg_admin.copied_to_clipboard);
}

function generateShortcode(){
   var productid = document.getElementById("productid").value;
   var language = document.getElementById("language").value;
   var currency = document.getElementById("currency").value;

   var shortcode = '[arca-pg-form productid="'+productid+'" language="hy" currency="AMD"]';
   shortcode = shortcode.replace( /(language=")(.*?)(")/gi , "$1"+language+"$3");
   shortcode = shortcode.replace( /(currency=")(.*?)(")/gi , "$1"+currency+"$3");
   document.getElementById("shortcode-1").innerHTML = shortcode;

   var shortcode = '[arca-pg-button productid="'+productid+'" language="hy" currency="AMD"]';
   shortcode = shortcode.replace( /(language=")(.*?)(")/gi , "$1"+language+"$3");
   shortcode = shortcode.replace( /(currency=")(.*?)(")/gi , "$1"+currency+"$3");
   document.getElementById("shortcode-2").innerHTML = shortcode;
}















