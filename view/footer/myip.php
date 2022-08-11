	</body>
	<script type="application/javascript">
	  $(function() {
		$.getJSON("https://api.ipify.org?format=jsonp&callback=?",
		  function(data) {
			console.log(data);
			$('p.public.ip').text(data.ip);
		  }
		);
	  });
	</script>
</html>
