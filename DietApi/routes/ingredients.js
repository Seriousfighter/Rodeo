const express = require('express');
const router = express.Router();
const Ingredient = require('../models/Ingredient'); // ✅ Verificar que el archivo se llame Ingredient.js

// GET - All ingredients with filters
router.get('/', async (req, res) => {
    try {
        const {
            category,
            status,
            availability,
            name,
            page = 1,
            limit = 50
        } = req.query;

        const filter = {};
        if (category) filter.category = category;
        if (status) filter.status = status;
        if (availability) filter.availability = availability;
        if (name) filter.name = { $regex: name, $options: 'i' };

        const pageNum = parseInt(page);
        const limitNum = parseInt(limit);
        const skip = (pageNum - 1) * limitNum;

        const ingredients = await Ingredient.find(filter)
            .sort({ name: 1 })
            .skip(skip)
            .limit(limitNum);

        const total = await Ingredient.countDocuments(filter);

        res.json({
            success: true,
            data: ingredients,
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
            error: 'Error fetching ingredients',
            details: error.message
        });
    }
});

// GET - Specific ingredient
router.get('/:id', async (req, res) => {
    try {
        const ingredient = await Ingredient.findById(req.params.id);
        
        if (!ingredient) {
            return res.status(404).json({
                success: false,
                error: 'Ingredient not found'
            });
        }

        res.json({
            success: true,
            data: ingredient
        });
    } catch (error) {
        res.status(500).json({
            success: false,
            error: 'Error fetching ingredient',
            details: error.message
        });
    }
});

// POST - Create new ingredient
router.post('/', async (req, res) => {
    try {
        const {
            name,
            category,
            subcategory,
            description,
            nutritional_info,
            cost_per_kg,
            availability,
            supplier,
            storage_requirements,
            shelf_life_days,
            minimum_order,
            status,
            created_by,
            notes
        } = req.body;

        if (!name || !category) {
            return res.status(400).json({
                success: false,
                error: 'Name and category are required'
            });
        }

        const newIngredient = new Ingredient({
            name: name.trim(),
            category,
            subcategory: subcategory || '',
            description: description || '',
            nutritional_info: nutritional_info || {},
            cost_per_kg: cost_per_kg || 0,
            availability: availability || 'medium',
            supplier: supplier || {},
            storage_requirements: storage_requirements || '',
            shelf_life_days: shelf_life_days || 365,
            minimum_order: minimum_order || 0,
            status: status || 'active',
            created_by: created_by ? parseInt(created_by) : null,
            notes: notes || ''
        });

        const savedIngredient = await newIngredient.save();

        res.status(201).json({
            success: true,
            data: savedIngredient,
            message: 'Ingredient created successfully'
        });
    } catch (error) {
        if (error.code === 11000) {
            res.status(400).json({
                success: false,
                error: 'Ingredient name already exists'
            });
        } else {
            res.status(500).json({
                success: false,
                error: 'Error creating ingredient',
                details: error.message
            });
        }
    }
});

// PUT - Update ingredient
router.put('/:id', async (req, res) => {
    try {
        const updateData = { ...req.body };
        if (updateData.name) updateData.name = updateData.name.trim();

        const updatedIngredient = await Ingredient.findByIdAndUpdate(
            req.params.id,
            updateData,
            { new: true, runValidators: true }
        );

        if (!updatedIngredient) {
            return res.status(404).json({
                success: false,
                error: 'Ingredient not found'
            });
        }

        res.json({
            success: true,
            data: updatedIngredient,
            message: 'Ingredient updated successfully'
        });
    } catch (error) {
        if (error.code === 11000) {
            res.status(400).json({
                success: false,
                error: 'Ingredient name already exists'
            });
        } else {
            res.status(500).json({
                success: false,
                error: 'Error updating ingredient',
                details: error.message
            });
        }
    }
});

// DELETE - Delete ingredient
router.delete('/:id', async (req, res) => {
    try {
        const deletedIngredient = await Ingredient.findByIdAndDelete(req.params.id);

        if (!deletedIngredient) {
            return res.status(404).json({
                success: false,
                error: 'Ingredient not found'
            });
        }

        res.json({
            success: true,
            message: 'Ingredient deleted successfully',
            data: deletedIngredient
        });
    } catch (error) {
        res.status(500).json({
            success: false,
            error: 'Error deleting ingredient',
            details: error.message
        });
    }
});

// GET - Ingredients by category
router.get('/category/:category', async (req, res) => {
    try {
        const ingredients = await Ingredient.find({
            category: req.params.category,
            status: 'active'
        }).sort({ name: 1 });

        res.json({
            success: true,
            data: ingredients,
            total: ingredients.length
        });
    } catch (error) {
        res.status(500).json({
            success: false,
            error: 'Error fetching ingredients by category',
            details: error.message
        });
    }
});

// GET - Statistics endpoint
router.get('/stats', async (req, res) => {
    try {
        const stats = await Ingredient.aggregate([
            {
                $group: {
                    _id: '$category',
                    count: { $sum: 1 },
                    avgCost: { $avg: '$cost_per_kg' },
                    active: {
                        $sum: {
                            $cond: [{ $eq: ['$status', 'active'] }, 1, 0]
                        }
                    }
                }
            },
            { $sort: { count: -1 } }
        ]);

        const totalIngredients = await Ingredient.countDocuments();
        const activeIngredients = await Ingredient.countDocuments({ status: 'active' });

        res.json({
            success: true,
            data: {
                total_ingredients: totalIngredients,
                active_ingredients: activeIngredients,
                by_category: stats
            }
        });
    } catch (error) {
        res.status(500).json({
            success: false,
            error: 'Error fetching ingredient statistics',
            details: error.message
        });
    }
});

module.exports = router;