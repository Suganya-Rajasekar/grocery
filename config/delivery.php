<?php
///// CHANGE TESTING LAT & LANG IN "Controllers/Api/Rider/DunzoController.php"
if(env('APP_ENV') == 'local'){
	return [
		'DUNZO_BASE'		=> 'https://apis-staging.dunzo.in/api/v1/',
		'DUNZO_BASE_V2'		=> 'https://apis-staging.dunzo.in/api/v2/',
		'DUNZO_CLIENT_ID'	=> 'd6e8f077-0b4a-47be-bef6-852f17f42d7b',
		'DUNZO_CLIENT_SC'	=> '6ede27d5-8859-42b1-8b46-f6199f62d591',
		'DUNZO_TOKEN'		=> 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJkIjp7InJvbGUiOjEwMCwidWlkIjoiNDcyM2NiZGItN2UxMi00OGVkLTgyZGItMWRhMjFmMWRmMjcyIn0sIm1lcmNoYW50X3R5cGUiOm51bGwsImNsaWVudF9pZCI6ImQ2ZThmMDc3LTBiNGEtNDdiZS1iZWY2LTg1MmYxN2Y0MmQ3YiIsImF1ZCI6Imh0dHBzOi8vaWRlbnRpdHl0b29sa2l0Lmdvb2dsZWFwaXMuY29tL2dvb2dsZS5pZGVudGl0eS5pZGVudGl0eXRvb2xraXQudjEuSWRlbnRpdHlUb29sa2l0IiwibmFtZSI6InRlc3RfMTM3MjgwMjEyNCIsInV1aWQiOiI0NzIzY2JkYi03ZTEyLTQ4ZWQtODJkYi0xZGEyMWYxZGYyNzIiLCJyb2xlIjoxMDAsImR1bnpvX2tleSI6ImNjZWVmOTIyLTYwOWMtNGU1ZS05M2E4LWZiZmQ3ZDNlNWZmZiIsImV4cCI6MTc3OTQzNTE5OCwidiI6MCwiaWF0IjoxNjIzOTE1MTk4LCJzZWNyZXRfa2V5IjoiZDc3MTMwNTEtYWRiMi00NTNiLWE3ODktZjY2YzY3NjJkOWQxIn0.HI8bIavQUn3q21jRMWTtRMwz6ImXl-fi35uxlSitx-U',
		'SHADOW_BASE_SANDBOX'	=> 'http://hobbit.demo.shadowfax.in/app/v3/sandbox/',
		'SHADOW_BASE'			=> 'http://hobbit.demo.shadowfax.in/api/v2/',
		'SHADOW_BASE_V1'		=> 'http://hobbit.demo.shadowfax.in/api/v1/',
		'SHADOW_BASE_V2'		=> 'http://hobbit.demo.shadowfax.in/api/v2/',
		'SHADOW_TOKEN'			=> 'd34decee045426fa0e6f7401f5221f5141aec776',
		'SHADOW_CODE'			=> 'empericafoods001',
	];
}
//PRODUCTION
return [
	'DUNZO_BASE'		=> 'https://api.dunzo.in/api/v1/',
	'DUNZO_BASE_V2'		=> 'https://api.dunzo.in/api/v2/',
	'DUNZO_CLIENT_ID'	=> '7147374f-65db-4365-baf4-008a26166885',
	'DUNZO_CLIENT_SC'	=> '8e63923c-839d-484c-8c7a-51bddad7c7e4',
	'DUNZO_TOKEN'		=> 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJkIjp7InJvbGUiOjEwMCwidWlkIjoiZTc5YThjYWQtNTMzNi00MmY4LWJmMGYtMzg1ZjdjODNiNjM3In0sIm1lcmNoYW50X3R5cGUiOm51bGwsImNsaWVudF9pZCI6IjcxNDczNzRmLTY1ZGItNDM2NS1iYWY0LTAwOGEyNjE2Njg4NSIsImF1ZCI6Imh0dHBzOi8vaWRlbnRpdHl0b29sa2l0Lmdvb2dsZWFwaXMuY29tL2dvb2dsZS5pZGVudGl0eS5pZGVudGl0eXRvb2xraXQudjEuSWRlbnRpdHlUb29sa2l0IiwibmFtZSI6Iktub3NoIiwidXVpZCI6ImU3OWE4Y2FkLTUzMzYtNDJmOC1iZjBmLTM4NWY3YzgzYjYzNyIsInJvbGUiOjEwMCwiZHVuem9fa2V5IjoiZDc3OTA5M2UtNjY0My00NTViLWFkYTItZjhkNzM3M2NhYjljIiwiZXhwIjoxNzkwMTYxOTAwLCJ2IjowLCJpYXQiOjE2MzQ2NDE5MDAsInNlY3JldF9rZXkiOiJhZTQ3ZTMxMC01ZWZlLTRiM2YtODhkMi1jYTUzY2E5MTFkZTUifQ.3Ngou3oa83rpNZ7iL2Ft4i9aDEC4IF5R4ir8NK_Y07Y',
	'SHADOW_BASE_SANDBOX'	=> 'http://api.shadowfax.in/api/v1/',
	'SHADOW_BASE'			=> 'http://api.shadowfax.in/api/v1/',
	'SHADOW_BASE_V1'		=> 'http://api.shadowfax.in/api/v1/',
	'SHADOW_BASE_V2'		=> 'http://api.shadowfax.in/api/v2/',
	'SHADOW_TOKEN'			=> '7b1941e27e99065784020ac224cd20047ccd4ff5',
	'SHADOW_CODE'			=> 'emprica001',
];