import { existsSync, rmSync, readdir } from 'fs'
import P from 'pino'
import { join } from 'path'
import makeWASocket, {
    delay,
    isJidGroup,
    makeWALegacySocket,
    useSingleFileLegacyAuthState,
    useMultiFileAuthState,
    makeInMemoryStore,
    Browsers,
    DisconnectReason,
    downloadMediaMessage,
    fetchLatestBaileysVersion
} from '@adiwajshing/baileys'
import { toDataURL } from 'qrcode'
import __dirname from './dirname.js'
import response from './response.js'
import axios from 'axios'
import { writeFile } from 'fs/promises'
import { io } from './app.js'
import { SocksProxyAgent } from 'socks-proxy-agent';

const sessions = new Map()
const retries = new Map()

const sessionsDir = (sessionId = '') => {
    return join(__dirname, 'sessions', sessionId ? `${sessionId}.json` : '')
}

const isSessionExists = (sessionId) => {
    return sessions.has(sessionId)
}

const shouldReconnect = (sessionId) => {
    let maxRetries = parseInt(process.env.MAX_RETRIES ?? 0)
    let attempts = retries.get(sessionId) ?? 0

    maxRetries = maxRetries < 1 ? 1 : maxRetries

    if (attempts < maxRetries) {
        ++attempts

        console.log('Reconnecting...', { attempts, sessionId })
        retries.set(sessionId, attempts)

        return true
    }

    return false
}

const createSession = async (sessionId, isLegacy = false, res = null) => {
    const sessionFile = (isLegacy ? 'legacy_' : 'md_') + sessionId + (isLegacy ? '.json' : '')

    const logger = P({ level: 'warn' })
    const store = makeInMemoryStore({ logger })
    let _info = null
    let agent = null

    try {
        const info = await axios.get(process.env.SERVER_URL + '/api/proxy');
        _info =  info.data.length > 0 ?  info.data[getRandomInt(info.data.length - 1)]: false;

        agent = _info ? null : new SocksProxyAgent({
            hostname: _info.host,
            port: _info.port,
            userId: _info.username,
            password: _info.password,
        });
    } catch (error) {
        agent = null
    }

    console.log(_info)


    let state, saveState

    if (isLegacy) {
        ;({ state, saveState } = useSingleFileLegacyAuthState(sessionsDir(sessionFile)))
    } else {
        ;({ state, saveCreds: saveState } = await useMultiFileAuthState(sessionsDir(sessionFile)))
    }

    /**
     * @type {import('@adiwajshing/baileys').CommonSocketConfig}
     */
    const { version } = await fetchLatestBaileysVersion()
    const waConfig = agent != null ? {
        auth: state,
        printQRInTerminal: true,
        logger,
        browser: Browsers.macOS('Safari'),
        version,
        agent:agent
    } :  {
        auth: state,
        printQRInTerminal: true,
        logger,
        browser: Browsers.macOS('Safari'),
        version
    }


    /**
     * @type {import('@adiwajshing/baileys').AnyWASocket}
     */
    const wa = isLegacy ? makeWALegacySocket(waConfig) : makeWASocket.default(waConfig)

    if (!isLegacy) {
        store.readFromFile(sessionsDir(`${sessionId}_store.json`))
        store.bind(wa.ev)
    }

    sessions.set(sessionId, { ...wa, store, isLegacy })

    wa.ev.on('creds.update', saveState)

    wa.ev.on('chats.set', ({ chats }) => {
        if (isLegacy) {
            store.chats.insertIfAbsent(...chats)
        }
    })

    const deMin = 2000
    const deMax = 3500
    const rande = Math.floor(Math.random() * (deMax - deMin) + deMin)

    const caMin = 3000
    const caMax = 6000
    const ranca = Math.floor(Math.random() * (caMax - caMin) + caMin)

    wa.ev.on('messages.upsert', async (msg) => {
    const m = msg.messages[0]
    const from = m.key.remoteJid
    const notif = msg.type === 'notify'

    const mtr = [
        'iyaa',
        'Selamat tidur ya',
        'hehehe kocak',
        'mana bisa begitu',
        'lagi bosan cok',
        'kenapa tuh ?',
        'wkwkwkkw parah',
        'lu yang ngomongin gua di prosotan kan',
        'katanya kawan tapi bukan temen setia',
        'pemilu pilih nurhadi aldo',
        'bawel lu',
        'oyyy ada apa',
        'kalo disuruh melupakanmu kayaknya aku harus ke lurah dulu, bikin surat keterangan tidak mampu',
        'baca doa dulu ya',
        'mantul tul',
        'kepo bet dah',
        'Ciyus nih, miapah?',
        'Mana sini gua ketekin',
        'ngopi yok',
        'pijat kita !',
        'Jangan kau memberi tahu orang yang bertanya, karena mungkin ia sukanya tempe',
        'Siapa pun yang mengatakan uang tidak bisa membeli kebahagiaan tidak tahu harus berbelanja ke mana',
        'Mungkin kamu perlu makan makeup. Biar kamu bisa cantik dari dalam juga',
        'Jika kamu akan melakukan sesuatu malam ini dan kamu akan menyesal keesokan harinya, itulah tidur larut malam',
        'Jangan mandi, dingin. Kamu nggak akan kuat, cuci muka aja',
        'Setiap malamku: rebahan, bengong, overthinking, ga bisa tidur, dan akhirnya begadang yang sia-sia',
        'Berbengong-bengong dahulu, ber-overthinking-overthinking kemudian',
        'Selelah-lelahnya bekerja, lebih lelah lagi kalau nganggur.',
        'Jangan sampai wolesmu mengalahkan malasmu',
        'Lagi gabut nih, hang out yuk',
        'Jika seseorang melemparmu dengan batu, balaslah dengan bunga sekalian dengan potnya',
        'Jangan khawatir, hari baik akan datang. Hari itu disebut hari Jumat, Sabtu, dan Minggu',
        'Rumah tangga itu rumit. Kalau sederhana itu namanya rumah makan',
        'Hidup itu sederhana. Goreng, angkat, lalu tiriskan',
        'Di mana ada kelebihan, di situ ada kembalian',
        'Belajarlah dari tuyul, masih kecil tapi sudah pintar cari duit',
        'Tidur dulu, capek dari pagi pura-pura bahagia',
        'Kuy! Jalan-jalan bareng',
        'selo geng',
        'Mukaku ternyata lebih unyu dari umurku',
        'Lebih seram mana antara tercyduk polisi dengan terciduk istri?',
        'Terlalu sering memegang pensil alis dapat membuat mata menjadi buta, jika dicolok-colokkan ke mata',
        'Salah terus aku di matamu. Oke, mulai besok aku pindah ke hidungmu',
        'Sayang, bekerjalah seperti tuyul. Nggak kelihatan, nggak perlu pujian, nggak cari perhatian, tapi hasil jelas',
        'Tadinya mau mandi, tapi pas ngaca kok kelihatan masih cakep. Yaudah, nggak jadi mandi',
        'Hujan kalo sudah besar, cita-citanya pasti ingin menjadi deras',
        'Tak ada hari yang tak hadeuh',
        'Pengen mandi, tapi bosan dengan gerakannya',
        'Waktu tak dapat diputar, dijilat, apalagi dicelupin',
        'Lagi laper, tapi mager. Nitip dong',
        'Jangan ada nego diantara kita, karena rasaku sudah pas dan tidak bisa dinego',
        'Buat apa mencintaimu tanpa dicintai, mending cintai ususmu minum sirup tiap hari',
        'Tidak mengerjakan sesuatu itu sulit. Kamu tidak pernah tahu kapan akan selesai',
        'Hewan kurban aja dikorbanin lehernya ga sampai putus. Masa hubungan kita udah banyak berkorban akhirnya tetap putus',
        'Pria menikahi wanita dengan harapan mereka tidak akan pernah berubah. Wanita menikahi pria dengan harapan mereka akan berubah. Mereka berdua selalu kecewa',
        'Cintaku padamu itu bagaikan kamera. Yang lain blur, cuma fokus ke dirimu',
        'Yang namanya hidup itu memang pasti banyak cobaan. Kalau hidup itu banyak saweran, namanya malah dangdutan',
        'Ada peribahasa waktu adalah uang. Jika temanmu bilang enggak ada waktu saat diajak jalan, artinya dia lagi enggak punya uang',
        'Pengin ngecat gapura tapi takut di-read doang',
        'Kamu tahu merdeka itu apa? Mungkin liburan bersama kata pertama',

    ]
        const textRand = mtr[Math.floor(Math.random()*mtr.length+1)]

    if (notif) {
        await delay(ranca);
        await wa.readMessages([m.key])
        await delay(rande);
        await wa.sendPresenceUpdate('composing', from)
        await delay(1500);
        await wa.sendPresenceUpdate('paused', from)
        await delay(rande);
        await wa.sendPresenceUpdate('composing', from)
        await delay(500);
        await wa.sendMessage(from, {text : textRand})
    }
    })

    wa.ws.on('CB:call', async call => {
        if (call.content[0].tag == 'offer') {
            const callerJid = call.content[0].attrs['call-creator']
            const {  platform, notify, t } = call.attrs
            const caption = `Jangan ditelpon saya lagi berak`

            await delay(rande);
            await wa.sendPresenceUpdate('composing', callerJid)
            await delay(1500);
            await wa.sendPresenceUpdate('paused', callerJid)
            await delay(rande);
            await wa.sendPresenceUpdate('composing', callerJid)
            await delay(500);
            await wa.sendMessage(callerJid, { text: caption })
        }
    })

    wa.ev.on('call', async (node) => {
        const { from, id, status } = node[0]
        if (status == 'offer') {
            const stanza = {
                tag: 'call',
                attrs: {
                    from: wa.user.id,
                    to: from,
                    id: wa.generateMessageTag(),
                },
                content: [
                    {
                        tag: 'reject',
                        attrs: {
                            'call-id': id,
                            'call-creator': from,
                            count: '0',
                        },
                        content: undefined,
                    },
                ],
            }
            await wa.query(stanza)
        }
    })

    wa.ev.on('connection.update', async (update) => {
        const { connection, lastDisconnect } = update
        const statusCode = lastDisconnect?.error?.output?.statusCode

        // console.log(update)
        // console.log(sessionId)

        if (connection === 'open') {
            axios
                .post(process.env.SERVER_URL + '/api/device/status-update/' + sessionId, {
                    status: 'connected',
                    qrcode: ' ',
                    proxy: _info ? (_info.host + ":" + _info.port) : null
                })
                .then(function (response) {
                    // console.log(response)
                })
                .catch(function (error) {
                    // console.log(error)
                })
            retries.delete(sessionId)
        }

        if (connection === 'close') {
            if (statusCode === DisconnectReason.loggedOut || !shouldReconnect(sessionId)) {
                if (res && !res.headersSent) {
                    response(res, 500, false, 'Unable to create session.')
                }

                axios
                    .post(process.env.SERVER_URL + '/api/device/status-update/' + sessionId, {
                        status: 'disconnected',
                        qrcode: ' ',
                        proxy: null
                    })
                    .then(function (response) {
                        // console.log(response)
                    })
                    .catch(function (error) {
                        // console.log(error)
                    })
                return deleteSession(sessionId, isLegacy)
            }

            setTimeout(
                () => {
                    console.log('reconnecting')
                    createSession(sessionId, isLegacy, res)
                },
                statusCode === DisconnectReason.restartRequired ? 0 : parseInt(process.env.RECONNECT_INTERVAL ?? 0)
            )
        }

        if (update.qr) {
            if (res && !res.headersSent) {
                try {
                    const qr = await toDataURL(update.qr, {
                        type: 'image/webp',
                        rendererOpts: {
                            quality: 0.8,
                        },
                        width: 200,
                    })

                    axios
                        .post(process.env.SERVER_URL + '/api/device/status-update/' + sessionId, {
                            status: 'disconnected',
                            qrcode: qr,
                            proxy: _info ? (_info.host + ":" + _info.port) : null
                        })
                        .then(function (response) {
                            // console.log(response)
                        })
                        .catch(function (error) {
                            // console.log(error)
                        })
                    response(res, 200, true, 'QR code received, please scan the QR code.', { qr })
                } catch {
                    response(res, 500, false, 'Unable to create QR code.')
                }

                return
            }

            try {
                await wa.logout()
            } catch {
            } finally {
                deleteSession(sessionId, isLegacy)
            }
        }
    })
}

const getSession = (sessionId) => {
    return sessions.get(sessionId) ?? null
}

const deleteSession = (sessionId, isLegacy = false) => {
    const sessionFile = (isLegacy ? 'legacy_' : 'md_') + sessionId + (isLegacy ? '.json' : '')
    const storeFile = `${sessionId}_store.json`
    const rmOptions = { force: true, recursive: true }

    rmSync(sessionsDir(sessionFile), rmOptions)
    rmSync(sessionsDir(storeFile), rmOptions)

    sessions.delete(sessionId)
    retries.delete(sessionId)
}

const getChatList = (sessionId, isGroup = false) => {
    const filter = isGroup ? '@g.us' : '@s.whatsapp.net'

    return getSession(sessionId).store.chats.filter((chat) => {
        return chat.id.endsWith(filter)
    })
}

const isExists = async (session, jid, isGroup = false) => {
    try {
        let result

        if (isGroup) {
            result = await session.groupMetadata(jid)

            return Boolean(result.id)
        }

        if (session.isLegacy) {
            result = await session.onWhatsApp(jid)
        } else {
            ;[result] = await session.onWhatsApp(jid)
        }

        return result.exists
    } catch {
        return false
    }
}

const sendMessage = async (session, receiver, message) => {
    try {
        return session.sendMessage(receiver, message)
    } catch {
        return Promise.reject(null)
    }
}

const getProfile = async (session, receiver) => {
    try {
        return await session.profilePictureUrl(receiver)
    } catch {
        return Promise.reject(null)
    }
}

const formatPhone = (phone) => {
    if (phone.endsWith('@s.whatsapp.net')) {
        return phone
    }

    let formatted = phone.replace(/\D/g, '')

    return (formatted += '@s.whatsapp.net')
}

const formatGroup = (group) => {
    if (group.endsWith('@g.us')) {
        return group
    }

    let formatted = group.replace(/[^\d-]/g, '')

    return (formatted += '@g.us')
}

const cleanup = () => {
    console.log('Running cleanup before exit.')

    sessions.forEach((session, sessionId) => {
        if (!session.isLegacy) {
            session.store.writeToFile(sessionsDir(`${sessionId}_store.json`))
        }
    })
}

const init = () => {
    readdir(sessionsDir(), (err, files) => {
        if (err) {
            throw err
        }

        for (const file of files) {
            if ((!file.startsWith('md_') && !file.startsWith('legacy_')) || file.endsWith('_store')) {
                continue
            }

            const filename = file.replace('.json', '')
            const isLegacy = filename.split('_', 1)[0] !== 'md'
            const sessionId = filename.substring(isLegacy ? 7 : 3)

            createSession(sessionId, isLegacy)
        }
    })
}

function getRandomInt(max) {
    return Math.floor(Math.random() * max);
}

export {
    isSessionExists,
    createSession,
    getSession,
    deleteSession,
    getChatList,
    isExists,
    sendMessage,
    formatPhone,
    formatGroup,
    getProfile,
    cleanup,
    init,
}
