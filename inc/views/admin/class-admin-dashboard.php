<?php
/**
 * 
 */
namespace KsDataRetrieve\View\Admin;

if ( ! defined( 'ABSPATH' ) ) {
	die( 'You are not allowed to access this page directly.' );
}

use KsDataRetrieve\Helper\Ks_Data_Retrieve_Helper;
 
class Admin_Dashboard {
	public function init() {
		add_action( 'admin_menu', array( $this, 'register' ) );
	}

	public function register( $atts, $content = null ) {
		add_submenu_page(
			'tools.php',
			__( 'Employees', 'ks-employees' ),
			__( 'Employees', 'ks-employees' ),
			'manage_options',
			'employee-list-page',
			array( $this, 'employee_list' ),
			3
		);
	}

	public function employee_list() {
		$data_key = Ks_Data_Retrieve_Helper::get_data_key();
		$data_time_key = Ks_Data_Retrieve_Helper::get_data_time_key();
		$data_expiry_time = Ks_Data_Retrieve_Helper::get_data_expiry();

		$ks_force_request = false;
		if ( isset( $_GET['action'] ) && $_GET['action'] == "refresh") {
			// clear data
			Ks_Data_Retrieve_Helper::clear_employee_data();

			$ks_force_request = true;
		}

		// get data from wp_options table
		$old_data = get_option( $data_key );
		$last_req_time = get_option( $data_time_key );

		if ( ! $old_data ) {
			$ks_force_request = true;

		} else {
			// check if data is expired
			$current_time = time(); // get timestamp
			if ( (int) $current_time - (int) $last_req_time > $data_expiry_time) { // passed 12 hours => green light for api call.
				// clear data
				Ks_Data_Retrieve_Helper::clear_employee_data();

				$ks_force_request = true;
			}			
		}

		$err_msg = "";

		if ( $ks_force_request ) {
			$data = array();
			
			$api_url = Ks_Data_Retrieve_Helper::get_apiurl();

			$data = file_get_contents( $api_url );
			$data = json_decode( $data );

			if ( $data && $data->status = "success" ) {
				// successfully received data

				// store received customers data and current time into wp_options table
				if ( ! $old_data )
					add_option( $data_key, json_encode( $data ) );
				else
					update_option( $data_key, json_encode( $data ) );

				if ( ! $last_req_time )
					add_option( $data_time_key, time() );
				else
					update_option( $data_time_key, time() );

			} else {
				$err_msg = __( 'Something went wrong while getting customer data. Pleast contact support.', 'ks-employees' );
			}
		}	else {
			$data = json_decode( $old_data );
		}	
		
	?>
		<div class="ks-employee-page">
			<div>
				<div class="ks-logo">
					<span class="dashicons dashicons-id"></span>
				</div>
				<div class="ks-title">
					<h2><?php echo __( 'Employees', 'ks-employees' ); ?></h2>
					<a 
						class="button button-primary btn-refresh" 
						href="<?php echo esc_url( admin_url( 'admin.php?page=employee-list-page&action=refresh' ) ); ?>"
					>
						<?php echo __( 'Get Fresh Data', 'ks-employees' ); ?>
					</a>
				</div>
				<div class="clearfix"></div>
			</div>

			<div class="ks-employee-wrapper">
				<?php
				if ( ! empty( $err_msg ) ) :
				?>
				<p class="ks-error"><?php echo $err_msg;?></p>
				<?php
				else :
				?>
				<table class="wp-list-table widefat fixed striped">
					<thead>
						<tr>
							<th><?php echo __( 'ID', 'ks-employees' ); ?></th>
							<th><?php echo __( 'Name', 'ks-employees' ); ?></th>
							<th><?php echo __( 'Salary', 'ks-employees' ); ?></th>
							<th><?php echo __( 'Age', 'ks-employees' ); ?></th>
						</tr>
					</thead>
					<tbody>
					<?php
						foreach ( $data->data as $employee_data ) {
							echo '<tr>';
							echo '	<td>' . $employee_data->id . '</td>';
							echo '	<td>' . $employee_data->employee_name . '</td>';
							echo '	<td>' . Ks_Data_Retrieve_Helper::get_default_currency() . number_format( $employee_data->employee_salary ) . '</td>';
							echo '	<td>' . $employee_data->employee_age . '</td>';
							echo '</tr>';
						}
					?>
					</tbody>
				</table>
				<?php
				endif; // end of if ( ! empty( $errMsg ) ) :
				?>
			</div> <!-- end of .ks-employee-wrapper -->
		</div>
	<?php
	}
}