-- Set users as offline if their last_seen is older than 2 minutes
-- This can be run periodically to clean up stale online statuses

UPDATE Users 
SET is_online = 0 
WHERE is_online = 1 
AND last_seen < DATE_SUB(NOW(), INTERVAL 2 MINUTE);
