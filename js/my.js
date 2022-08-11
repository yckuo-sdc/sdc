$(document).ready(function(){
    $.ajax({
         url: '/ajax/myip.php',
         cache: false,
         dataType:'json',
         type:'get',
    })
    .done(function(data) {
         console.log(data);
         $('p.mac').text(data.mac); 
    })
    .fail(function(jqXHR) {
    });
});
