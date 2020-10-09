<?php
/**
 * 
 */
namespace KsDataRetrieve\View\FrontEnd;
 
class Employees_List_Table {

	public function get_employee_list_content() {
		ob_start();
		?>
			<div id="ks-employees">
				<div class="loader"></div>

				<h2 class="title"><?php echo __( 'Employee', 'ks-employees' ); ?></h2>
				<div class="ks-employees-wrapper">
				</div>
			</div>

			<script type="text/javascript">
				( function( $ ) {
					'use strict';

					$.ajax ( {
						type: 		'post',
						dataType: 'json',
						url: 			'<?php echo home_url();?>/wp-admin/admin-ajax.php',
						data: 		{ action: "ks_employees" },
						success: function( res ) {
							if ( res.status == 'error' ) {
								$( '#ks-employees .ks-employees-wrapper' ).html( res.message );

							} else {
								var outputHTML = getEmployeeListTemplate( res.data );
								$( '#ks-employees .ks-employees-wrapper' ).html( outputHTML );
							}

							// hide loading
							$( '.loader' ).remove();
						},
						error: function( err ) {
							// hide loading
							$( '.loader' ).remove();
						}
					} );
				} )( jQuery );
			</script>	
		<?php
		return ob_get_clean();
	}
}