jQuery( document ).ready( function($) {
  $( 'input:not([type="submit"]):not([readonly])' ).timepicker({
    timeFormat    : 'H:i', // 14:15
    scrollDefault : '7.30', // Defualt time
    step          : 15, // 15 Minute intervals
    disableTouchKeyboard : true, // No keyboards
    stopScrollPropagation : true
  });


  // For each column
  $( 'tr.start td' ).each( function( i ) {
    var start_elem    = $( this ).find( 'input' );
    var finish_elem   = $( 'tr.finish td:eq('+ i +') input' );
    var total_elem    = $( 'tr.total td:eq('+ i +') input' );
    var overtime_elem = $( 'tr.overtime td:eq('+ i +') input' );
    var calc_diffs    = function() {
      var start  = start_elem.timepicker( 'getTime' );
      var finish = finish_elem.timepicker( 'getTime' );

      // No start or finish? End.
      if ( ! start || ! finish ) {
        return;
      }

      var result  = Math.round( ( finish - start ) / 60000 );
      var hours   = Math.floor( result / 60  );
      var minutes = result - hours * 60;
      if ( (minutes + "").length == 1 ) {
        minutes = "0" + minutes;
      }
      // If worked over 9 hours...
      if ( hours >= 9 ) {
        var ohours = Math.floor( result / 60  ) - 9;
        if ( (ohours + "").length == 1 ) {
          ohours = "0" + ohours;
          overtime_elem.val( ohours+":"+minutes );
        }
      } else {
        overtime_elem.val( "00:00" );
      }
      if ( (hours + "").length == 1 ) {
        hours = "0" + hours;
      }
      total_elem.val( hours+":"+minutes );
    };
    // When we change the times, we figure it out
    start_elem.on( 'change keyup', calc_diffs );
    finish_elem.on( 'change keyup', calc_diffs );
  } );

  $('.hours-worked').on( 'change keyup', function() {
    var hours = [];
    $( 'tr.overtime input' ).each( function() {
      hours += $(this).val();
    });
    console.log( hours );
  });

});
