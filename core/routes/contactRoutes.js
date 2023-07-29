import { Router } from 'express'
import { body, query } from 'express-validator'
import requestValidator from './../middlewares/requestValidator.js'
import sessionValidator from './../middlewares/sessionValidator.js'
import * as controller from './../controllers/contactController.js'

const router = Router()

router.get('/get/:jid', query('id').notEmpty(), requestValidator, sessionValidator, controller.getPP)

export default router
