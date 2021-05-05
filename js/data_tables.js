$(document).ready(function(){
    var table = $('#example_table').DataTable( {
        //"processing": true,
        //"serverSide": true,
        "ajax": "/ajax/fetch_tndev/",
	    "columns": [
            { "data": "ou" },
            { "data": "name" },
            { "data": "ipv4" }
        ]
    } );
});

