require('dotenv').config();
const express = require('express');
const mongoose = require('mongoose');
const cors = require('cors');

const app = express();

// Configuración de seguridad
const ALLOWED_ORIGINS = process.env.ALLOWED_ORIGINS?.split(',') || ['http://laravel:8000'];
const API_SECRET = process.env.API_SECRET;

// Middleware de seguridad
app.use(cors({
    origin: ALLOWED_ORIGINS,
    credentials: true,
    optionsSuccessStatus: 200
}));

// Middleware para verificar origen interno
app.use((req, res, next) => {
    const origin = req.get('Origin') || req.get('Referer');
    const userAgent = req.get('User-Agent');
    
    // Verificar que viene de Laravel o es una llamada interna
    if (origin && !ALLOWED_ORIGINS.some(allowedOrigin => origin.includes(allowedOrigin.replace('http://', '').replace('https://', '')))) {
        return res.status(403).json({
            success: false,
            error: 'Access denied - Invalid origin'
        });
    }
    
    next();
});

// Middleware para API Key (opcional pero recomendado)
app.use('/recordings', (req, res, next) => {
    const apiKey = req.headers['x-api-key'];
    
    if (API_SECRET && apiKey !== API_SECRET) {
        return res.status(401).json({
            success: false,
            error: 'Access denied - Invalid API key'
        });
    }
    
    next();
});

// Middleware
app.use(express.json({limit: '10mb'})); // Parse JSON bodies
app.use(express.urlencoded({ extended: true, limit: '10mb' })); // Parse URL-encoded bodies

// Deshabilitar header X-Powered-By
app.disable('x-powered-by');

// Middleware para logging de requests internos
app.use((req, res, next) => {
    console.log(`[${new Date().toISOString()}] ${req.method} ${req.path} - Origin: ${req.get('Origin') || 'No origin'}`);
    next();
});

const PORT = process.env.PORT || 3000;
const MONGODB_URI = process.env.MONGODB_URI || 'mongodb://localhost:27017/animal_recordings';

// Connect to MongoDB
mongoose.connect(MONGODB_URI, {
    useNewUrlParser: true,
    useUnifiedTopology: true,
})
.then(() => console.log('MongoDB connected successfully'))
.catch(err => console.error('MongoDB connection error:', err));

// Animal Recording Schema
const animalRecordingSchema = new mongoose.Schema({
    animal_id: {
        type: Number,
        required: true,
        index: true
    },
    rodeo_id: {
        type: Number,
        required: true,
        index: true
    },
    client_id: {
        type: Number,
        required: true,
        index: true
    },
    recording_type: {
        type: String,
        required: true,
        enum: ['insemination', 'vaccination', 'weight_check', 'ultrasound', 'natural_breeding', 'health_check', 'other']
    },
    recording_data: {
        type: mongoose.Schema.Types.Mixed,
        required: true
    },
    veterinarian_id: {
        type: Number,
        required: true
    },
    recording_date: {
        type: Date,
        required: true,
        default: Date.now
    },
    notes: {
        type: String,
        default: ''
    },
    status: {
        type: String,
        enum: ['pending', 'completed', 'cancelled'],
        default: 'completed'
    }
}, {
    timestamps: true
});

// Indexes for better performance
animalRecordingSchema.index({ animal_id: 1, recording_date: -1 });
animalRecordingSchema.index({ rodeo_id: 1, recording_date: -1 });
animalRecordingSchema.index({ client_id: 1, recording_date: -1 });

const AnimalRecording = mongoose.model('AnimalRecording', animalRecordingSchema);

// Root endpoint
app.get('/', (req, res) => {
    res.json({
        message: 'Animal Recording API',
        version: '1.0.0',
        endpoints: {
            'GET /recordings': 'Get all recordings (with filters)',
            'GET /recordings/:id': 'Get specific recording',
            'POST /recordings': 'Create new recording',
            'PUT /recordings/:id': 'Update recording',
            'DELETE /recordings/:id': 'Delete recording',
            'GET /recordings/animal/:animal_id': 'Get recordings by animal',
            'GET /recordings/rodeo/:rodeo_id': 'Get recordings by rodeo',
            'GET /recordings/client/:client_id': 'Get recordings by client'
        }
    });
});

// INDEX - Get all recordings with filters
app.get('/recordings', async (req, res) => {
    try {
        const {
            animal_id,
            rodeo_id,
            client_id,
            recording_type,
            status,
            start_date,
            end_date,
            page = 1,
            limit = 50
        } = req.query;

        // Build filter object
        const filter = {};
        if (animal_id) filter.animal_id = parseInt(animal_id);
        if (rodeo_id) filter.rodeo_id = parseInt(rodeo_id);
        if (client_id) filter.client_id = parseInt(client_id);
        if (recording_type) filter.recording_type = recording_type;
        if (status) filter.status = status;
        
        // Date range filter
        if (start_date || end_date) {
            filter.recording_date = {};
            if (start_date) filter.recording_date.$gte = new Date(start_date);
            if (end_date) filter.recording_date.$lte = new Date(end_date);
        }

        const pageNum = parseInt(page);
        const limitNum = parseInt(limit);
        const skip = (pageNum - 1) * limitNum;

        const recordings = await AnimalRecording.find(filter)
            .sort({ recording_date: -1 })
            .skip(skip)
            .limit(limitNum);

        const total = await AnimalRecording.countDocuments(filter);

        res.json({
            success: true,
            data: recordings,
            pagination: {
                current_page: pageNum,
                total_pages: Math.ceil(total / limitNum),
                total_records: total,
                per_page: limitNum
            }
        });
    } catch (error) {
        res.status(500).json({
            success: false,
            error: 'Error fetching recordings',
            details: error.message
        });
    }
});

// SHOW - Get specific recording
app.get('/recordings/:id', async (req, res) => {
    try {
        const recording = await AnimalRecording.findById(req.params.id);
        
        if (!recording) {
            return res.status(404).json({
                success: false,
                error: 'Recording not found'
            });
        }

        res.json({
            success: true,
            data: recording
        });
    } catch (error) {
        res.status(500).json({
            success: false,
            error: 'Error fetching recording',
            details: error.message
        });
    }
});

// STORE - Create new recording
app.post('/recordings', async (req, res) => {
    try {
        const {
            animal_id,
            rodeo_id,
            client_id,
            recording_type,
            recording_data,
            veterinarian_id,
            recording_date,
            notes,
            status
        } = req.body;

        // Validation
        if (!animal_id || !rodeo_id || !client_id || !recording_type || !recording_data || !veterinarian_id) {
            return res.status(400).json({
                success: false,
                error: 'Missing required fields',
                required: ['animal_id', 'rodeo_id', 'client_id', 'recording_type', 'recording_data', 'veterinarian_id']
            });
        }

        const newRecording = new AnimalRecording({
            animal_id: parseInt(animal_id),
            rodeo_id: parseInt(rodeo_id),
            client_id: parseInt(client_id),
            recording_type,
            recording_data,
            veterinarian_id: parseInt(veterinarian_id),
            recording_date: recording_date ? new Date(recording_date) : new Date(),
            notes: notes || '',
            status: status || 'completed'
        });

        const savedRecording = await newRecording.save();

        res.status(201).json({
            success: true,
            data: savedRecording,
            message: 'Recording created successfully'
        });
    } catch (error) {
        res.status(500).json({
            success: false,
            error: 'Error creating recording',
            details: error.message
        });
    }
});

// UPDATE - Update existing recording
app.put('/recordings/:id', async (req, res) => {
    try {
        const {
            animal_id,
            rodeo_id,
            client_id,
            recording_type,
            recording_data,
            veterinarian_id,
            recording_date,
            notes,
            status
        } = req.body;

        const updateData = {};
        if (animal_id) updateData.animal_id = parseInt(animal_id);
        if (rodeo_id) updateData.rodeo_id = parseInt(rodeo_id);
        if (client_id) updateData.client_id = parseInt(client_id);
        if (recording_type) updateData.recording_type = recording_type;
        if (recording_data) updateData.recording_data = recording_data;
        if (veterinarian_id) updateData.veterinarian_id = parseInt(veterinarian_id);
        if (recording_date) updateData.recording_date = new Date(recording_date);
        if (notes !== undefined) updateData.notes = notes;
        if (status) updateData.status = status;

        const updatedRecording = await AnimalRecording.findByIdAndUpdate(
            req.params.id,
            updateData,
            { new: true, runValidators: true }
        );

        if (!updatedRecording) {
            return res.status(404).json({
                success: false,
                error: 'Recording not found'
            });
        }

        res.json({
            success: true,
            data: updatedRecording,
            message: 'Recording updated successfully'
        });
    } catch (error) {
        res.status(500).json({
            success: false,
            error: 'Error updating recording',
            details: error.message
        });
    }
});

// DELETE - Delete recording
app.delete('/recordings/:id', async (req, res) => {
    try {
        const deletedRecording = await AnimalRecording.findByIdAndDelete(req.params.id);

        if (!deletedRecording) {
            return res.status(404).json({
                success: false,
                error: 'Recording not found'
            });
        }

        res.json({
            success: true,
            message: 'Recording deleted successfully',
            data: deletedRecording
        });
    } catch (error) {
        res.status(500).json({
            success: false,
            error: 'Error deleting recording',
            details: error.message
        });
    }
});

// Additional endpoints for specific queries

// Get recordings by animal
app.get('/recordings/animal/:animal_id', async (req, res) => {
    try {
        const recordings = await AnimalRecording.find({
            animal_id: parseInt(req.params.animal_id)
        }).sort({ recording_date: -1 });

        res.json({
            success: true,
            data: recordings,
            total: recordings.length
        });
    } catch (error) {
        res.status(500).json({
            success: false,
            error: 'Error fetching animal recordings',
            details: error.message
        });
    }
});

// Get recordings by rodeo
app.get('/recordings/rodeo/:rodeo_id', async (req, res) => {
    try {
        const recordings = await AnimalRecording.find({
            rodeo_id: parseInt(req.params.rodeo_id)
        }).sort({ recording_date: -1 });

        res.json({
            success: true,
            data: recordings,
            total: recordings.length
        });
    } catch (error) {
        res.status(500).json({
            success: false,
            error: 'Error fetching rodeo recordings',
            details: error.message
        });
    }
});

// Get recordings by client
app.get('/recordings/client/:client_id', async (req, res) => {
    try {
        const recordings = await AnimalRecording.find({
            client_id: parseInt(req.params.client_id)
        }).sort({ recording_date: -1 });

        res.json({
            success: true,
            data: recordings,
            total: recordings.length
        });
    } catch (error) {
        res.status(500).json({
            success: false,
            error: 'Error fetching client recordings',
            details: error.message
        });
    }
});

// Health check endpoint
app.get('/health', (req, res) => {
    res.json({
        status: 'OK',
        timestamp: new Date().toISOString(),
        mongodb: mongoose.connection.readyState === 1 ? 'Connected' : 'Disconnected'
    });
});

app.listen(PORT, () => {
    console.log(`Animal Recording API running on: http://localhost:${PORT}`);
});