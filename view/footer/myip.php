	</body>
	<script type="application/javascript">
	  $(function() {
		$.getJSON("https://api.ipify.org?format=jsonp&callback=?",
		  function(json) {
			console.log(json);
			$('p.public.ip').text(json.ip);
		  }
		);
	  });
	</script>
</html>


