const mongoose = require('mongoose');

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
dietSchema.index({ createdAt: -1 });

module.exports = mongoose.model('Diet', dietSchema);