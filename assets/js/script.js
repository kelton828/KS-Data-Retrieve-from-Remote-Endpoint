function getEmployeeListTemplate( data ) {
	var outputHTML = '';
	outputHTML = '<table cellpadding="0" cellspacing="0">' + 
				'<thead><tr>' + // start of header
					'<th>ID</th>' + 
					'<th>Name</th>' + 
					'<th>Salary</th>' + 
					'<th>Age</th>' + 
				'</tr></thead>' + // end of header
				'<tbody>'; // start of body

	for ( const [ key, employee_data ] of Object.entries( data ) ) {
		outputHTML += '<tr id="' + key + '">' + 
					'<td>' + employee_data.id + '</td>' + 
					'<td>' + employee_data.employee_name + '</td>' + 
					'<td>' + employee_data.employee_salary.toLocaleString() + '</td>' + 
					'<td>' + employee_data.employee_age + '</td>' + 
				'</tr>';
	}
	outputHTML += '</tbody></table>'; // end of table

	return outputHTML;
}