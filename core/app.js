import 'dotenv/config'
import express from 'express'
import nodeCleanup from 'node-cleanup'
import routes from './routes.js'
import { init, cleanup, getSession, isExists, formatPhone } from './whatsapp.js'
import { Server } from 'socket.io'
import axios from 'axios'
import { delay, fetchLatestBaileysVersion } from '@adiwajshing/baileys'
import path from 'path'
import { fileURLToPath } from 'url'

const __filename = fileURLToPath(import.meta.url)
const __dirname = path.dirname(__filename)

const app = express()
const host = process.env.HOST ?? '0.0.0.0'
const port = parseInt(process.env.PORT ?? 8000)

app.use(express.urlencoded({ extended: true }))
app.use(express.json())
app.use('/media', express.static(__dirname + '/media'))
app.use('/', routes)

const { version, isLatest } = await fetchLatestBaileysVersion()
console.log(`using WA web v${version.join('.')}, isLatest: ${isLatest}`)

export const io = new Server(
    app.listen(port, host, () => {
        init()
        console.log(`Server is listening on http://${host}:${port}`)
    }),
    {
        cors: {
            origin: '*',
        },
    }
)

nodeCleanup(cleanup)
