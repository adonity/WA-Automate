"use strict";

Object.defineProperty(exports, "__esModule", {
  value: true
});
exports.io = exports.default = void 0;

require("dotenv/config");

var _express = _interopRequireDefault(require("express"));

var _nodeCleanup = _interopRequireDefault(require("node-cleanup"));

var _routes = _interopRequireDefault(require("./routes.js"));

var _whatsapp = require("./whatsapp.js");

var _socket = require("socket.io");

var _axios = _interopRequireDefault(require("axios"));

var _baileys = require("@adiwajshing/baileys");

var _path = _interopRequireDefault(require("path"));

var _url = require("url");

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

const _filename = (0, _url.fileURLToPath)(import.meta.url); // 👇️ "/home/john/Desktop/javascript"


const _dirname = _path.default.dirname(_filename);

const app = (0, _express.default)();
const host = process.env.HOST ?? '127.0.0.1';
const port = parseInt(process.env.PORT ?? 8000);
app.use(_express.default.urlencoded({
  extended: true
}));
app.use(_express.default.json());
app.use('/media', _express.default.static(_dirname + '/media'));
app.use('/', _routes.default);
const {
  version,
  isLatest
} = await (0, _baileys.fetchLatestBaileysVersion)();
console.log(`using WA web v${version.join('.')}, isLatest: ${isLatest}`);
const io = new _socket.Server(app.listen(port, host, () => {
  (0, _whatsapp.init)();
  console.log(`Server is listening on http://${host}:${port}`);
}), {
  cors: {
    origin: '*'
  }
});
exports.io = io;
io.on('connection', function (socket) {
  console.log('conntected');

  if (socket.handshake.query.ids) {
    const _ids = JSON.parse(socket.handshake.query.ids);

    socket.join(_ids);
  }

  socket.on('disconnect', reason => {
    console.log('disconnected : ' + reason);
  });
  socket.on('broadcast', async ({
    devices,
    contacts,
    message,
    id,
    delay: bdelay
  }) => {
    console.log(`broadcast ${id} start`);
    let i = 0;
    console.log(devices);

    for (const contact of contacts) {
      const device = devices[i];
      const session = (0, _whatsapp.getSession)(device.id + '');

      const _delay = bdelay.split('-');

      let delaystart = 0;
      let delayend = 0;

      if (_delay.length > 1) {
        delaystart = parseInt(_delay[0]) / 100;
        delayend = parseInt(_delay[1]) / 100;
      } else if (_delay.length == 1) {
        delayend = parseInt(_delay[0]) / 100;
      }

      const randelay = Math.floor(Math.random() * delayend - delaystart) + delaystart;

      if (session != null) {
        try {
          const number = (0, _whatsapp.formatPhone)(contact.contact.number);
          const exists = await (0, _whatsapp.isExists)(session, number);

          if (!exists) {
            updateBlastStatus({
              id,
              device_id: device.id,
              contact_id: contact.id,
              status: '0'
            });
            continue;
          }

          await (0, _baileys.delay)(randelay * 100);
          session.sendMessage(number, message);
          updateBlastStatus({
            id,
            device_id: device.id,
            contact_id: contact.id,
            status: '1'
          });
        } catch {
          updateBlastStatus({
            id,
            device_id: device.id,
            contact_id: contact.id,
            status: '0'
          });
        }
      } else {
        updateBlastStatus({
          id,
          device_id: device.id,
          contact_id: contact.id,
          status: '0'
        });
      }

      i = i == devices.length - 1 ? 0 : i + 1;
    }
  });
  socket.on('broadcast-new', async ({
    blasts,
    id,
    delay: bdelay
  }) => {
    console.log(`broadcast ${id} start`);
    var _break = false;
    socket.on('broadcast-break', async ({
      id_blast
    }) => {
      if (id_blast === id) {
        _break = true;
      }
    });

    for (const blast of blasts) {
      const session = (0, _whatsapp.getSession)(blast.device_id + '');

      const _delay = bdelay.split('-');

      let delaystart = 0;
      let delayend = 0;

      if (_delay.length > 1) {
        delaystart = parseInt(_delay[0]) / 100;
        delayend = parseInt(_delay[1]) / 100;
      } else if (_delay.length == 1) {
        delayend = parseInt(_delay[0]) / 100;
      }

      const randelay = Math.floor(Math.random() * delayend - delaystart) + delaystart;

      if (session != null) {
        try {
          const number = (0, _whatsapp.formatPhone)(blast.number);
          const exists = await (0, _whatsapp.isExists)(session, number);

          if (!exists) {
            updateBlastStatus({
              id,
              device_id: blast.device_id,
              contact_id: blast.contact_id,
              status: '0'
            });
            continue;
          }

          await (0, _baileys.delay)(randelay * 100);

          if (_break) {
            break;
          }

          session.sendMessage(number, blast.message);
          updateBlastStatus({
            id,
            device_id: blast.device_id,
            contact_id: blast.contact_id,
            status: '1'
          });
        } catch {
          updateBlastStatus({
            id,
            device_id: blast.device_id,
            contact_id: blast.contact_id,
            status: '0'
          });
        }
      } else {
        updateBlastStatus({
          id,
          device_id: blast.device_id,
          contact_id: blast.contact_id,
          status: '0'
        });
      }
    }
  });
});

const updateBlastStatus = ({
  id,
  device_id,
  contact_id,
  status
}) => {
  _axios.default.post(process.env.SERVER_URL + '/api/blast/status-update', {
    contact_id,
    status,
    device_id,
    id
  }).then(function (response) {
    io.emit('blast-' + id, {
      message: {
        contact_id,
        status,
        device_id,
        id
      }
    });
  }).catch(function (error) {
    console.log(error);
  });
};

(0, _nodeCleanup.default)(_whatsapp.cleanup);
var _default = app;
exports.default = _default;