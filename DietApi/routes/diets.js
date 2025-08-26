const express = require('express');
const router = express.Router();
const Diet = require('../models/Diet');
const { validateObjectId } = require('../middleware/validation');

// INDEX - Get all diets with filters
router.get('/', async (req, res) => {
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
            .sort({ createdAt: -1 })
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
router.get('/:id', validateObjectId, async (req, res) => {
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
router.post('/', async (req, res) => {
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
router.put('/:id', validateObjectId, async (req, res) => {
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
router.delete('/:id', validateObjectId, async (req, res) => {
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
router.get('/type/:type', async (req, res) => {
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
router.post('/:id/components', validateObjectId, async (req, res) => {
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
router.put('/:id/components/:componentId', validateObjectId, async (req, res) => {
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
router.delete('/:id/components/:componentId', validateObjectId, async (req, res) => {
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

module.exports = router;