const express = require('express');
const router = express.Router();
const GroupDiet = require('../models/GroupDiet');
const Diet = require('../models/Diet');
const { validateObjectId } = require('../middleware/validation');

// GET - All group diets with rodeo_id filter
router.get('/', async (req, res) => {
    try {
        const {
            group_id,
            rodeo_id,
            status,
            page = 1,
            limit = 50
        } = req.query;

        const filter = {};
        if (group_id) filter.group_id = parseInt(group_id);
        if (rodeo_id) filter.rodeo_id = parseInt(rodeo_id);
        if (status) filter.status = status;

        const pageNum = parseInt(page);
        const limitNum = parseInt(limit);
        const skip = (pageNum - 1) * limitNum;

        const groupDiets = await GroupDiet.find(filter)
            .sort({ createdAt: -1 })
            .skip(skip)
            .limit(limitNum);

        const total = await GroupDiet.countDocuments(filter);

        res.json({
            success: true,
            data: groupDiets,
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
            error: 'Error fetching group diets',
            details: error.message
        });
    }
});
// GET - Group diet by group_id
router.get('/group/:groupId', async (req, res) => {
    try {
        const groupId = parseInt(req.params.groupId);
        
        const groupDiet = await GroupDiet.findOne({ group_id: groupId });
        
        if (!groupDiet) {
            return res.status(404).json({
                success: false,
                error: 'Group diet not found',
                data: null
            });
        }

        res.json({
            success: true,
            data: groupDiet
        });
    } catch (error) {
        console.error('Error fetching group diet:', error);
        res.status(500).json({
            success: false,
            error: 'Error fetching group diet',
            details: error.message
        });
    }
});

// GET - All group diets by rodeo_id
router.get('/rodeo/:rodeoId', async (req, res) => {
    try {
        const rodeoId = parseInt(req.params.rodeoId);
        const groupDiets = await GroupDiet.findByRodeoId(rodeoId);
        
        res.json({
            success: true,
            data: groupDiets,
            total: groupDiets.length
        });
    } catch (error) {
        res.status(500).json({
            success: false,
            error: 'Error fetching rodeo group diets',
            details: error.message
        });
    }
});

// POST - Create new group diet (actualizado para requerir rodeo_id)
router.post('/', async (req, res) => {
    try {
        const {
            group_id,
            rodeo_id,
            group_name,
            group_description,
            diet_id,
            usage_condition,
            condition_description,
            priority,
            created_by,
            notes
        } = req.body;

        // Validation - ahora rodeo_id es requerido
        if (!group_id || !rodeo_id || !group_name || !diet_id) {
            return res.status(400).json({
                success: false,
                error: 'group_id, rodeo_id, group_name, and diet_id are required'
            });
        }

        // Get the original diet to copy
        const originalDiet = await Diet.findById(diet_id);
        if (!originalDiet) {
            return res.status(404).json({
                success: false,
                error: 'Original diet not found'
            });
        }

        // Check if group diet already exists
        let groupDiet = await GroupDiet.findByGroupId(group_id);
        
        if (!groupDiet) {
            // Create new group diet document
            groupDiet = new GroupDiet({
                group_id,
                rodeo_id: parseInt(rodeo_id),
                group_name: group_name.trim(),
                group_description: group_description || '',
                diets: [],
                created_by: created_by ? parseInt(created_by) : null,
                notes: notes || ''
            });
        }

        // Create diet copy
        const dietCopy = {
            name: originalDiet.name,
            description: originalDiet.description,
            components: originalDiet.components.map(comp => ({
                name: comp.name,
                amount: comp.amount,
                unit: comp.unit,
                notes: comp.notes
            })),
            total_weight: originalDiet.total_weight,
            diet_type: originalDiet.diet_type,
            status: 'active',
            original_diet_id: originalDiet._id,
            usage_condition: usage_condition || 'normal',
            condition_description: condition_description || '',
            priority: priority || (groupDiet.diets.length + 1),
            is_customized: false,
            customization_notes: '',
            created_by: created_by ? parseInt(created_by) : null,
            notes: notes || ''
        };

        // Add diet to group
        groupDiet.diets.push(dietCopy);

        // Set as active if it's the first diet or priority 1
        if (groupDiet.diets.length === 1 || priority === 1) {
            groupDiet.active_diet_id = groupDiet.diets[groupDiet.diets.length - 1]._id;
        }

        const savedGroupDiet = await groupDiet.save();

        res.status(201).json({
            success: true,
            data: savedGroupDiet,
            message: 'Diet assigned to group successfully'
        });
    } catch (error) {
        res.status(500).json({
            success: false,
            error: 'Error creating group diet',
            details: error.message
        });
    }
});

// PUT - Update specific diet in group
router.put('/:groupId/diets/:dietId', async (req, res) => {
    try {
        const groupId = parseInt(req.params.groupId);
        const dietId = req.params.dietId;
        
        const groupDiet = await GroupDiet.findByGroupId(groupId);
        if (!groupDiet) {
            return res.status(404).json({
                success: false,
                error: 'Group diet not found'
            });
        }

        const diet = groupDiet.diets.id(dietId);
        if (!diet) {
            return res.status(404).json({
                success: false,
                error: 'Diet not found in group'
            });
        }

        // Update diet fields
        const {
            name,
            description,
            components,
            total_weight,
            diet_type,
            status,
            usage_condition,
            condition_description,
            priority,
            customization_notes,
            notes
        } = req.body;

        if (name) diet.name = name.trim();
        if (description !== undefined) diet.description = description;
        if (components) {
            diet.components = components;
            diet.is_customized = true;
        }
        if (total_weight !== undefined) diet.total_weight = total_weight;
        if (diet_type) diet.diet_type = diet_type;
        if (status) diet.status = status;
        if (usage_condition) diet.usage_condition = usage_condition;
        if (condition_description !== undefined) diet.condition_description = condition_description;
        if (priority) diet.priority = priority;
        if (customization_notes !== undefined) {
            diet.customization_notes = customization_notes;
            diet.is_customized = true;
        }
        if (notes !== undefined) diet.notes = notes;

        // Mark as customized if components or other key fields changed
        if (components || name || total_weight) {
            diet.is_customized = true;
        }

        const savedGroupDiet = await groupDiet.save();

        res.json({
            success: true,
            data: savedGroupDiet,
            message: 'Group diet updated successfully'
        });
    } catch (error) {
        res.status(500).json({
            success: false,
            error: 'Error updating group diet',
            details: error.message
        });
    }
});

// POST - Set active diet for group
router.post('/:groupId/activate/:dietId', async (req, res) => {
    try {
        const groupId = parseInt(req.params.groupId);
        const dietId = req.params.dietId;
        
        const groupDiet = await GroupDiet.findByGroupId(groupId);
        if (!groupDiet) {
            return res.status(404).json({
                success: false,
                error: 'Group diet not found'
            });
        }

        const success = groupDiet.setActiveDiet(dietId);
        if (!success) {
            return res.status(400).json({
                success: false,
                error: 'Diet not found or not active'
            });
        }

        await groupDiet.save();

        res.json({
            success: true,
            data: groupDiet,
            message: 'Active diet changed successfully'
        });
    } catch (error) {
        res.status(500).json({
            success: false,
            error: 'Error setting active diet',
            details: error.message
        });
    }
});

// DELETE - Remove diet from group
router.delete('/:groupId/diets/:dietId', async (req, res) => {
    try {
        const groupId = parseInt(req.params.groupId);
        const dietId = req.params.dietId;
        
        const groupDiet = await GroupDiet.findByGroupId(groupId);
        if (!groupDiet) {
            return res.status(404).json({
                success: false,
                error: 'Group diet not found'
            });
        }

        const diet = groupDiet.diets.id(dietId);
        if (!diet) {
            return res.status(404).json({
                success: false,
                error: 'Diet not found in group'
            });
        }

        // Don't allow deletion if it's the only diet
        /*if (groupDiet.diets.length === 1) {
            return res.status(400).json({
                success: false,
                error: 'Cannot delete the only diet in group'
            });
        }*/

        // If deleting active diet, set another as active
        if (groupDiet.active_diet_id && groupDiet.active_diet_id.toString() === dietId) {
            const otherDiet = groupDiet.diets.find(d => 
                d._id.toString() !== dietId && d.status === 'active'
            );
            groupDiet.active_diet_id = otherDiet ? otherDiet._id : null;
        }

        diet.deleteOne();
        const savedGroupDiet = await groupDiet.save();

        res.json({
            success: true,
            data: savedGroupDiet,
            message: 'Diet removed from group successfully'
        });
    } catch (error) {
        res.status(500).json({
            success: false,
            error: 'Error removing diet from group',
            details: error.message
        });
    }
});

// GET - Get active diet for group
router.get('/:groupId/active', async (req, res) => {
    try {
        const groupId = parseInt(req.params.groupId);
        const groupDiet = await GroupDiet.findByGroupId(groupId);
        
        if (!groupDiet) {
            return res.status(404).json({
                success: false,
                error: 'Group diet not found'
            });
        }

        const activeDiet = groupDiet.getActiveDiet();
        if (!activeDiet) {
            return res.status(404).json({
                success: false,
                error: 'No active diet found for group'
            });
        }

        res.json({
            success: true,
            data: activeDiet
        });
    } catch (error) {
        res.status(500).json({
            success: false,
            error: 'Error fetching active diet',
            details: error.message
        });
    }
});

module.exports = router;