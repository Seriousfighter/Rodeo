const mongoose = require('mongoose');

// Component Schema (subdocument for diet components)
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

// Diet Schema (subdocument) - Copy of original diet for group customization
const dietSubdocumentSchema = new mongoose.Schema({
    name: {
        type: String,
        required: true,
        trim: true
    },
    description: {
        type: String,
        default: '',
        trim: true
    },
    components: [componentSchema], // Array of components
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
    // Reference to original diet (for tracking purposes)
    original_diet_id: {
        type: mongoose.Schema.Types.ObjectId,
        ref: 'Diet',
        required: true
    },
    // Condition when this diet should be used
    usage_condition: {
        type: String,
        enum: ['normal', 'rain', 'hot_weather', 'cold_weather', 'drought', 'winter', 'summer', 'emergency', 'custom'],
        default: 'normal'
    },
    condition_description: {
        type: String,
        default: '',
        trim: true
    },
    // Priority order (1 = highest priority)
    priority: {
        type: Number,
        default: 1,
        min: 1
    },
    // Customization tracking
    is_customized: {
        type: Boolean,
        default: false
    },
    customization_notes: {
        type: String,
        default: '',
        trim: true
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
    timestamps: true,
    _id: true // Each diet will have its own _id
});

// GroupDiet Schema - Main document for managing group diets
const groupDietSchema = new mongoose.Schema({
    // Group reference (from Laravel MySQL)
    group_id: {
        type: Number,
        required: true,
        index: true
    },
    // Group basic info (cached for performance)
    group_name: {
        type: String,
        required: true,
        trim: true
    },
    group_description: {
        type: String,
        default: '',
        trim: true
    },
    // Array of diets for this group
    diets: [dietSubdocumentSchema],
    // Currently active diet
    active_diet_id: {
        type: mongoose.Schema.Types.ObjectId,
        default: null
    },
    // Group diet management settings
    auto_switch_conditions: {
        type: Boolean,
        default: false // If true, automatically switch diets based on conditions
    },
    // Feeding schedule
    feeding_schedule: {
        times_per_day: {
            type: Number,
            default: 2,
            min: 1,
            max: 6
        },
        feeding_hours: [{
            hour: { type: String, match: /^([01]?[0-9]|2[0-3]):[0-5][0-9]$/ }, // Format: "HH:MM"
            portion_percentage: { type: Number, min: 0, max: 100 }
        }],
        total_daily_amount: {
            type: String,
            default: ''
        }
    },
    // Status
    status: {
        type: String,
        enum: ['active', 'inactive', 'archived'],
        default: 'active'
    },
    // Tracking
    created_by: {
        type: Number, // User ID from Laravel
        required: false
    },
    last_modified_by: {
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
groupDietSchema.index({ group_id: 1 });
groupDietSchema.index({ status: 1 });
groupDietSchema.index({ 'diets.usage_condition': 1 });
groupDietSchema.index({ 'diets.status': 1 });
groupDietSchema.index({ active_diet_id: 1 });
groupDietSchema.index({ createdAt: -1 });

// Instance methods
groupDietSchema.methods.getActiveDiet = function() {
    if (this.active_diet_id) {
        return this.diets.id(this.active_diet_id);
    }
    // Return the first active diet if no specific active diet is set
    return this.diets.find(diet => diet.status === 'active');
};

groupDietSchema.methods.getDietByCondition = function(condition) {
    return this.diets.find(diet => 
        diet.usage_condition === condition && diet.status === 'active'
    );
};

groupDietSchema.methods.setActiveDiet = function(dietId) {
    const diet = this.diets.id(dietId);
    if (diet && diet.status === 'active') {
        this.active_diet_id = dietId;
        return true;
    }
    return false;
};

// Static methods
groupDietSchema.statics.findByGroupId = function(groupId) {
    return this.findOne({ group_id: groupId, status: { $ne: 'archived' } });
};

groupDietSchema.statics.getActiveGroupDiets = function() {
    return this.find({ status: 'active' }).sort({ createdAt: -1 });
};

module.exports = mongoose.model('GroupDiet', groupDietSchema);