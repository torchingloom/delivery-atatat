$(
	function()
	{
		$(".use-datepicker").datepicker
		(
			{
				dateFormat: "yy-mm-dd"
			}
		);
		$(".use-datetimepicker").datetimepicker
        (
            {
                dateFormat: "yy-mm-dd",
                timeFormat: 'hh:mm',
                formatTime: 'hh:mm'
		    }
        );
	}
);
