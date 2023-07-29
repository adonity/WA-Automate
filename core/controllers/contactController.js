import { formatPhone, getProfile, getSession, isExists } from './../whatsapp.js'
import response from './../response.js'

const getPP = async (req, res) => {
    const session = getSession(res.locals.sessionId)
    const receiver = formatPhone(req.params.jid)

    try {
        const exists = await isExists(session, receiver)

        if (!exists) {
            return response(res, 400, false, 'The receiver number is not exists.')
        }

        const ppUrl = await getProfile(session, receiver)

        return response(res, 200, true, '', {
            image: ppUrl,
            number: receiver 
        })
    } catch {
        response(res, 500, false, 'Failed to get profile.')
    }
}

export { getPP }
