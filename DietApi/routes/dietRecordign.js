const express = require('express');
const router = express.Router();
const Diet = require('../models/DietRecording');
const { validateObjectId } = require('../middleware/validation');