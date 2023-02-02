 <!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>{!! CNF_APPNAME !!} Email</title>
	<link href="https://fonts.googleapis.com/css?family=Montserrat&display=swap" rel="stylesheet">
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	<style type="text/css">
		body{ font-family: 'Montserrat', sans-serif; }
		td{line-height: 28px;}
	</style>
</head>
<body style="margin: 0; padding: 0;">
	<table  cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px;margin: auto;">

		@include('mail.header')
		<table style="max-width: 600px;margin: auto;text-align: left;width: 100%">
			<tr>
				<th>Hi {!! $userData->name ?? '' !!}</th>
			</tr>
			<tr>
				<td style="white-space: pre-wrap;">		<?php print_r($msg ?? '');?></td>
			</tr>
		</table>
		<tr>
			<td>
				<table align="center"  cellpadding="0" cellspacing="0" style="max-width:600px;border-top: 3px solid #f55a60;padding-top: 10px;border-left: 1px solid  rgba(185, 185, 185, 0.2196078431372549);border-right: 1px solid  rgba(185, 185, 185, 0.2196078431372549);margin-bottom: 20px;border-bottom: 3px solid #f55a60;margin-top: 0px;">
					<tr>
						<td>
							<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="padding: 0px 0px;">
								@yield('content')
								@include('mail.footer')
							</table>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</body>
</html>