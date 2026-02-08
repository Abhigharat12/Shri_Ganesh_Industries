-- Alter lead_history table to add new columns for enhanced Lead Interaction History / Follow-ups feature

ALTER TABLE lead_history
ADD COLUMN interaction_type ENUM('Call', 'WhatsApp', 'Email', 'Meeting', 'Visit') DEFAULT 'Call' AFTER last_interaction,
ADD COLUMN interaction_notes TEXT DEFAULT '' AFTER interaction_type,
ADD COLUMN follow_up_status ENUM('Pending', 'Done', 'Missed', 'Rescheduled') DEFAULT 'Pending' AFTER follow_up_date,
ADD COLUMN updated_by INT DEFAULT NULL AFTER follow_up_status,
ADD INDEX idx_updated_by (updated_by);

-- Add foreign key constraint separately
ALTER TABLE lead_history
ADD CONSTRAINT fk_updated_by FOREIGN KEY (updated_by) REFERENCES users(user_id) ON DELETE SET NULL;

-- Update existing records to have default values if needed
UPDATE lead_history SET interaction_type = 'Call' WHERE interaction_type IS NULL;
UPDATE lead_history SET interaction_notes = '' WHERE interaction_notes IS NULL;
UPDATE lead_history SET follow_up_status = 'Pending' WHERE follow_up_status IS NULL;
