/* Formatting function for row details - modify as you need */
function format ( d ) {
    // `d` is the original data object for the row
    return '<table cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;">'+
        '<tr>'+
            '<td>Tel:</td>'+
            '<td>'+d.tel+'</td>'+
        '</tr>'+
        '<tr>'+
            '<td>Mail:</td>'+
            '<td>'+d.mail+'</td>'+
        '</tr>'+
        '<tr>'+
            '<td>Disabled at:</td>'+
            '<td>'+d.disabled_at+'</td>'+
        '</tr>'+
        '<tr>'+
            '<td>Shutdown at:</td>'+
            '<td>'+d.shut_at+'</td>'+
        '</tr>'+
    '</table>';
}

$(document).ready(function(){

	// Setup - add a text input to each footer cell
    $('#example_table tfoot th:not(:first-child)').each( function () {
        var title = $(this).text();
        $(this).html( '<input type="text" placeholder="Search '+title+'" />' );
    } );
 

	// DataTable
    var datatable = $('#example_table').DataTable( {
        processing: true,
        //serverSide: true,
        ajax: '/ajax/tndev/',
	    columns: [
			{
                data:	null,
                className: 'details-control',
                orderable: false,
                defaultContent: '<i class="green plus circle icon"></i>',
            },
            { data: 'department' },
            { data: 'section' },
            {
				data: 'ip',
				render: function(data, type, row, meta) {
                    var render_data = "";
                    if (data) {
                        var ip_array = data.split(",");
                        ip_array.forEach(function(item) {
                           render_data += "<p>" + "<div class='ui basic grey label'>" + item + "</div>" + "</p>";
                        });
                    }
                    return render_data;
                }
           	}, 
            { data: 'name' },
            { data: 'description' },
            { data: 'owner' },
            { data: 
                'antivirus', 
				render: function(data, type, row, meta) {
                    var render_data = data;
                    var label = data;
                    if (data === "1") {
                        render_data = "<div class='ui red circular label'>" + label + "</div>";
                    }  
                    return render_data;
                }
            },
            { data: 
                'edr_corecloud',
				render: function(data, type, row, meta) {
                    var render_data = data;
                    var label = data;
                    if (data === "1") {
                        render_data = "<div class='ui orange circular label'>" + label + "</div>";
                    }  
                    return render_data;
                }

            },
            { data: 
                'edr_fireeye_concat',
				render: function(data, type, row, meta) {
                    var render_data = data;
                    var label = data;
                    var sections = data.split(',');
                    var flag = sections[0];
                    var unreported_day = sections[1];

                    if (flag === "1") {
                        render_data = "<div class='ui yellow circular label'>" + flag + "</div>";
                        render_data += "<div class='ui circular label'><i class='eye slash icon'></i>" + unreported_day + "</div>";
                    } else {
                        render_data = "0";
                    }
                    return render_data;
                }
                
            },
            { data: 
                'gcb',
				render: function(data, type, row, meta) {
                    var render_data = data;
                    var label = data;
                    if (data === "1") {
                        render_data = "<div class='ui olive circular label'>" + label + "</div>";
                    }  
                    return render_data;
                }
            },
            //{ data: 'shut_at' },
        ], 
		columnDefs: [
			{
				targets: 0,
				width: "1%",
			},
			{
				targets: 3,
				width: "10%",
			},
            //{
            //    target: 11,
            //    visible: false,
            //}
		],
        order: [
            [1, 'asc'],
            [2, 'asc'],
            [3, 'asc'],
        ],
        initComplete: function () {
            // Apply the search
            this.api().columns().every( function () {
                var that = this;
 
                $( 'input', this.footer() ).on( 'keyup change clear', function () {
                    if ( that.search() !== this.value ) {
                        that
                            .search( this.value )
                            .draw();
                    }
                } );
            } );
        },
    } );

	// Add event listener for opening and closing details
    $('#example_table tbody').on('click', 'td.details-control', function () {
        var tr = $(this).closest('tr');
        var row = datatable.row( tr );
        var td = $(tr).children().first();
 
        if ( row.child.isShown() ) {
            // This row is already open - close it
            row.child.hide();
            tr.removeClass('shown');
            $(td).html('<i class="green plus circle icon"></i>');
        }
        else {
            // Open this row
            row.child( format(row.data()) ).show();
            tr.addClass('shown');
            $(td).html('<i class="red minus circle icon"></i>');
        }
    } );


});
