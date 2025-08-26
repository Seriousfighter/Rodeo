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

// Middleware para API Key para recursos protegidos
app.use(['/diets', '/ingredients', '/group-diets'], (req, res, next) => {
    const apiKey = req.headers['x-api-key'];
    
    if (API_SECRET && apiKey !== API_SECRET) {
        return res.status(401).json({
            success: false,
            error: 'Access denied - Invalid API key'
        });
    }
    
    next();
});

// Middleware general
app.use(express.json({limit: '10mb'}));
app.use(express.urlencoded({ extended: true, limit: '10mb' }));
app.disable('x-powered-by');

// Middleware para logging
app.use((req, res, next) => {
    console.log(`[${new Date().toISOString()}] ${req.method} ${req.path} - Origin: ${req.get('Origin') || 'No origin'}`);
    next();
});

const PORT = process.env.PORT || 3001;
const MONGODB_URI = process.env.MONGODB_URI || 'mongodb://localhost:27017/diet';

// Connect to MongoDB
mongoose.connect(MONGODB_URI, {
    useNewUrlParser: true,
    useUnifiedTopology: true,
})
.then(() => console.log('MongoDB connected successfully'))
.catch(err => console.error('MongoDB connection error:', err));

// Import routes
const dietRoutes = require('./routes/diets');
const ingredientRoutes = require('./routes/ingredients');
const groupDietRoutes = require('./routes/groupDiet'); // ✅ Verificar esta línea

// Root endpoint
app.get('/', (req, res) => {
    res.json({
        message: 'Diet Management API',
        version: '2.0.0',
        status: 'Active',
        description: 'API para gestión de dietas y ingredientes bovinos',
        resources: {
            'diets': {
                description: 'Dietas completas para animales',
                count_endpoint: '/diets?limit=1',
                examples: ['maintenance', 'growth', 'reproduction', 'lactation', 'custom']
            },
            'ingredients': {
                description: 'Base de ingredientes disponibles',
                count_endpoint: '/ingredients?limit=1',
                categories: ['forages', 'concentrates', 'minerals', 'vitamins', 'additives', 'byproducts'],
                stats_endpoint: '/ingredients/stats'
            }
        },
        endpoints: {
            'Diets': {
                'GET /diets': 'Get all diets (supports: diet_type, status, name, page, limit)',
                'GET /diets/:id': 'Get specific diet with components',
                'POST /diets': 'Create new diet (requires: name, components[])',
                'PUT /diets/:id': 'Update diet (partial updates supported)',
                'DELETE /diets/:id': 'Delete diet',
                'GET /diets/type/:type': 'Get active diets by type',
                'POST /diets/:id/components': 'Add component to existing diet',
                'PUT /diets/:id/components/:componentId': 'Update specific component',
                'DELETE /diets/:id/components/:componentId': 'Remove component from diet'
            },
            'Ingredients': {
                'GET /ingredients': 'Get all ingredients (supports: category, status, availability, name, page, limit)',
                'GET /ingredients/:id': 'Get specific ingredient with nutritional info',
                'POST /ingredients': 'Create new ingredient (requires: name, category)',
                'PUT /ingredients/:id': 'Update ingredient (partial updates supported)',
                'DELETE /ingredients/:id': 'Delete ingredient',
                'GET /ingredients/category/:category': 'Get active ingredients by category',
                'GET /ingredients/stats': 'Get ingredient statistics and counts'
            }
        },
        security: {
            'api_key': 'Required via X-API-Key header for protected routes',
            'cors': 'Configured for Laravel application',
            'protected_routes': ['/diets', '/ingredients']
        },
        database: {
            'type': 'MongoDB',
            'collections': ['diets', 'ingredients'],
            'connection': mongoose.connection.readyState === 1 ? 'Connected' : 'Disconnected'
        }
    });
});

// Use routes ANTES del middleware de API key
app.use('/diets', dietRoutes);
app.use('/ingredients', ingredientRoutes);
app.use('/group-diets', groupDietRoutes); // ✅ Verificar esta línea

// Health check endpoint
app.get('/health', (req, res) => {
    res.json({
        status: 'OK',
        timestamp: new Date().toISOString(),
        mongodb: mongoose.connection.readyState === 1 ? 'Connected' : 'Disconnected',
        collections: {
            diets: 'Active',
            ingredients: 'Active',
            groupDiets: 'Active'
        }
    });
});

app.listen(PORT, () => {
    console.log(`Diet Management API running on: http://localhost:${PORT}`);
    console.log('Available resources: diets, ingredients');
});