<?php

$booking_id = Confirmation_Page_Session::get_instance()->get_booking_id($a['unset']); // Take the unset argument from the shortcode. True by default

if($booking_id):

  $ticket_quantity = eo_get_booking_meta($booking_id, 'ticket_quantity');
  $event_id = eo_get_booking_meta($booking_id, 'event_id');
  $occurrence_id = eo_get_booking_meta($booking_id, 'occurrence_id');

  $event_title = get_the_title($event_id);

  $event_date = eo_get_the_start(null, $event_id, null, $occurrence_id);

  $message = 'You have booked %d ticket(s) to %s on %s with booking reference %d. Please check your email for a copy of your tickets.';

  // Echo the booking confirmation message, passing it through gettext
  $booking_message = sprintf(__($message, 'eo-confirmation-page-session'),
    $ticket_quantity,
    $event_title,
    $event_date,
    $booking_id
    );?>

  <div class="confirmation-message">
    <?php echo $booking_message; ?>
  </div>

  <div class="confirmation-ticket-table">
    <?php 
    // Based on the code of eventorganiser_email_ticket_list()
    
    $booking_tickets = eo_get_booking_tickets( $booking_id, false );
    $total_price = eo_get_booking_meta( $booking_id, 'booking_amount' );
    

    $booking_table = sprintf(
      '<table>
      <thead><tr> <th>%s</th><th> %s </th></tr></thead>
      <tbody>',
      __( 'Ticket', 'eventorganiserp' ),
      __( 'Price', 'eventorganiserp' )
    );

    foreach ( $booking_tickets as $ticket ) {
      $booking_table .= sprintf(
        '<tr> <td>%s<td> %s </td></tr>',
        esc_html( $ticket->ticket_name ),
        eo_format_price( $ticket->ticket_price )
      );
    }

    $booking_table .= sprintf( '<tr> <td>%s</td><td> %s </td> <td></td></tr></tbody></table>', __( 'Total', 'eventorganiserp' ), eo_format_price( $total_price ) );

    echo $booking_table;
    ?>  
  </div>

<?php else: ?>
  
  <?php _e('No booking available.', 'eo-confirmation-page-session') ?>

<?php endif; ?>
  