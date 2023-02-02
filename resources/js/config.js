export const Config	= function () {
	const apiDomain		= 'https://knosh.in/';
	const socketDomain	= 'https://knosh.in:8891/';
	const mode			= 'development';
	const getMessage	= apiDomain + 'chatMessage';
	const putMessage	= apiDomain + 'chatMessage';
	const data			= {
		'apiDomain'		: apiDomain,
		'socketDomain'	: socketDomain,
		'getMessage'	: getMessage,
		'putMessage'	: putMessage,
	};
	return data;
}