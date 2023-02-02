import SocketIO from 'socket.io-client';

import {Config}  from '../config';

const socketUrl = Config().socketDomain;

const options = {
  reconnectionAttempts: 3,
  transports: ["websocket", "polling"]
};

export default SocketIO(socketUrl, options);