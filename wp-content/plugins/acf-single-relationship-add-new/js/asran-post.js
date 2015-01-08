jQuery(function($) {
   //Determine if we are running in an iframe, and if so, close the color box after successful input of a new post.
   if(parent.frames.length!=0) {
      if (parent.jQuery("#colorbox").css("display")=="block") {  
         //alert('ColorBox is currently open');
         parent.jQuery.colorbox.close();
      }else{  
         //alert('ColorBox is currently closed');  
      }
   }
   $('.acf_relationship').each(function(i, e) {
         //Get the post types associated with the relationship field.
         var posttypes = $(this).attr('data-post_type').split(',');
         
         //Only works with a single post type. Others are ignored.
         if(posttypes.length == 1) {
            //Determine if this is the hidden row (the only row we can guarantee will be in every repeater)
            if($(this).is('.row-clone .acf_relationship')) {
               if($(this).is('.row_layout .acf_relationship')) {
                  //$(this).closest('tr').find('.label').prepend('<ul class="hl"><li class="right"><a href="' + asran_custom.admin_url + '/post-new.php?post_type=' + posttypes + '&asran-colorbox=true" class="acf-button add-post cboxElement iframe repeater">Add New Profile</a></li></ul>');
                  $(this).parents('.repeater').find('.repeater-footer li.right').prepend('<a href="' + asran_custom.admin_url + '/post-new.php?post_type=' + posttypes + '&asran-colorbox=true" class="add-post cboxElement iframe repeater">Add New Profile</a> or ');
               } else {
                  //Find the column number the field is in so we can put the Add New button in the header.
                  var col = $(this).closest('td').index();
                  //Add the Colobox link and button to the proper location
                  //$(this).closest('table').find('tr > *:eq(' + col + ')').prepend('<ul class="hl"><li class="right"><a href="' + asran_custom.admin_url + '/post-new.php?post_type=' + posttypes + '&asran-colorbox=true" class="acf-button add-post cboxElement iframe repeater">Add New Profile</a></li></ul>');
                  $(this).parents('.repeater').find('.repeater-footer li.right').prepend('<a href="' + asran_custom.admin_url + '/post-new.php?post_type=' + posttypes + '&asran-colorbox=true" class="add-post cboxElement iframe repeater">Add New Profile</a> or ');
               }
            } else {
               //If it isn't a Repeater Row, then it is a stand-alone row. We skip the Repeater Rows.
               if(!$(this).is('.row .acf_relationship')) {
                  //Add the Colobox link and button to the proper location and fix the line up of the label.
                  $(this).closest('.field-relationship').find('p').append('<ul class="hl clearfix repeater-footer"><li class="right"><a href="' + asran_custom.admin_url + '/post-new.php?post_type=' + posttypes + '&asran-colorbox=true" class="acf-button add-post cboxElement iframe non-repeater">Add New Profile</a></li></ul>');
                  $(this).closest('.field-relationship').find('label').css('float','left');
               } else {
                  if($(this).is('.row_layout .acf_relationship')) {
                     //$(this).closest('tr').find('.label').prepend('<ul class="hl"><li class="right"><a href="' + asran_custom.admin_url + '/post-new.php?post_type=' + posttypes + '&asran-colorbox=true" class="acf-button add-post cboxElement iframe repeater">Add New Profile</a></li></ul>');
                  }
               }
               
            }
            //For any of the Colorbox links generated above.
            $(".iframe").colorbox({iframe:true, width:"90%", height:"90%", onClosed:function() { refresh_search(this); } });

         }
      });

});

function refresh_search(o) {
   (function($) {
      //Handle Repeater fields differently than Single Fields - class was added to the button link (repeater or non-repeater)
      if($(o).hasClass('repeater')) {
         var field = $(o).closest('table').find('.relationship_search');
      } else {
         var field = $(o).closest('.field-relationship').find('.relationship_search');
      }
      
      //Update any of the fields by calling the update function. If this function changes in the ACF code this function should be updated. Code copied from ACF / JS / input-actions.js  at about line 600.
      $.each(field, function(index, value) {
         var val = $(this).val(),
            div = $(this).closest('.acf_relationship');
                        
         // ajax
         try {
           acf.relationship_update_results( div );
         }
         catch(err) { //ignore errors here
         }         
         try {
         var _relationship = acf.fields.relationship;
          _relationship.update_results( div );
         }
         catch(err) { //ignore errors here
         }         
      });

   }) (jQuery)
}

