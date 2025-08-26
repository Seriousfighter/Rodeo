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
app.use('/diets', (req, res, next) => {
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

const PORT = process.env.PORT || 3001; // Different port from RecordingAPI
const MONGODB_URI = process.env.MONGODB_URI || 'mongodb://localhost:27017/diet';

// Connect to MongoDB
mongoose.connect(MONGODB_URI, {
    useNewUrlParser: true,
    useUnifiedTopology: true,
})
.then(() => console.log('MongoDB connected successfully'))
.catch(err => console.error('MongoDB connection error:', err));

// Component Schema (subdocument)
const componentSchema = new mongoose.Schema({
    name: {
        type: String,
        required: true,
        trim: true
    },
    amount: {
        type: String, // Using String to support different units like "10kg", "500gr", "2L"
        required: true,
        trim: true
    },
    unit: {
        type: String,
        enum: ['kg', 'gr', 'L', 'ml', 'units', 'other'],
        default: 'kg'
    },
    notes: {
        type: String,
        default: '',
        trim: true
    }
}, {
    _id: true // Each component will have its own _id
});

// Diet Schema
const dietSchema = new mongoose.Schema({
    name: {
        type: String,
        required: true,
        trim: true,
        unique: true
    },
    description: {
        type: String,
        default: '',
        trim: true
    },
    components: [componentSchema], // Array of components subdocuments
    total_weight: {
        type: String,
        default: ''
    },
    diet_type: {
        type: String,
        enum: ['maintenance', 'growth', 'reproduction', 'lactation', 'custom'],
        default: 'custom'
    },
    status: {
        type: String,
        enum: ['active', 'inactive', 'draft'],
        default: 'active'
    },
    created_by: {
        type: Number, // User ID from Laravel
        required: false
    },
    notes: {
        type: String,
        default: '',
        trim: true
    }
}, {
    timestamps: true
});

// Indexes for better performance
dietSchema.index({ name: 1 });
dietSchema.index({ diet_type: 1 });
dietSchema.index({ status: 1 });
dietSchema.index({ created_at: -1 });

const Diet = mongoose.model('Diet', dietSchema);

// Root endpoint
app.get('/', (req, res) => {
    res.json({
        message: 'Diet Management API',
        version: '1.0.0',
        endpoints: {
            'GET /diets': 'Get all diets (with filters)',
            'GET /diets/:id': 'Get specific diet',
            'POST /diets': 'Create new diet',
            'PUT /diets/:id': 'Update diet',
            'DELETE /diets/:id': 'Delete diet',
            'GET /diets/type/:type': 'Get diets by type',
            'POST /diets/:id/components': 'Add component to diet',
            'PUT /diets/:id/components/:componentId': 'Update diet component',
            'DELETE /diets/:id/components/:componentId': 'Remove component from diet'
        }
    });
});

// INDEX - Get all diets with filters
app.get('/diets', async (req, res) => {
    try {
        const {
            diet_type,
            status,
            name,
            page = 1,
            limit = 50
        } = req.query;

        // Build filter object
        const filter = {};
        if (diet_type) filter.diet_type = diet_type;
        if (status) filter.status = status;
        if (name) filter.name = { $regex: name, $options: 'i' }; // Case-insensitive search

        const pageNum = parseInt(page);
        const limitNum = parseInt(limit);
        const skip = (pageNum - 1) * limitNum;

        const diets = await Diet.find(filter)
            .sort({ created_at: -1 })
            .skip(skip)
            .limit(limitNum);

        const total = await Diet.countDocuments(filter);

        res.json({
            success: true,
            data: diets,
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
            error: 'Error fetching diets',
            details: error.message
        });
    }
});

// SHOW - Get specific diet
app.get('/diets/:id', async (req, res) => {
    try {
        const diet = await Diet.findById(req.params.id);
        
        if (!diet) {
            return res.status(404).json({
                success: false,
                error: 'Diet not found'
            });
        }

        res.json({
            success: true,
            data: diet
        });
    } catch (error) {
        res.status(500).json({
            success: false,
            error: 'Error fetching diet',
            details: error.message
        });
    }
});

// STORE - Create new diet
app.post('/diets', async (req, res) => {
    try {
        const {
            name,
            description,
            components,
            total_weight,
            diet_type,
            status,
            created_by,
            notes
        } = req.body;

        // Validation
        if (!name) {
            return res.status(400).json({
                success: false,
                error: 'Diet name is required'
            });
        }

        if (!components || !Array.isArray(components) || components.length === 0) {
            return res.status(400).json({
                success: false,
                error: 'At least one component is required'
            });
        }

        // Validate components
        for (let component of components) {
            if (!component.name || !component.amount) {
                return res.status(400).json({
                    success: false,
                    error: 'Each component must have name and amount'
                });
            }
        }

        const newDiet = new Diet({
            name: name.trim(),
            description: description || '',
            components,
            total_weight: total_weight || '',
            diet_type: diet_type || 'custom',
            status: status || 'active',
            created_by: created_by ? parseInt(created_by) : null,
            notes: notes || ''
        });

        const savedDiet = await newDiet.save();

        res.status(201).json({
            success: true,
            data: savedDiet,
            message: 'Diet created successfully'
        });
    } catch (error) {
        if (error.code === 11000) { // Duplicate key error
            res.status(400).json({
                success: false,
                error: 'Diet name already exists'
            });
        } else {
            res.status(500).json({
                success: false,
                error: 'Error creating diet',
                details: error.message
            });
        }
    }
});

// UPDATE - Update existing diet
app.put('/diets/:id', async (req, res) => {
    try {
        const {
            name,
            description,
            components,
            total_weight,
            diet_type,
            status,
            notes
        } = req.body;

        const updateData = {};
        if (name) updateData.name = name.trim();
        if (description !== undefined) updateData.description = description;
        if (components) updateData.components = components;
        if (total_weight !== undefined) updateData.total_weight = total_weight;
        if (diet_type) updateData.diet_type = diet_type;
        if (status) updateData.status = status;
        if (notes !== undefined) updateData.notes = notes;

        const updatedDiet = await Diet.findByIdAndUpdate(
            req.params.id,
            updateData,
            { new: true, runValidators: true }
        );

        if (!updatedDiet) {
            return res.status(404).json({
                success: false,
                error: 'Diet not found'
            });
        }

        res.json({
            success: true,
            data: updatedDiet,
            message: 'Diet updated successfully'
        });
    } catch (error) {
        if (error.code === 11000) { // Duplicate key error
            res.status(400).json({
                success: false,
                error: 'Diet name already exists'
            });
        } else {
            res.status(500).json({
                success: false,
                error: 'Error updating diet',
                details: error.message
            });
        }
    }
});

// DELETE - Delete diet
app.delete('/diets/:id', async (req, res) => {
    try {
        const deletedDiet = await Diet.findByIdAndDelete(req.params.id);

        if (!deletedDiet) {
            return res.status(404).json({
                success: false,
                error: 'Diet not found'
            });
        }

        res.json({
            success: true,
            message: 'Diet deleted successfully',
            data: deletedDiet
        });
    } catch (error) {
        res.status(500).json({
            success: false,
            error: 'Error deleting diet',
            details: error.message
        });
    }
});

// Get diets by type
app.get('/diets/type/:type', async (req, res) => {
    try {
        const diets = await Diet.find({
            diet_type: req.params.type,
            status: 'active'
        }).sort({ name: 1 });

        res.json({
            success: true,
            data: diets,
            total: diets.length
        });
    } catch (error) {
        res.status(500).json({
            success: false,
            error: 'Error fetching diets by type',
            details: error.message
        });
    }
});

// Add component to existing diet
app.post('/diets/:id/components', async (req, res) => {
    try {
        const { name, amount, unit, notes } = req.body;

        if (!name || !amount) {
            return res.status(400).json({
                success: false,
                error: 'Component name and amount are required'
            });
        }

        const diet = await Diet.findById(req.params.id);
        
        if (!diet) {
            return res.status(404).json({
                success: false,
                error: 'Diet not found'
            });
        }

        diet.components.push({
            name: name.trim(),
            amount: amount.trim(),
            unit: unit || 'kg',
            notes: notes || ''
        });

        const updatedDiet = await diet.save();

        res.json({
            success: true,
            data: updatedDiet,
            message: 'Component added successfully'
        });
    } catch (error) {
        res.status(500).json({
            success: false,
            error: 'Error adding component',
            details: error.message
        });
    }
});

// Update specific component in diet
app.put('/diets/:id/components/:componentId', async (req, res) => {
    try {
        const { name, amount, unit, notes } = req.body;

        const diet = await Diet.findById(req.params.id);
        
        if (!diet) {
            return res.status(404).json({
                success: false,
                error: 'Diet not found'
            });
        }

        const component = diet.components.id(req.params.componentId);
        
        if (!component) {
            return res.status(404).json({
                success: false,
                error: 'Component not found'
            });
        }

        if (name) component.name = name.trim();
        if (amount) component.amount = amount.trim();
        if (unit) component.unit = unit;
        if (notes !== undefined) component.notes = notes;

        const updatedDiet = await diet.save();

        res.json({
            success: true,
            data: updatedDiet,
            message: 'Component updated successfully'
        });
    } catch (error) {
        res.status(500).json({
            success: false,
            error: 'Error updating component',
            details: error.message
        });
    }
});

// Remove component from diet
app.delete('/diets/:id/components/:componentId', async (req, res) => {
    try {
        const diet = await Diet.findById(req.params.id);
        
        if (!diet) {
            return res.status(404).json({
                success: false,
                error: 'Diet not found'
            });
        }

        const component = diet.components.id(req.params.componentId);
        
        if (!component) {
            return res.status(404).json({
                success: false,
                error: 'Component not found'
            });
        }

        component.deleteOne();
        const updatedDiet = await diet.save();

        res.json({
            success: true,
            data: updatedDiet,
            message: 'Component removed successfully'
        });
    } catch (error) {
        res.status(500).json({
            success: false,
            error: 'Error removing component',
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
    console.log(`Diet Management API running on: http://localhost:${PORT}`);
});