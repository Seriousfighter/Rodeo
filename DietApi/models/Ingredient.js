const mongoose = require('mongoose');

const nutritionalInfoSchema = new mongoose.Schema({
    protein: { type: Number, default: 0 }, // Proteína bruta %
    fat: { type: Number, default: 0 }, // Grasa %
    fiber: { type: Number, default: 0 }, // Fibra %
    ash: { type: Number, default: 0 }, // Cenizas %
    moisture: { type: Number, default: 0 }, // Humedad %
    energy: { type: Number, default: 0 }, // Energía metabolizable Mcal/kg
    calcium: { type: Number, default: 0 }, // Calcio %
    phosphorus: { type: Number, default: 0 }, // Fósforo %
    notes: { type: String, default: '' }
});

const ingredientSchema = new mongoose.Schema({
    name: {
        type: String,
        required: true,
        unique: true,
        trim: true
    },
    category: {
        type: String,
        enum: ['forages', 'concentrates', 'minerals', 'vitamins', 'additives', 'byproducts'],
        required: true
    },
    subcategory: {
        type: String,
        default: ''
    },
    description: {
        type: String,
        default: ''
    },
    nutritional_info: nutritionalInfoSchema,
    cost_per_kg: {
        type: Number,
        default: 0
    },
    availability: {
        type: String,
        enum: ['high', 'medium', 'low', 'seasonal'],
        default: 'medium'
    },
    supplier: {
        name: { type: String, default: '' },
        contact: { type: String, default: '' },
        location: { type: String, default: '' }
    },
    storage_requirements: {
        type: String,
        default: ''
    },
    shelf_life_days: {
        type: Number,
        default: 365
    },
    minimum_order: {
        type: Number,
        default: 0
    },
    status: {
        type: String,
        enum: ['active', 'inactive', 'discontinued'],
        default: 'active'
    },
    created_by: {
        type: Number,
        required: false
    },
    notes: {
        type: String,
        default: ''
    }
}, {
    timestamps: true
});

// Índices
ingredientSchema.index({ name: 1 });
ingredientSchema.index({ category: 1 });
ingredientSchema.index({ status: 1 });
ingredientSchema.index({ availability: 1 });

module.exports = mongoose.model('Ingredient', ingredientSchema);